<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_model extends CI_Model {

    /**
     * @vars
     */
    private $_db;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'sitename';
        $this->_sitename_id = $this->get_sitename_id();
    }


    /**
     * Get list of non-deleted company
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @param string $sort
     * @param string $dir
     * @return array|bool
     */
    function get_all($limit=0, $offset=0, $filters=array(), $sort='sitename', $dir='ASC')
    {
        $user = $this->session->userdata('logged_in');
        $sql = "
                SELECT SQL_CALC_FOUND_ROWS s.*
                FROM {$this->_db} as s
            ";

        if ( ! empty($filters))
        {
            foreach ($filters as $key=>$value)
            {
                $value = $this->db->escape('%' . $value . '%');
                $sql .= " AND {$key} LIKE {$value}";
            }
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
     * Get specific company
     * 
     * @param int $id
     * @return array|bool
     */
    function get_company($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        if($this->config->item('master_sitename')==$this->config->item('sitename')){
            $sql = "
                SELECT *
                FROM {$this->_db}
                WHERE id = " . $this->db->escape($id) . "
            ";
        }else{
            $sql = "
                SELECT *
                FROM {$this->_db}
                WHERE id = " . $this->db->escape($id) . "
                    AND deleted = '0'
            ";
        }
   
        $query = $this->db->query($sql);

        if ($query->num_rows())
            return $query->row_array();
        else
            return FALSE;
    }


    /**
     * Add a new company
     *
     * @param array $data
     * @return mixed|bool
     */
    function add_company($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        //Add company
        $sql = "
            INSERT INTO {$this->_db} (
                sitename, sitename_name, sitename_email
            ) VALUES (
                " . $this->db->escape(strtolower($data['sitename'])) . ",
                " . $this->db->escape($data['sitename_name']) . ",
                " . $this->db->escape($data['sitename_email']) . "
            )
        ";

        $this->db->query($sql);
        
        if ($id = $this->db->insert_id()){
            log_message('info', sprintf(lang('log add_company'), $user['username'],$data['sitename']));
            //Add user for company
            // secure password
            $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
            $password = hash('sha512', $data['sitename'] . $salt);

            $sql = "
                INSERT INTO users (
                    username, password, salt, viewpass, is_admin, deleted, created, updated, updateby, site_id
                ) VALUES (
                    " . $this->db->escape($data['sitename']) . ",
                    " . $this->db->escape($password) . ",
                    " . $this->db->escape($salt) . ",
                    " . $this->db->escape($data['sitename']) . ",
                    " . $this->db->escape($data['is_admin']) . ",
                    '0',
                    '" . date('Y-m-d H:i') . "',
                    '" . date('Y-m-d H:i') . "',
                    " . $this->db->escape($user['id']) . " ,
                    " . $this->db->escape($id) . " 
                )
            ";
            $this->db->query($sql);
            
            // insert column view for company
            $sql = "
                INSERT INTO `customers_columns` (`name`, `status`, `site_id`, `ordering`) VALUES
                    ('id', '0', ".$id.", 1),
                    ('sitename', '0', ".$id.", 2),
                    ('name', '1', ".$id.", 3),
                    ('email', '1', ".$id.", 4),
                    ('data_entry', '1', ".$id.", 5),
                    ('posting', '1', ".$id.", 6),
                    ('priority', '0', ".$id.", 8),
                    ('comment', '0', ".$id.", 9),
                    ('comment_privat', '0', ".$id.", 9),
                    ('approveby', '0', ".$id.", 10),
                    ('deleted', '0', ".$id.", 11),
                    ('updated', '1', ".$id.", 12),
                    ('updateby', '1', ".$id.", 13);
            ";
            $this->db->query($sql);

            return TRUE;
        }else{
            return FALSE;
        }
        
        
    }
    
    /**
     * Edit an existing company
     * 
     * @param array $data
     * @return bool
     */
    function edit_company($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $sql = "
            UPDATE {$this->_db}
            SET
        ";  


        $sql .= "
                sitename_name = " . $this->db->escape($data['sitename_name']) . ",
                sitename_email = " . $this->db->escape($data['sitename_email']) . "    
            WHERE id = " . $this->db->escape($data['id']) . "
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows()){
            log_message('info', sprintf(lang('log edit_company'), $user['username'],$data['sitename']));
            return TRUE;
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
     * Check to see if a sitename already exists
     *
     * @param string $sitename
     * @return bool
     */
    function company_exists($sitename)
    {
        $sql = "
            SELECT id
            FROM {$this->_db}
            WHERE sitename = " . $this->db->escape($sitename) . "
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows())
            return TRUE;
        else
            return FALSE;
    }
    
}