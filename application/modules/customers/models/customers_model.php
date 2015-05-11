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
        //$this->_sitename_id = $this->get_sitename_id();
        $this->_sitename_id = $this->session->userdata("site_id");
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
                AND c.site_id =".$this->_sitename_id."
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

        $sql .= " GROUP BY c.id ORDER BY {$sort} {$dir}" ;

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
                SELECT c.*,d.*,p.*,u.username,s.sitename,s.list_status
                FROM {$this->_db} as c
                LEFT JOIN data_entry as d ON d.customer_id = c.id 
                LEFT JOIN posting as p ON p.customer_id = c.id
                LEFT JOIN users as u ON u.id = c.updateby  
                LEFT JOIN sitename as s ON s.id = c.site_id
                WHERE c.id = " . $this->db->escape($id) . "
            ";
        }else{
            $sql = "
                SELECT c.*,d.*,p.*,u.username,s.sitename,s.list_status
                FROM {$this->_db} as c
                LEFT JOIN data_entry as d ON d.customer_id = c.id 
                LEFT JOIN posting as p ON p.customer_id = c.id
                LEFT JOIN users as u ON u.id = c.updateby
                LEFT JOIN sitename as s ON s.id = c.site_id
                WHERE c.id = " . $this->db->escape($id) . "
                    AND c.deleted = '0'
                    AND c.site_id = ".$this->_sitename_id."
            ";
        }
                
        $query = $this->db->query($sql);

        if ($query->num_rows())
            return $query->row_array();
        else
            return FALSE;
    }

    /**
     * Get specific column
     * 
     * @return array|bool
     */
    function get_column()
    {
        $sql = "
                SELECT *
                FROM customers_columns
                WHERE site_id = ".$this->_sitename_id."
            ";
        
        $sql .= " ORDER BY ordering ASC";
        
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
                name, email, priority, approveby,comment, comment_privat, created, updated, updateby, site_id, on_hold
            ) VALUES (
                " . $this->db->escape($data['name']) . ",
                " . $this->db->escape($data['email']) . ",
                " . $this->db->escape($data['priority']) . ",
                " . $this->db->escape($data['approveby']) . ",    
                " . $this->db->escape($data['comment']) . ",
                " . $this->db->escape($data['comment_privat']) . ",
                '" . date('Y-m-d H:i:s') . "',
                '" . date('Y-m-d H:i:s') . "',
                " . $this->db->escape($user['id']) . " ,
                " . $this->db->escape($this->_sitename_id) . " ,
                " . $this->db->escape(isset($data['on_hold'])?$data['on_hold']:'0') . "
            )
        ";
            
        $this->db->query($sql);

        if ($id = $this->db->insert_id()){
            log_message('info', sprintf(lang('log add_customer'), $user['username'],$data['name'],$this->config->item('sitename')));
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
            if( $this->session->userdata("list_status")==1 || ($this->config->item('master_sitename')==$this->config->item('sitename'))){
                $this->check_add_task_list($data,$id,$this->_sitename_id);
            }
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
            priority = " . $this->db->escape($data['priority']) . ",
            approveby = " . $this->db->escape($data['approveby']) . ",
            comment = " . $this->db->escape($data['comment']) . ", 
            comment_privat = " . $this->db->escape($data['comment_privat']) . ", 
            updated = '" . date('Y-m-d H:i:s') . "',
            updateby = " . $this->db->escape($user['id']) . ",
            on_hold = " . $this->db->escape(isset($data['on_hold'])?$data['on_hold']:'0') . "
            WHERE id = " . $this->db->escape($data['id']) . "
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows()){
             log_message('info', sprintf(lang('log edit_customer'), $user['username'],$data['name'],$this->config->item('sitename')));
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
            if( $this->session->userdata("list_status")==1 || ($this->config->item('master_sitename')==$this->config->item('sitename'))){
                $this->check_add_task_list($data,$data['id'],$data['site_id']);
                $this->edit_task_list($data,$data['id'],$data['site_id']);
            }
            return TRUE;
        }else{
            return FALSE;    
        }
    }

    /**
     * Edit an existing customer
     * 
     * @param array $data
     * @return bool
     */
    function edit_column($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $affected_row = '';
        foreach ($data as $key => $value) {
            $sql = "
                UPDATE customers_columns
                SET
                status = " . $this->db->escape($value) . "
                WHERE site_id = " . $this->db->escape($this->_sitename_id) . "
                AND name = ". $this->db->escape($key) ."
            ";
            $this->db->query($sql);
        }
        log_message('info', sprintf(lang('log edit_column'), $user['username'],$this->config->item('sitename')));
        return TRUE;
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
        
        $customer = $this->get_customer($id);
        
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
        
        if ($this->db->affected_rows()){
            log_message('info', sprintf(lang('log delete_customer'), $user['username'],$customer['name'],$this->config->item('sitename')));
            return TRUE;
        }else{
            return FALSE;
        }
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
        $customer_data = $this->get_customer($id);
        $user = $this->session->userdata('logged_in');
        $this->db->query("DELETE FROM {$this->_db} WHERE id = ".$this->db->escape($id));
        
        $customer = $this->get_customer($id);
        
        if (!$customer){
            log_message('info', sprintf(lang('log master_delete_customer'), $user['username'],$customer_data['name'],$customer_data['sitename'],$this->config->item('sitename')));
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * Update an existing customer column
     * 
     * @param array $name
     * @return bool
     */
    function update_column($name)
    {
        $sql = "
                SELECT s.id
                FROM sitename as s
            ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        foreach ($data as $key=>$user)
        {
             // insert column view for company
            $sql = "
                INSERT INTO `customers_columns` (`name`, `status`, `site_id`, `ordering`) VALUES
                    ('".$name."', '0', ".$user['id'].", 14);
            ";
            $this->db->query($sql);
        }
        return TRUE;
    }
    
     /**
     * Update an existing customer TABLE
     * 
     * @param array $name
     * @return bool
     */
    function update_table()
    {
        $sql ="ALTER TABLE `customers` ADD `comment_privat` TEXT NOT NULL ;";
        $this->db->query($sql);
        return TRUE;
    }
    //GET Task List by day
    function get_task_list_by_task_id($task_id,$site_id){
         $sql = "
                SELECT tl.*
                FROM task_list as tl
                WHERE tl.task_id = " . $this->db->escape($task_id) . "
                AND tl.task_list_type = '0' AND tl.start_date != ''
                AND tl.site_id = " . $this->db->escape($site_id) . "
            ";
        $query = $this->db->query($sql);
         if ($query->num_rows())
            return $query->result_array();
        else
            return FALSE;
    }
    //GET Task List by day
    function get_task_list_by_day($Day,$site_id){
         $sql = "
                SELECT tl.*
                FROM task_list as tl
                WHERE DAY(tl.start_date) = " . $this->db->escape($Day) . "
                AND tl.task_list_type = '1'
                AND tl.site_id = ".$this->db->escape($site_id). "
            ";
        $query = $this->db->query($sql);
         if ($query->num_rows())
            return $query->result_array();
        else
            return FALSE;
    }
    
    //Check Task checked List
    function check_task_list_checked($task_list_id,$date){
         $sql = "
                SELECT tc.*
                FROM task_checked as tc
                WHERE tc.task_checked_date = " . $this->db->escape($date) . "
                AND tc.task_list_id = " . $this->db->escape($task_list_id) . "
            ";
        $query = $this->db->query($sql);
         if ($query->num_rows())
            return $query->result_array();
        else
            return FALSE;
    }
    //GET Task checked List by day
    function get_task_list_checked_by_DayMonth($Day,$Month,$task_list_id){
         $sql = "
                SELECT tc.*
                FROM task_checked as tc
                WHERE DAY(tc.task_checked_date) = " . $this->db->escape($Day) . "
                AND MONTH(tc.task_checked_date) = " . $this->db->escape($Month) . "
                AND tc.task_list_id = " . $this->db->escape($task_list_id) . "
            ";
        $query = $this->db->query($sql);
         if ($query->num_rows())
            return $query->result_array();
        else
            return FALSE;
    }
    //GET Task List
    function get_task_list($id)
    {
        $DateString = "%m/%d/%Y";
         $sql = "
                SELECT *
                FROM task_list
                WHERE customer_id = " . $this->db->escape($id) . "
            ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
       
        if ($query->num_rows() > 0){
            foreach($data as $item){
                
                if($item['task_id']==3){
                    $results['results']["repeat3"] = $item['task_repeat'];
                    $results['results']["start_date3"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
                }
                if($item['task_id']==4){
                    $results['results']["repeat4"] = $item['task_repeat'];
                    $results['results']["start_date4"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
                }
                if($item['task_id']==5){
                    $results['results']["repeat5"] = $item['task_repeat'];
                    $results['results']["start_date5"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
                }
                if($item['task_id']==6){
                    $results['results']["repeat6"] = $item['task_repeat'];
                    $results['results']["start_date6"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
                }
                if($item['task_id']==7){
                    $results['results']["repeat7"] = $item['task_repeat'];
                    $results['results']["start_date7"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
                }
                if($item['task_id']==8){
                    $results['results']["repeat8"] = $item['task_repeat'];
                    $results['results']["start_date8"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
                }
//                if($item['task_id']==9){
//                    $results['results']["repeat9"] = $item['task_repeat'];
//                    $results['results']["start_date9"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
//                
//                }
//                if($item['task_id']==10){
//                    $results['results']["repeat10"] = $item['task_repeat'];
//                    $results['results']["start_date10"] = ($item['start_date']!=NULL) ? mdate($DateString, strtotime($item['start_date'])):"";
//                }
            }
        }else{
            $results['results'] = NULL;
        }
        return $results;
    }
    //Add Task List
    function add_task_list($task_id,$site_id,$customer_id,$repeat,$task_list_type,$start_date)
    {
        if($start_date!=''){
            $newDate = date("Y-m-d", strtotime($start_date));
        }else{
            $newDate = NULL;
        }
         $sql = "
            INSERT INTO task_list (
                task_id, site_id, customer_id, task_list_type, task_repeat, start_date
            ) VALUES (
                " . $this->db->escape($task_id) . ",
                " . $this->db->escape($site_id) . ",
                " . $this->db->escape($customer_id) . ",
                '" . $this->db->escape($task_list_type) . "',    
                " . $this->db->escape($repeat) . ",
                " . $this->db->escape($newDate) . "
            )
        ";
            
        $this->db->query($sql);
    }
    //Add Task List
    function update_task_list($task_id,$site_id,$customer_id,$repeat,$task_list_type,$start_date)
    {
        if($start_date!=''){
            $newDate = date("Y-m-d", strtotime($start_date));
        }else{
            $newDate = NULL;
        }
        
         $sql = "
                UPDATE task_list
                SET
                task_repeat = " . $this->db->escape(isset($repeat)?$repeat:'0') . ",
                task_list_type = '" . $this->db->escape($task_list_type) . "',
                start_date = " . $this->db->escape($newDate) . "
                WHERE task_id = " . $this->db->escape($task_id) . "
                AND customer_id = " . $this->db->escape($customer_id) . "
            ";
            $this->db->query($sql);
    }
    //Add Task List
    function edit_task_list($data,$id,$site_id)
    {
        $DateString = "%Y-%m-%d";
        $sql_in = "SELECT tl.task_id FROM task_list as tl WHERE tl.customer_id ='".$data["id"]."' ";
        $sql = "SELECT t.task_id FROM task as t WHERE t.task_id in(".$sql_in.") ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0){
            $task =  $query->result_array();
            foreach($task as $item){
                $this->clear_task_checked($item['task_id'],$site_id,$id);
                if($item['task_id']==3)
                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat3'],1,$data['start_date3']);
                if($item['task_id']==4)
                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat4'],1,$data['start_date4']);
                if($item['task_id']==5)
                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat5'],1,$data['start_date5']);
                if($item['task_id']==6)
                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat6'],1,$data['start_date6']);
                if($item['task_id']==7)
                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat7'],1,$data['start_date7']);
                if($item['task_id']==8)
                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat8'],1,$data['start_date8']);
//                if($item['task_id']==9)
//                    $this->update_task_list($item['task_id'],$site_id,$id,$data['repeat9'],1,$data['start_date9']);
//                if($item['task_id']==10)
//                    $this->update_task_list($item['task_id'],$this->_sitename_id,$id,$data['repeat10'],1,$data['start_date10']);
                if($item['task_id']==11)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_monday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==12)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_tuesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==13)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_wednesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==14)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_thursday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==15)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_friday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==16)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_monday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==17)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_tuesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==18)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_wednesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==19)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_thursday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==20)
                    $this->update_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_friday'])? mdate($DateString,time()): NULL);
            }
        }
    }
    
    //Check add task list
    function check_add_task_list($data,$id,$site_id)
    {
        $DateString = "%Y-%m-%d";
        $sql_not_in = "SELECT tl.task_id FROM task_list as tl WHERE tl.customer_id ='".$id."' ";
        $sql = "SELECT t.task_id FROM task as t WHERE t.task_id not in(".$sql_not_in.") ";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0){
            $task =  $query->result_array();
            foreach($task as $item){
                if($item['task_id']==3)
                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat3'],1,$data['start_date3']);
                if($item['task_id']==4)
                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat4'],1,$data['start_date4']);
                if($item['task_id']==5)
                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat5'],1,$data['start_date5']);
                if($item['task_id']==6)
                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat6'],1,$data['start_date6']);
                if($item['task_id']==7)
                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat7'],1,$data['start_date7']);
                if($item['task_id']==8)
                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat8'],1,$data['start_date8']);
//                if($item['task_id']==9)
//                    $this->add_task_list($item['task_id'],$site_id,$id,$data['repeat9'],1,$data['start_date9']);
//                if($item['task_id']==10)
//                    $this->add_task_list($item['task_id'],$this->_sitename_id,$id,$data['repeat10'],1,$data['start_date10']);
                if($item['task_id']==11)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_monday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==12)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_tuesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==13)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_wednesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==14)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_thursday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==15)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['data_entry_friday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==16)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_monday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==17)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_tuesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==18)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_wednesday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==19)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_thursday'])? mdate($DateString,time()): NULL);
                if($item['task_id']==20)
                    $this->add_task_list($item['task_id'],$site_id,$id,1,0,isset($data['posting_friday'])? mdate($DateString,time()): NULL);
            }
        }
        
    }
    /**
     * Get specific column
     * 
     * @return array|bool
     */
    function get_holiday()
    {
        $sql = "
                SELECT DATE_FORMAT(date,'%m-%d') as date
                FROM holidays
            ";
        
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0){
            foreach($query->result_array() as $item){
                $data[] = $item['date'];
            }
            $results['results'] = $data;
        }else{
            $results['results'] = NULL;
        }
        return $results;
    }
    
    //Add Task Checked
    function add_task_list_checked($task_list_id,$date)
    {
        if($date!=''){
            $newDate = date("Y-m-d", strtotime($date));
        }else{
            $newDate = NULL;
        }
         $sql = "
            INSERT INTO task_checked (
                task_list_id,task_checked_date
            ) VALUES (
                " . $this->db->escape($task_list_id) . ",
                " . $this->db->escape($newDate) . "
            )
        ";
        $this->db->query($sql);
    }
    
    //Update Task Checkd
    function update_task_list_checked($task_checked_id,$task_list_id,$date)
    {
        if($date!=''){
            $newDate = date("Y-m-d", strtotime($date));
        }else{
            $newDate = NULL;
        }
         $sql = "
                UPDATE task_checked
                SET
                task_checked_date = " . $this->db->escape($newDate) . "
                WHERE task_checked_id = " . $this->db->escape($task_checked_id) . "
                AND task_list_id = " . $this->db->escape($task_list_id) . "
            ";
            $this->db->query($sql);
    }
    function clear_task_checked($task_id,$site_id,$customer_id)
    {
       $sql = "
                SELECT *
                FROM task_list
                WHERE customer_id = " . $this->db->escape($customer_id) . "
                AND task_id = " . $this->db->escape($task_id) . " 
            ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        $newDate = date("Y-m-d", time());
        if ($query->num_rows() > 0){
            foreach($data as $item){
                $this->db->query("DELETE FROM task_checked WHERE task_checked_date>".$newDate." AND task_checked_status='0' AND task_list_id = ".$this->db->escape($item['task_list_id']));
            }
        }
    }

    function get_checked_ByDate($date,$site_id)
    {
        $sql = "
                SELECT t.*,tc.*,tl.*,s.*,c.id as customer_id,c.name as customer_name,c.email as customer_email
                FROM task_checked as tc
                LEFT JOIN task_list as tl ON tl.task_list_id = tc.task_list_id 
                LEFT JOIN task as t ON t.task_id = tl.task_id 
                LEFT JOIN sitename as s ON s.id = tl.site_id
                LEFT JOIN customers as c ON c.id = tl.customer_id
                WHERE tc.task_checked_date = " . $this->db->escape($date) . " AND s.id=".$this->db->escape($site_id)." ORDER BY c.name";
        $query = $this->db->query($sql);
        if ($query->num_rows())
            return $query->result_array();
        else
            return FALSE;
    }
    function update_checked($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        
        $sql = "
            UPDATE task_checked
            SET
            task_checked_status = " . $this->db->escape($data['task_checked_status']) . "
            WHERE task_checked_id = " . $this->db->escape($data['task_checked_id']) . "
        ";
        $this->db->query($sql);
        $this->add_task_log($data);
    }
     function add_task_log($data)
    {
         $user = $this->session->userdata('logged_in');

         $sql = "
            INSERT INTO task_log (
                customer_id,task_log_name,task_log_status,user_id,site_id,task_date,task_log_date
            ) VALUES (
                " . $this->db->escape($data['customer_id']) . ",
                " . $this->db->escape($data['task_log_name']) . ",
                " . $this->db->escape($data['task_checked_status']) . ",
                " . $this->db->escape($user['id']) . ",
                " . $this->db->escape($data['site_id']) . ",
                " . $this->db->escape($data['task_date']) . ",
                NOW()
            )
        ";
        $this->db->query($sql);
    }
    
    function get_task_log_all($limit=0, $offset=0, $filters=array(), $sort='task_log_id', $dir='DESC',$filters_tabs=array(),$id)
    {   
        
        
         $sql = "
                SELECT SQL_CALC_FOUND_ROWS tl.task_log_id, tl.task_log_date,tl.task_log_name,tl.task_log_status,u.username as username, c.name as customer_name,tl.task_date
                FROM task_log as tl
                LEFT JOIN sitename as s ON s.id = tl.site_id
                LEFT JOIN customers as c ON c.id = tl.customer_id
                LEFT JOIN users as u ON u.id = tl.user_id
                WHERE tl.site_id =".$id."
            ";
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

        $sql .= " ORDER BY {$sort} {$dir}" ;

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
}