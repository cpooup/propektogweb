<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

    /**
     * @var string
     */
    private $_redirect_url;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the language files
        $this->lang->load('users');

        // load the users model
        $this->load->model('users_model');

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/users'));
        define('DEFAULT_LIMIT', 50);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "username");
        define('DEFAULT_DIR', "asc");

        // use the url in session (if available) to return to the previous filter/sorted/paginated list
        if ($this->session->userdata(REFERRER))
            $this->_redirect_url = $this->session->userdata(REFERRER);
        else
            $this->_redirect_url = THIS_URL;
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * User list page
     */
    public function index()
    {
        // get parameters
        $limit  = $this->input->get('limit')  ? $this->input->get('limit', TRUE)  : DEFAULT_LIMIT;
        $offset = $this->input->get('offset') ? $this->input->get('offset', TRUE) : DEFAULT_OFFSET;
        $sort   = $this->input->get('sort')   ? $this->input->get('sort', TRUE)   : DEFAULT_SORT;
        $dir    = $this->input->get('dir')    ? $this->input->get('dir', TRUE)    : DEFAULT_DIR;

        // get filters
        $filters = array();

        if ($this->input->get('username'))
            $filters['username'] = $this->input->get('username', TRUE);
        
         if ($this->input->get('updated'))
            $filters['updated'] = $this->input->get('updated', TRUE);
        
        if ($this->input->get('updateby'))
            $filters['updateby'] = $this->input->get('updateby', TRUE);
        
        if ($this->input->get('deleted'))
            $filters['deleted'] = $this->input->get('deleted', TRUE);
        
        // build filter string
        $filter = "";
        foreach ($filters as $key=>$value)
            $filter .= "&{$key}={$value}";

        // save the current url to session for returning
        $this->session->set_userdata(REFERRER, THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");

        // are filters being submitted?
        if ($this->input->post())
        {
            if ($this->input->post('clear'))
            {
                // reset button clicked
                redirect(THIS_URL);
            }
            else
            {
                // apply the filter(s)
                $filter = "";

                if ($this->input->post('username'))
                    $filter .= "&username=" . $this->input->post('username', TRUE);
                
                if ($this->input->post('updated'))
                    $filters['updated'] = $this->input->post('updated', TRUE);

                if ($this->input->post('updateby'))
                    $filters['updateby'] = $this->input->post('updateby', TRUE);
                
                if ($this->input->post('deleted'))
                   $filters['deleted'] = $this->input->post('deleted', TRUE);
                
                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $users = $this->users_model->get_all($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $users['total'],
            'per_page'   => $limit
        ));

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'    => lang('users title user_list'),
            'js_files_i18n' => array(
                $this->jsi18n->translate("/application/modules/users/assets/js/users_admin_i18n.js")
            )
        ));
        $data = $this->header_data;

        // create button to add new users
        $data['controls'] = array(
            'add_new' => array(
                'bootstrap_button_class' => 'btn-success tooltips',
                'bootstrap_icon_class'   => 'glyphicon-plus-sign',
                'url'                    => THIS_URL . '/add',
                'text'                   => lang('users button add_new_user'),
                'tooltip'                => lang('users tooltip add_new_user'),
                'data-target'            => '#myModal',
                'data-toggle'            => 'modal'
            )
        );

        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'users'      => $users['results'],
            'total'      => $users['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir
        );

        // load views
        $data['content'] = $this->load->view('admin/users_list', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }


    /**
     * Add new user
     */
    public function add()
    {
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('username', lang('admin input username'), 'required|trim|xss_clean|callback__check_username[]');
        $this->form_validation->set_rules('password', lang('users input password'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('password_repeat', lang('users input password_repeat'), 'required|trim|xss_clean|matches[password]');
        if ($this->form_validation->run($this) == TRUE)
        {
            // save the new user
            $saved = $this->users_model->add_user($this->input->post());

            if ($saved)
                $this->session->set_flashdata('message', sprintf(lang('users msg add_user_success'), $this->input->post('username')));
            else
                $this->session->set_flashdata('error', sprintf(lang('users error add_user_failed'), $this->input->post('username')));

            // return to list and display message
            redirect($this->_redirect_url);
        }
        
        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'  => lang('users title user_add')
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => NULL,
            'title'              => lang("users button add_new_user"),
            'password_required' => TRUE
        );

        // load views
        $data['content'] = $this->load->view('admin/users_form', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }


    /**
     * Edit existing user
     *
     * @param int $id
     */
    public function edit($id=NULL)
    {
        $user = $this->session->userdata('logged_in');
        $id = $user['id'];
        // make sure we have a numeric id
        if (is_null($id) || ! is_numeric($id))
            redirect($this->_redirect_url);

        // get the data
        $user = $this->users_model->get_user($id);

        // if empty results, return to list
        if ( ! $user)
            redirect($this->_redirect_url);

        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('password', lang('users input password'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('password_repeat', lang('users input password_repeat'), 'required|trim|xss_clean|matches[password]');
        $this->form_validation->set_rules('password_current', lang('users input password_current'), 'required|trim|xss_clean|callback__check_password[]');

        if ($this->form_validation->run($this) == TRUE)
        {
            // save the changes
            $saved = $this->users_model->edit_user($this->input->post());

            if ($saved)
                $this->session->set_flashdata('message', sprintf(lang('users msg edit_user_success'), $this->input->post('username')));
            else
                $this->session->set_flashdata('error', sprintf(lang('users error edit_user_failed'), $this->input->post('username')));

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'  => lang('users title user_edit')
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => $user,
            'user_id'           => $id,
            'title'           => lang("users manage account"),
            'password_required' => FALSE
        );

        // load views
        $data['content'] = $this->load->view('admin/users_edit', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }


    /**
     * Delete a user
     *
     * @param int $id
     */
    public function delete($id=NULL)
    {
        // make sure we have a numeric id
        if ( ! is_null($id) || ! is_numeric($id))
        {
            // get user details
            $user = $this->users_model->get_user($id);

            if ($user)
            {

                if($this->config->item('master_sitename')==$this->config->item('sitename')){
                    $delete = $this->users_model->delete_master_user($id);
                }else{
                    // soft-delete the user
                    $delete = $this->users_model->delete_user($id);
                }
                if ($delete)
                    $this->session->set_flashdata('message', sprintf(lang('users msg delete_user'), $user['username'] ));
                else
                    $this->session->set_flashdata('error', sprintf(lang('users error delete_user'), $user['username'] ));
            }
            else
            {
                $this->session->set_flashdata('error', lang('users error user_not_exist'));
            }
        }
        else
        {
            $this->session->set_flashdata('error', lang('users error user_id_required'));
        }

        // return to list and display message
        redirect($this->_redirect_url);
    }

    /**
     * Make sure username is available for ajax
     *
     * @param string $username
     * @param string|null $current
     * @return bool|int
     */
    public function user_availability()
    {
        if ($this->input->post('username')){
            $username = $this->input->post('username', TRUE);
            if ($this->users_model->username_exists($username))
            {
               echo "false";
            }else{
               echo "true";
            }
        }else{
            echo "false";
        }
    }
    
    /**
     * Make sure password is match for ajax
     *
     */
    public function user_password_current()
    {
        if ($this->input->post('password_current')){
            $password_current = $this->input->post('password_current', TRUE);
            if ($this->users_model->user_password($password_current))
            {
               echo "true";
            }else{
               echo "false";
            }
        }else{
            echo "true";
        }
    }
    
    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/


    /**
     * Make sure username is available
     *
     * @param string $username
     * @param string|null $current
     * @return bool|int
     */
    function _check_username($username, $current)
    {
        if (trim($username) != trim($current) && $this->users_model->username_exists($username))
        {
            $this->form_validation->set_message('_check_username', sprintf(lang('users error username_exists'), $username));
            return FALSE;
        }
        else
            return $username;
    }
    
    /**
     * Make sure username is available
     *
     * @param string $username
     * @param string|null $current
     * @return bool|int
     */
    function _check_password($password, $current)
    { 
        if (trim($password) != trim($current) && !$this->users_model->user_password($password))
        { 
            $this->form_validation->set_message('_check_password', sprintf(lang('password error not match'), $password));
            return FALSE;
        }
        else{
            return $password;
        }
    }

}
