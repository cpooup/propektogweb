<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_model extends CI_Model {

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
        $this->_db = 'holidays';
        //$this->_sitename_id = $this->get_sitename_id();
        $this->_sitename_id = $this->session->userdata("site_id");
    }


    /**
     * Get list of non-deleted holiday
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @param string $sort
     * @param string $dir
     * @return array|bool
     */
    function get_all($limit=0, $offset=0, $filters=array(), $sort='holiday_name', $dir='ASC')
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
     * Get specific holiday
     * 
     * @param int $id
     * @return array|bool
     */
    function get_holiday($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        $sql = "
                SELECT *
                FROM {$this->_db}
                WHERE holiday_id = " . $this->db->escape($id) . "
            ";
   
        $query = $this->db->query($sql);

        if ($query->num_rows())
            return $query->row_array();
        else
            return FALSE;
    }


    /**
     * Add a new holiday
     *
     * @param array $data
     * @return mixed|bool
     */
    function add_holiday($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        //Add holiday
        $newDate = date("Y-m-d", strtotime($data['date']));
        $sql = "
            INSERT INTO {$this->_db} (
                holiday_name, date
            ) VALUES (
                " . $this->db->escape($data['holiday_name']) . ",
                " . $this->db->escape($newDate) . "
            )
        ";

        $this->db->query($sql);
        
        if ($id = $this->db->insert_id()){
            return TRUE;
        }else{
            return FALSE;
        }

    }
    
    /**
     * Edit an existing holiday
     * 
     * @param array $data
     * @return bool
     */
    function edit_holiday($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $newDate = date("Y-m-d", strtotime($data['date']));
        $sql = "
            UPDATE {$this->_db}
            SET
        ";  


        $sql .= "
                holiday_name = " . $this->db->escape($data['holiday_name']) . ",
                date = " . $this->db->escape($newDate) . "   
            WHERE holiday_id = " . $this->db->escape($data['id']) . "
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows()){
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
    
    
    function delete_holiday($id=NULL)
    {
       if (is_null($id))
            return FALSE;
       
        $this->db->query("DELETE FROM {$this->_db} WHERE holiday_id = ".$this->db->escape($id));
        
        $holiday = $this->get_holiday($id);
        
        if (!$holiday){

            return TRUE;
        }else{
            return FALSE;
        }
    }
    
}