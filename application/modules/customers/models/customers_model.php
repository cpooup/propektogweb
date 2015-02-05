<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers_model extends CI_Model {

    /**
     * @vars
     */
    private $_db;
    private $_sitename_id;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'customers';
        $this->_sitename_id = $this->get_sitename_id();
    }


    /**
     * Get list of non-deleted customers
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @param string $sort
     * @param string $dir
     * @return array|bool
     */
    function get_all($limit=0, $offset=0, $filters=array(), $sort='name', $dir='ASC',$filters_tabs=array())
    {   
        if($this->config->item('master_sitename')==$this->config->item('sitename')){
            $sql = "
                SELECT SQL_CALC_FOUND_ROWS c.*,d.*,p.*,u.username,s.sitename
                FROM {$this->_db} as c
                LEFT JOIN data_entry as d ON d.customer_id = c.id 
                LEFT JOIN posting as p ON p.customer_id = c.id
                LEFT JOIN users as u ON u.id = c.updateby
                LEFT JOIN sitename as s ON s.id = c.site_id
                WHERE 1=1
            ";
        }else{
            $sql = "
                SELECT SQL_CALC_FOUND_ROWS c.*,d.*,p.*,u.username,s.sitename
                FROM {$this->_db} as c
                LEFT JOIN data_entry as d ON d.customer_id = c.id 
                LEFT JOIN posting as p ON p.customer_id = c.id
                LEFT JOIN users as u ON u.id = c.updateby
                LEFT JOIN sitename as s ON s.id = c.site_id
                WHERE c.deleted = '0'
                AND c.site_id =".$this->_sitename_id['id']."
            ";
        }
        if ( ! empty($filters))
        {
            foreach ($filters as $key=>$value)
            {
                $value = $this->db->escape('%' . $value . '%');
                $sql .= " AND {$key} LIKE {$value}";
            }
        }
        
        if ( ! empty($filters_tabs))
        {
            $sql_day = '';
            foreach ($filters_tabs as $key=>$value)
            {
                $value = $this->db->escape('%' . $value . '%');
                if($sql_day==''){
                    $sql_day .= " {$key} LIKE {$value}";
                }else{
                    $sql_day .= " OR {$key} LIKE {$value}";
                }
            }
            $sql .=" AND (".$sql_day.")";
        }

        $sql .= " ORDER BY {$sort} {$dir}";

        if ($limit)
        {
            $offset = (int)$offset;
            $limit  = (int)$limit;
            $sql .= " LIMIT {$offset}, {$limit}";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
            $results['results'] = $query->result_array();
        else
            $results['results'] = NULL;

        $sql = "SELECT FOUND_ROWS() AS total";
        $query = $this->db->query($sql);
        $results['total'] = $query->row()->total;

        return $results;
    }

    
    /**
     * Get specific customer
     * 
     * @param int $id
     * @return array|bool
     */
    function get_customer($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        
        if($this->config->item('master_sitename')==$this->config->item('sitename')){
            $sql = "
                SELECT c.*,d.*,p.*,u.username,s.sitename
                FROM {$this->_db} as c
                LEFT JOIN data_entry as d ON d.customer_id = c.id 
                LEFT JOIN posting as p ON p.customer_id = c.id
                LEFT JOIN users as u ON u.id = c.updateby  
                LEFT JOIN sitename as s ON s.id = c.site_id
                WHERE c.id = " . $this->db->escape($id) . "
            ";
        }else{
            $sql = "
                SELECT c.*,d.*,p.*,u.username,s.sitename
                FROM {$this->_db} as c
                LEFT JOIN data_entry as d ON d.customer_id = c.id 
                LEFT JOIN posting as p ON p.customer_id = c.id
                LEFT JOIN users as u ON u.id = c.updateby
                LEFT JOIN sitename as s ON s.id = c.site_id
                WHERE c.id = " . $this->db->escape($id) . "
                    AND c.deleted = '0'
                    AND c.site_id = ".$this->_sitename_id['id']."
            ";
        }
                
        $query = $this->db->query($sql);

        if ($query->num_rows())
            return $query->row_array();
        else
            return FALSE;
    }


    /**
     * Add a new customer
     *
     * @param array $data
     * @return mixed|bool
     */
    function add_customer($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $sql = "
            INSERT INTO {$this->_db} (
                name, email, choice, priority, approveby,comment , created, updated, updateby, site_id
            ) VALUES (
                " . $this->db->escape($data['name']) . ",
                " . $this->db->escape($data['email']) . ",
                " . $this->db->escape($data['choice']) . ",
                " . $this->db->escape($data['priority']) . ",
                " . $this->db->escape($data['comment']) . ",
                " . $this->db->escape($data['approveby']) . ",
                '" . date('Y-m-d H:i:s') . "',
                '" . date('Y-m-d H:i:s') . "',
                " . $this->db->escape($user['id']) . " ,
                " . $this->db->escape($this->_sitename_id['id']) . " 
            )
        ";
            
        $this->db->query($sql);

        if ($id = $this->db->insert_id()){
            $sql = "
                INSERT INTO data_entry (
                    customer_id, data_entry_monday, data_entry_tuesday, data_entry_wednesday, data_entry_thursday, data_entry_friday
                ) VALUES (
                    " . $this->db->escape($id) . ",
                    " . $this->db->escape(isset($data['data_entry_monday'])?$data['data_entry_monday']:'0') . ",
                    " . $this->db->escape(isset($data['data_entry_tuesday'])?$data['data_entry_tuesday']:'0') . ",
                    " . $this->db->escape(isset($data['data_entry_wednesday'])?$data['data_entry_wednesday']:'0') . ",
                    " . $this->db->escape(isset($data['data_entry_thursday'])?$data['data_entry_thursday']:'0') . ",
                    " . $this->db->escape(isset($data['data_entry_friday'])?$data['data_entry_friday']:'0') . "
                )
            ";
            $this->db->query($sql);
            $sql = "
                INSERT INTO posting (
                    customer_id, posting_monday, posting_tuesday, posting_wednesday, posting_thursday, posting_friday
                ) VALUES (
                    " . $this->db->escape($id) . ",
                    " . $this->db->escape(isset($data['posting_monday'])?$data['posting_monday']:'0') . ",
                    " . $this->db->escape(isset($data['posting_tuesday'])?$data['posting_tuesday']:'0') . ",
                    " . $this->db->escape(isset($data['posting_wednesday'])?$data['posting_wednesday']:'0') . ",
                    " . $this->db->escape(isset($data['posting_thursday'])?$data['posting_thursday']:'0') . ",
                    " . $this->db->escape(isset($data['posting_friday'])?$data['posting_friday']:'0') . "
                )
            ";
            $this->db->query($sql);
            return $id;
        }else{
            return FALSE;
        }
    }

    // Get sitename id
    function get_sitename_id()
    {
         $sql = "SELECT id FROM sitename as s WHERE s.sitename like '".$this->config->item('sitename')."'";
         $query = $this->db->query($sql);

        if ($query->num_rows())
            return $query->row_array();
        else
            return FALSE;   
    }
    
    /**
     * Edit an existing customer
     * 
     * @param array $data
     * @return bool
     */
    function edit_customer($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $sql = "
            UPDATE {$this->_db}
            SET
            name = " . $this->db->escape($data['name']) . ",
            email = " . $this->db->escape($data['email']) . ",
            choice = " . $this->db->escape($data['choice']) . ",
            priority = " . $this->db->escape($data['priority']) . ",
            approveby = " . $this->db->escape($data['approveby']) . ",
            comment = " . $this->db->escape($data['comment']) . ",    
            updated = '" . date('Y-m-d H:i:s') . "',
            updateby = " . $this->db->escape($user['id']) . "
            WHERE id = " . $this->db->escape($data['id']) . "
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows()){
            $sql = "
                UPDATE data_entry
                SET
                data_entry_monday = " . $this->db->escape(isset($data['data_entry_monday'])?$data['data_entry_monday']:'0') . ",
                data_entry_tuesday = " . $this->db->escape(isset($data['data_entry_tuesday'])?$data['data_entry_tuesday']:'0') . ",
                data_entry_wednesday = " . $this->db->escape(isset($data['data_entry_wednesday'])?$data['data_entry_wednesday']:'0') . ",
                data_entry_thursday = " . $this->db->escape(isset($data['data_entry_thursday'])?$data['data_entry_thursday']:'0') . ",
                data_entry_friday = " . $this->db->escape(isset($data['data_entry_friday'])?$data['data_entry_friday']:'0') . "
                WHERE customer_id = " . $this->db->escape($data['id']) . "
            ";
            $this->db->query($sql);
            $sql = "
                UPDATE posting
                SET
                posting_monday = " . $this->db->escape(isset($data['posting_monday'])?$data['posting_monday']:'0') . ",
                posting_tuesday = " . $this->db->escape(isset($data['posting_tuesday'])?$data['posting_tuesday']:'0') . ",
                posting_wednesday = " . $this->db->escape(isset($data['posting_wednesday'])?$data['posting_wednesday']:'0') . ",
                posting_thursday = " . $this->db->escape(isset($data['posting_thursday'])?$data['posting_thursday']:'0') . ",
                posting_friday = " . $this->db->escape(isset($data['posting_friday'])?$data['posting_friday']:'0') . "
                WHERE customer_id = " . $this->db->escape($data['id']) . "
            ";
            $this->db->query($sql);
            return TRUE;
        }else{
            return FALSE;    
        }
    }

    /**
     * Soft delete an existing customer
     * 
     * @param int $id
     * @return bool
     */
    function delete_customer($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $sql = "
            UPDATE {$this->_db}
            SET
                deleted = '1',
                updated = '" . date('Y-m-d H:i:s') . "',
                updateby = " . $this->db->escape($user['id']) . "
            WHERE id = " . $this->db->escape($id) . "
                AND id > 1
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows())
            return TRUE;
        else
            return FALSE;
    }
    
    /**
     * Master delete an existing customer
     * 
     * @param int $id
     * @return bool
     */
    function delete_master_customer($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        
        $this->db->query("DELETE FROM {$this->_db} WHERE id = ".$this->db->escape($id));
        
        $customer = $this->get_customer($id);
        
        if (!$customer)
            return TRUE;
        else
            return FALSE;
    }

}