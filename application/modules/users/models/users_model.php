<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

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
        $this->_db = 'users';
        $this->_sitename_id = $this->get_sitename_id();
    }


    /**
     * Get list of non-deleted users
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @param string $sort
     * @param string $dir
     * @return array|bool
     */
    function get_all($limit=0, $offset=0, $filters=array(), $sort='username', $dir='ASC')
    {
        $user = $this->session->userdata('logged_in');
        if($this->config->item('master_sitename')==$this->config->item('sitename')){
            $sql = "
                SELECT SQL_CALC_FOUND_ROWS u.*,s.sitename,uu.username as createby
                FROM {$this->_db} as u
                LEFT JOIN sitename as s ON s.id = u.site_id
                LEFT JOIN users as uu ON uu.id = u.updateby
                WHERE u.id != ".$user['id']."
            ";
        }else{
            $sql = "
                SELECT SQL_CALC_FOUND_ROWS u.*,s.sitename,uu.username as createby
                FROM {$this->_db} as u
                LEFT JOIN sitename as s ON s.id = u.site_id
                LEFT JOIN users as uu ON uu.id = u.updateby
                WHERE u.deleted = '0' AND u.id != ".$user['id']." 
                AND u.site_id =".$this->_sitename_id['id']."
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

    function get_all_user_by_company($site_id)
    {
         $user = $this->session->userdata('logged_in');
         $sql = "
                SELECT SQL_CALC_FOUND_ROWS u.*,s.sitename,uu.username as createby
                FROM {$this->_db} as u
                LEFT JOIN sitename as s ON s.id = u.site_id
                LEFT JOIN users as uu ON uu.id = u.updateby
                WHERE u.deleted = '0'
                AND u.site_id =".$site_id."
            ";

       
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
     * Get specific user
     * 
     * @param int $id
     * @return array|bool
     */
    function get_user($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        if($this->config->item('master_sitename')==$this->config->item('sitename')){
            $sql = "
                SELECT u.*,s.sitename
                FROM {$this->_db} as u
                LEFT JOIN sitename as s ON s.id = u.site_id
                WHERE u.id = " . $this->db->escape($id) . "
            ";
        }else{
            $sql = "
                SELECT u.*,s.sitename
                FROM {$this->_db} as u
                LEFT JOIN sitename as s ON s.id = u.site_id
                WHERE u.id = " . $this->db->escape($id) . "
                    AND u.deleted = '0'
            ";
        }
   
        $query = $this->db->query($sql);

        if ($query->num_rows())
            return $query->row_array();
        else
            return FALSE;
    }


    /**
     * Add a new user
     *
     * @param array $data
     * @return mixed|bool
     */
    function add_user($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        // secure password
        $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
        $password = hash('sha512', $data['password'] . $salt);

        $sql = "
            INSERT INTO {$this->_db} (
                username, password, salt, viewpass, is_admin, deleted, created, updated, updateby, site_id
            ) VALUES (
                " . $this->db->escape($data['username']) . ",
                " . $this->db->escape($password) . ",
                " . $this->db->escape($salt) . ",
                " . $this->db->escape($data['password']) . ",
                " . $this->db->escape($data['is_admin']) . ",
                '0',
                '" . date('Y-m-d H:i') . "',
                '" . date('Y-m-d H:i') . "',
                " . $this->db->escape($user['id']) . " ,
                " . $this->db->escape($this->_sitename_id['id']) . " 
            )
        ";

        $this->db->query($sql);

        if ($id = $this->db->insert_id()){
            log_message('info', sprintf(lang('log add_user'), $user['username'],$data['username'],$this->config->item('sitename')));
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
     * Edit an existing user
     * 
     * @param array $data
     * @return bool
     */
    function edit_user($data=array())
    {
        if (empty($data))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $sql = "
            UPDATE {$this->_db}
            SET
        ";
   
        if ($data['password'] != '')
        {
            // secure password
            $salt     = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
            $password = hash('sha512', $data['password'] . $salt);

            $sql .= "
                password = " . $this->db->escape($password) . ",
                salt = " . $this->db->escape($salt) . ",
            ";
        }

        $sql .= "
                viewpass = " . $this->db->escape($data['password']) . ",
                updated = '" . date('Y-m-d H:i') . "',
                updateby = " . $this->db->escape($user['id']) . "    
            WHERE id = " . $this->db->escape($data['id']) . "
                AND deleted = '0'
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows()){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Soft delete an existing user
     * 
     * @param int $id
     * @return bool
     */
    function delete_user($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        $user = $this->session->userdata('logged_in');
        $user_data = $this->get_user($id); 
        
        $user_count = $this->get_all_user_by_company($user_data['site_id']);
        
        if($user_count['total'] < 2)
            return FALSE;    
        
        $sql = "
            UPDATE {$this->_db}
            SET
                deleted = '1',
                updated = '" . date('Y-m-d H:i') . "',
                updateby = " . $this->db->escape($user['id']) . "
            WHERE id = " . $this->db->escape($id) . "
                AND id > 1 AND site_id = ".$data_user['site_id']."
        ";

        $this->db->query($sql);
        
        if ($this->db->affected_rows()){
            log_message('info', sprintf(lang('log delete_user'), $user['username'],$user_data['username'],$this->config->item('sitename')));
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
    function delete_master_user($id=NULL)
    {
        if (is_null($id))
            return FALSE;
        $user_data = $this->get_user($id);
        $user_count = $this->get_all_user_by_company($user_data['site_id']);
        if($user_count['total'] < 2)
            return FALSE;    
            
        $this->db->query("DELETE FROM {$this->_db} WHERE id = ".$this->db->escape($id)." AND id > 1 AND site_id=".$user_data['site_id']);
        $user_login = $this->session->userdata('logged_in');
        $user = $this->get_user($id);
        if (!$user){
            log_message('info', sprintf(lang('log master_delete_user'), $user_login['username'],$user_data['username'],$user_data['sitename'],$this->config->item('sitename')));
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Check for valid login credentials
     *
     * @param string $username
     * @param string $password
     * @return array|bool
     */
    function login($username=NULL, $password=NULL)
    {
        if (is_null($username) || is_null($password))
            return FALSE;

        $sql = "
            SELECT
                id,
                username,
                password,
                salt,
                viewpass,
                is_admin,
                deleted,
                created,
                updated,
                site_id,
                updateby
            FROM {$this->_db}
            WHERE (username = " . $this->db->escape($username) . ")
                AND site_id =(SELECT id FROM sitename as s WHERE s.sitename like '".$this->config->item('sitename')."')
                AND deleted = '0'
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            $results = $query->row_array();
            $salted_password = hash('sha512', $password . $results['salt']);

            if ($results['password'] == $salted_password)
            {
                unset($results['password']);
                unset($results['salt']);
                return $results;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }

    }

    
    /**
     * Check to see if a username already exists
     *
     * @param string $username
     * @return bool
     */
    function username_exists($username)
    {
        $sql = "
            SELECT id
            FROM {$this->_db}
            WHERE username = " . $this->db->escape($username) . "
            LIMIT 1
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows())
            return TRUE;
        else
            return FALSE;
    }
    
    /**
     * Check to see if a password match
     *
     * @param string $password_current
     * @return bool
     */
    function user_password($password_current)
    {
        $user = $this->session->userdata('logged_in');
        $sql = "
            SELECT
                id,
                username,
                password,
                salt,
                viewpass,
                is_admin,
                deleted,
                created,
                updated,
                site_id,
                updateby
            FROM {$this->_db}
            WHERE (username = " . $this->db->escape($user['username']) . ")
                AND site_id =(SELECT id FROM sitename as s WHERE s.sitename like '".$this->config->item('sitename')."')
                AND deleted = '0'
            LIMIT 1
        ";
        $query = $this->db->query($sql);
        if ($query->num_rows())
        {
            $results = $query->row_array();
            $salted_password = hash('sha512', $password_current . $results['salt']);

            if ($results['password'] == $salted_password)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}