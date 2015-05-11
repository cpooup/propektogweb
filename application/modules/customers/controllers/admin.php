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
        $this->load->helper('date');
        // load the language files
        $this->lang->load('customers');

        // load the customers model
        $this->load->model('customers_model');

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/customers'));
        define('DEFAULT_LIMIT', 50);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "name");
        define('DEFAULT_DIR', "asc");
        define('DEFAULT_TAB', "");
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
        $tab    = $this->input->get('tab')    ? $this->input->get('tab', TRUE)    : DEFAULT_TAB;
        // get filters
        $filters = array();
        
        if ($this->input->get('id'))
            $filters['id'] = $this->input->get('id', TRUE);
        
        if ($this->input->get('sitename'))
            $filters['sitename'] = $this->input->get('sitename', TRUE);
        
        if ($this->input->get('name'))
            $filters['name'] = $this->input->get('name', TRUE);

        if ($this->input->get('email'))
            $filters['email'] = $this->input->get('email', TRUE);
        
        if ($this->input->get('comment'))
            $filters['comment'] = $this->input->get('comment', TRUE);
        
        if ($this->input->get('comment_privat'))
            $filters['comment_privat'] = $this->input->get('comment_privat', TRUE);
        
        if ($this->input->get('approveby'))
            $filters['approveby'] = $this->input->get('approveby', TRUE);
        
        if ($this->input->get('updated'))
            $filters['updated'] = $this->input->get('updated', TRUE);
        
        if ($this->input->get('updateby'))
            $filters['updateby'] = $this->input->get('updateby', TRUE);
        
        if ($this->input->get('deleted'))
            $filters['deleted'] = $this->input->get('deleted', TRUE);
        
        // get filters by tab
        $filters_tabs = array();
        if($tab==2){
            $filters_tabs['data_entry_monday'] = '1';
            $filters_tabs['posting_monday'] = '1';
        }else if($tab==3){
            $filters_tabs['data_entry_tuesday'] = '1';
            $filters_tabs['posting_tuesday'] = '1';
        }else if($tab==4){
            $filters_tabs['data_entry_wednesday'] = '1';
            $filters_tabs['posting_wednesday'] = '1';
        }else if($tab==5){
            $filters_tabs['data_entry_thursday'] = '1';
            $filters_tabs['posting_thursday'] = '1';
        }else if($tab==6){
            $filters_tabs['data_entry_friday'] = '1';
            $filters_tabs['posting_friday'] = '1';
        }
            
        // build filter string
        $filter = "";
        foreach ($filters as $key=>$value)
            $filter .= "&{$key}={$value}";

        // save the current url to session for returning
        $this->session->set_userdata(REFERRER, THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}&tab={$tab}");

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
                if ($this->input->post('id'))
                    $filters['id'] = $this->input->post('id', TRUE);
                 
                if ($this->input->post('sitename'))
                    $filters['sitename'] = $this->input->post('sitename', TRUE);
                
                if ($this->input->post('name'))
                    $filters['name'] = $this->input->post('name', TRUE);

                if ($this->input->post('email'))
                    $filters['email'] = $this->input->post('email', TRUE);

                if ($this->input->post('comment'))
                    $filters['comment'] = $this->input->post('comment', TRUE);
                
                if ($this->input->post('comment_privat'))
                    $filters['comment_privat'] = $this->input->post('comment_privat', TRUE);

                if ($this->input->post('approveby'))
                    $filters['approveby'] = $this->input->post('approveby', TRUE);

                if ($this->input->post('updated'))
                    $filters['updated'] = $this->input->post('updated', TRUE);

                if ($this->input->post('updateby'))
                    $filters['updateby'] = $this->input->post('updateby', TRUE);
                
                if ($this->input->post('deleted'))
                   $filters['deleted'] = $this->input->post('deleted', TRUE);

                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}&tab={$tab}");
            }
        }

        // get list
        $customers = $this->customers_model->get_all($limit, $offset, $filters, $sort, $dir,$filters_tabs);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}&tab={$tab}",
            'total_rows' => $customers['total'],
            'per_page'   => $limit
        ));

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'    => lang('customers title user_list'),
            'js_files_i18n' => array(
                $this->jsi18n->translate("/application/modules/customers/assets/js/customers_admin_i18n.js")
            )
        ));
        $data = $this->header_data;
        
        // Tabs
        $data['tabs'] = array(
            'tab1' => array(
                'url'                    => THIS_URL . '/'."?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
                'text'                   => lang('customers tab1'),
                'active'                 => $tab==''?'active':''
            ),
            'tab2' => array(
                'url'                    => THIS_URL . '/?tab=2'."&sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
                'text'                   => lang('customers tab2'),
                'active'                 => $tab==2?'active':''
            ),
            'tab3' => array(
                'url'                    => THIS_URL . '/?tab=3'."&sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
                'text'                   => lang('customers tab3'),
                'active'                 => $tab==3?'active':''
            ),
            'tab4' => array(
                'url'                    => THIS_URL . '/?tab=4'."&sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
                'text'                   => lang('customers tab4'),
                'active'                 => $tab==4?'active':''
            ),
            'tab5' => array(
                'url'                    => THIS_URL . '/?tab=5'."&sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
                'text'                   => lang('customers tab5'),
                'active'                 => $tab==5?'active':''
            ),
            'tab6' => array(
                'url'                    => THIS_URL . '/?tab=6'."&sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
                'text'                   => lang('customers tab6'),
                'active'                 => $tab==6?'active':''
            )
        );
        
        // create button to add new customers
        if($this->config->item('master_sitename')!=$this->config->item('sitename')){
            $data['controls'] = array(
                'setting_column' => array(
                    'bootstrap_button_class' => 'btn-warning',
                    'bootstrap_icon_class'   => 'glyphicon-pencil',
                    'url'                    => THIS_URL . '/column',
                    'text'                   => lang('customers button setting_column'),
                    'tooltip'                => lang('customers tooltip setting_column'),
                    'data-target'            => '#myModalColumn',
                    'data-toggle'            => 'modal'
                ),
                'add_new' => array(
                    'bootstrap_button_class' => 'btn-primary',
                    'bootstrap_icon_class'   => 'glyphicon-plus-sign',
                    'url'                    => THIS_URL . '/add',
                    'text'                   => lang('customers button add_new_user'),
                    'tooltip'                => lang('customers tooltip add_new_user'),
                    'data-target'            => '#myModal',
                    'data-toggle'            => 'modal'
                )
            );
        }else{
            $data['controls'] = array(
                'setting_column' => array(
                    'bootstrap_button_class' => 'btn-warning',
                    'bootstrap_icon_class'   => 'glyphicon-pencil',
                    'url'                    => THIS_URL . '/column',
                    'text'                   => lang('customers button setting_column'),
                    'tooltip'                => lang('customers tooltip setting_column'),
                    'data-target'            => '#myModalColumn',
                    'data-toggle'            => 'modal'
                )
            );
        }
        
        // get the data column
        $columns = $this->customers_model->get_column();
        $filters_columns = array();
        foreach ($columns['results'] as $column){
            $filters_columns[$column['name']] = $column['status'];
        }
        

                   
        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'customers'      => $customers['results'],
            'total'      => $customers['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir,
            'tab'        => $tab,
            'column'      => $filters_columns
        );

        // load views
        $data['content'] = $this->load->view('admin/customers_list', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }


    /**
     * Add new user
     */
    public function add()
    {
        
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('name', lang('customers input name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', lang('customers input email'), 'required|trim|xss_clean|valid_email');
        

        if ($this->form_validation->run($this) == TRUE)
        {
            // save the new user
            $saved = $this->customers_model->add_customer($this->input->post());

            if ($saved)
                $this->session->set_flashdata('message', sprintf(lang('customers msg add_user_success'), $this->input->post('name')));
            else
                $this->session->set_flashdata('error', sprintf(lang('customers error add_user_failed'), $this->input->post('name')));

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'  => lang('users title user_add')
        ));
        $data = $this->header_data;

        // set content data
         $now = new DateTime();
        $date_new = $now->format('m-d-Y');
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => NULL,
            'title'              => lang("customers title add_new"),
            'password_required' => TRUE,
            'list_status' => $this->session->userdata("list_status"),
            'date_new' => $date_new
        );
        // load views
        $data['content'] = $this->load->view('admin/customers_form', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('modal_template', $data);
    }


    /**
     * Edit existing customer
     *
     * @param int $id
     */
    public function edit($id=NULL)
    {
        // make sure we have a numeric id
        if (is_null($id) || ! is_numeric($id))
            redirect($this->_redirect_url);

        // get the data
        $user = $this->customers_model->get_customer($id);
        
        // get the data
        if($this->session->userdata("list_status")==1 || ($this->config->item('master_sitename')==$this->config->item('sitename'))){
            $task_list = $this->customers_model->get_task_list($id);
            $list_status = 1;
        }else{
            $task_list['results'] = NULL;
            $list_status = 0;
        }
        // if empty results, return to list
        if ( ! $user)
            redirect($this->_redirect_url);

        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('name', lang('customers input name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', lang('customers input email'), 'required|trim|xss_clean|valid_email');

        if ($this->form_validation->run($this) == TRUE)
        {
            // save the changes
            $saved = $this->customers_model->edit_customer($this->input->post());

            if ($saved)
                $this->session->set_flashdata('message', sprintf(lang('customers msg edit_user_success'), $this->input->post('name')));
            else
                $this->session->set_flashdata('error', sprintf(lang('customers error edit_user_failed'), $this->input->post('name')));

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'  => lang('customers title user_edit')
        ));
        $data = $this->header_data;

        // set content data
        $now = new DateTime();
        $date_new = $now->format('m-d-Y');
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => $user,
            'task_list'         => $task_list['results'],
            'customer_id'           => $id,
            'title'              => sprintf(lang("customer edit"),$id),
            'list_status' => $list_status,
            'date_new' => $date_new
        );
        // load views
        $data['content'] = $this->load->view('admin/customers_form', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }

    /**
     * Edit existing customer
     *
     * @param int $id
     */
    public function column()
    {
        // get the data
        $columns = $this->customers_model->get_column();

        if ($this->input->post())
        {
            // save the changes
            $saved = $this->customers_model->edit_column($this->input->post());

            if ($saved)
                $this->session->set_flashdata('message', lang('customers msg edit_column_success'));
            else
                $this->session->set_flashdata('error', lang('customers error edit_column_failed'));

            // return to list and display message
            redirect($this->_redirect_url);
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'  => lang('customers title column_edit')
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'title'             => lang("customers title column_edit"),
            'columns'      => $columns['results'],
            'total'      => $columns['total']
        );
        // load views
        $data['content'] = $this->load->view('admin/customers_column', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }
    
    /**
     * Delete a customer
     *
     * @param int $id
     */
    public function delete($id=NULL)
    {
        // make sure we have a numeric id
        if ( ! is_null($id) || ! is_numeric($id))
        {
            // get user details
            $user = $this->customers_model->get_customer($id);

            if ($user)
            {
                if($this->config->item('master_sitename')==$this->config->item('sitename')){
                    $delete = $this->customers_model->delete_master_customer($id);
                }else{
                    // soft-delete the user
                    $delete = $this->customers_model->delete_customer($id);
                }
                if ($delete)
                    $this->session->set_flashdata('message', sprintf(lang('customers msg delete_user'), $user['name']));
                else
                    $this->session->set_flashdata('error', sprintf(lang('customers error delete_user'), $user['name']));
            }
            else
            {
                $this->session->set_flashdata('error', lang('customers error user_not_exist'));
            }
        }
        else
        {
            $this->session->set_flashdata('error', lang('customers error user_id_required'));
        }

        // return to list and display message
        redirect($this->_redirect_url);
    }


    /**
     * Export list to CSV
     */
    function export()
    {
        header('Content-Type: text/html; charset=utf-8');
        // get parameters
        $sort = $this->input->get('sort') ? $this->input->get('sort', TRUE) : DEFAULT_SORT;
        $dir  = $this->input->get('dir')  ? $this->input->get('dir', TRUE)  : DEFAULT_DIR;
        $tab    = $this->input->get('tab')    ? $this->input->get('tab', TRUE)    : DEFAULT_TAB;
        
        // get filters
        $filters = array();
        
        if ($this->input->get('sitename'))
            $filters['sitename'] = $this->input->get('sitename', TRUE);
        
        if ($this->input->get('name'))
            $filters['name'] = $this->input->get('name', TRUE);

        if ($this->input->get('email'))
            $filters['email'] = $this->input->get('email', TRUE);
        
        if ($this->input->get('comment'))
            $filters['comment'] = $this->input->get('comment', TRUE);
        
        if ($this->input->get('comment_privat'))
            $filters['comment_privat'] = $this->input->get('comment_privat', TRUE);
        
        if ($this->input->get('approveby'))
            $filters['approveby'] = $this->input->get('approveby', TRUE);
        
        if ($this->input->get('updated'))
            $filters['updated'] = $this->input->get('updated', TRUE);
        
        if ($this->input->get('updateby'))
            $filters['updateby'] = $this->input->get('updateby', TRUE);
        
        if ($this->input->get('deleted'))
            $filters['deleted'] = $this->input->get('deleted', TRUE);

        // get filters by tab
        $filters_tabs = array();
        if($tab==2){
            $filters_tabs['data_entry_monday'] = '1';
            $filters_tabs['posting_monday'] = '1';
        }else if($tab==3){
            $filters_tabs['data_entry_tuesday'] = '1';
            $filters_tabs['posting_tuesday'] = '1';
        }else if($tab==4){
            $filters_tabs['data_entry_wednesday'] = '1';
            $filters_tabs['posting_wednesday'] = '1';
        }else if($tab==5){
            $filters_tabs['data_entry_thursday'] = '1';
            $filters_tabs['posting_thursday'] = '1';
        }else if($tab==6){
            $filters_tabs['data_entry_friday'] = '1';
            $filters_tabs['posting_friday'] = '1';
        }
        
        // get all customers
        $users = $this->customers_model->get_all(0, 0, $filters, $sort, $dir,$filters_tabs);

        if ($users['total'] > 0)
        {
            
            // manipulate the output array
            foreach ($users['results'] as $key=>$user)
            {
                if($this->config->item('master_sitename')==$this->config->item('sitename')){
                    $users['results'][$key][lang('export col sitename')] =  $user['sitename'];
                }
                $users['results'][$key][lang('export col name')] = $user['name'];
                $users['results'][$key][lang('export col email')] = $user['email'];
                if($this->config->item('master_sitename')==$this->config->item('sitename')){
                    $users['results'][$key][lang('export col comment_privat')] =  $user['comment_privat'];
                }
                $users['results'][$key][lang('export col comment')] =  $user['comment'];
                $users['results'][$key][lang('export col data_entry')] = lang('export data_entry_monday'.$user['data_entry_monday']).lang('export data_entry_tuesday'.$user['data_entry_tuesday']).lang('export data_entry_wednesday'.$user['data_entry_wednesday']).lang('export data_entry_thursday'.$user['data_entry_thursday']).lang('export data_entry_friday'.$user['data_entry_friday']);
                $users['results'][$key][lang('export col posting')] = lang('export posting_monday'.$user['posting_monday']).lang('export posting_tuesday'.$user['posting_tuesday']).lang('export posting_wednesday'.$user['posting_wednesday']).lang('export posting_thursday'.$user['posting_thursday']).lang('export posting_friday'.$user['posting_friday']);
                $users['results'][$key][lang('export col approveby')] =  $user['approveby'];
                $users['results'][$key][lang('export col priority')] = ($user['priority'] > 0?lang('customers select priority yes'):lang('customers select priority no'));
                $users['results'][$key][lang('export col updated')] =  $user['updated'];
                $users['results'][$key][lang('export col updateby')] =  $user['username'];
                if($this->config->item('master_sitename')==$this->config->item('sitename')){
                    if ($user['deleted'] == 0){
                        $users['results'][$key]['Status'] = lang('admin input active');
                    }else{
                        $users['results'][$key]['Status'] = lang('admin input inactive');
                    }
                }
                if ($user['on_hold'] == 0){
                    $users['results'][$key][lang('export col on_hold')] = '';
                }else{
                    $users['results'][$key][lang('export col on_hold')] = lang('export col on_hold');
                }
                //Fix column in array
                unset($users['results'][$key]['name']);
                unset($users['results'][$key]['email']);
                unset($users['results'][$key]['priority']);
                unset($users['results'][$key]['comment']);
                unset($users['results'][$key]['comment_privat']);
                unset($users['results'][$key]['approveby']);
                unset($users['results'][$key]['deleted']);
                unset($users['results'][$key]['created']);
                unset($users['results'][$key]['updated']);
                unset($users['results'][$key]['updateby']);
                unset($users['results'][$key]['siteid']);
                unset($users['results'][$key]['data_entry_id']);
                unset($users['results'][$key]['customer_id']);
                unset($users['results'][$key]['data_entry_monday']);
                unset($users['results'][$key]['data_entry_tuesday']);
                unset($users['results'][$key]['data_entry_wednesday']);
                unset($users['results'][$key]['data_entry_thursday']);
                unset($users['results'][$key]['data_entry_friday']);
                unset($users['results'][$key]['posting_id']);
                unset($users['results'][$key]['posting_monday']);
                unset($users['results'][$key]['posting_tuesday']);
                unset($users['results'][$key]['posting_wednesday']);
                unset($users['results'][$key]['posting_thursday']);
                unset($users['results'][$key]['posting_friday']);
                unset($users['results'][$key]['username']);
                unset($users['results'][$key]['sitename']);
                unset($users['results'][$key]['site_id']);
                unset($users['results'][$key]['on_hold']);
            }

            // export the file
            array_to_csv($users['results'], "customers");
        }
        else
        {
            // nothing to export
            $this->session->set_flashdata('error', lang('core error no_results'));
            redirect($this->_redirect_url);
        }

        exit;
    }
    
    public function update_customers_column()
    {
        $column_name = $this->uri->segment(4);
        if($column_name){
        // save the changes
            $saved = $this->customers_model->update_column($column_name);

            if ($saved)
                $this->session->set_flashdata('message', lang('customers msg edit_column_success'));
            else
                $this->session->set_flashdata('error', lang('customers error edit_column_failed'));

            // return to list and display message
            redirect($this->_redirect_url);
        }
        exit();
    }
    
    public function update_customers_table()
    {
        // save the changes
            $saved = $this->customers_model->update_table();

            if ($saved)
                $this->session->set_flashdata('message', lang('customers msg edit_column_success'));
            else
                $this->session->set_flashdata('error', lang('customers error edit_column_failed'));

            // return to list and display message
            redirect($this->_redirect_url);
        exit();
    }
    
    /**
     * Customer task list
     */
    public function customers_task_list($id=NULL)
    {
       
        // make sure we have a numeric id
        if (is_null($id) || ! is_numeric($id))
            redirect($this->_redirect_url);
        
        if($this->session->userdata("list_status")==0 && ($this->config->item('master_sitename')!=$this->config->item('sitename')))
             redirect($this->_redirect_url);
        
        if ($this->input->get('date'))
            $date_action = $this->input->get('date', TRUE);
       
        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'    => lang('customers title task_list'),
            'js_files_i18n' => array(
                $this->jsi18n->translate("/application/modules/customers/assets/js/customers_admin_i18n.js")
            )
        ));
        $data = $this->header_data;

        
        // get the data holiday
        $DateString = "%Y-%m-%d";
        $MonthDayString = "%m-%d";
        $MonthString = "%m";
        $YearString = "%Y";
        
        $custom_date = strtotime( mdate($DateString,time()) ); 
        $day_now =  mdate('%N', $custom_date);
       
      
        if($day_now==6){
            $custom_date = strtotime('+2 day', time());
        }
        if($day_now==7){
            $custom_date = strtotime('+1 day', time());
        }
        $time = isset($date_action) ? $date_action : $custom_date;
        
        $week =  mdate('%W', $time);

        if(($week%2)== 0){
            $week1_monday = mdate($DateString, strtotime('last week monday', $time));
            $week1_tuesday = mdate($DateString, strtotime('last week tuesday', $time));
            $week1_wednesday = mdate($DateString, strtotime('last week wednesday', $time));
            $week1_thursday = mdate($DateString, strtotime('last week thursday', $time));
            $week1_friday = mdate($DateString, strtotime('last week friday', $time));
    //        $week1_saturday = mdate($DateString, strtotime('this week saturday', $time));
    //        $week1_sunday = mdate($DateString, strtotime('this week sunday', $time));

            $week2_monday = mdate($DateString, strtotime('this week monday', $time));
            $week2_tuesday = mdate($DateString, strtotime('this week tuesday', $time));
            $week2_wednesday = mdate($DateString, strtotime('this week wednesday', $time));
            $week2_thursday = mdate($DateString, strtotime('this week thursday', $time));
            $week2_friday = mdate($DateString, strtotime('this week friday', $time));
    //        $week2_saturday = mdate($DateString, strtotime('next week saturday', $time));
    //        $week2_sunday = mdate($DateString, strtotime('next week sunday', $time));
        }
        else{
            $week1_monday = mdate($DateString, strtotime('this week monday', $time));
            $week1_tuesday = mdate($DateString, strtotime('this week tuesday', $time));
            $week1_wednesday = mdate($DateString, strtotime('this week wednesday', $time));
            $week1_thursday = mdate($DateString, strtotime('this week thursday', $time));
            $week1_friday = mdate($DateString, strtotime('this week friday', $time));
    //        $week1_saturday = mdate($DateString, strtotime('this week saturday', $time));
    //        $week1_sunday = mdate($DateString, strtotime('this week sunday', $time));

            $week2_monday = mdate($DateString, strtotime('next week monday', $time));
            $week2_tuesday = mdate($DateString, strtotime('next week tuesday', $time));
            $week2_wednesday = mdate($DateString, strtotime('next week wednesday', $time));
            $week2_thursday = mdate($DateString, strtotime('next week thursday', $time));
            $week2_friday = mdate($DateString, strtotime('next week friday', $time));
    //        $week2_saturday = mdate($DateString, strtotime('next week saturday', $time));
    //        $week2_sunday = mdate($DateString, strtotime('next week sunday', $time));
        }
      
        
        $back_week = THIS_URL . '/customers_task_list/'.$id."?date=".strtotime('-2 week monday', strtotime($week1_monday));
        $nex_week = THIS_URL . '/customers_task_list/'.$id."?date=".strtotime('next week monday', strtotime($week2_monday));

        $day_in_week = Array($week1_monday,$week1_tuesday,$week1_wednesday,$week1_thursday,$week1_friday,$week2_monday,$week2_tuesday,$week2_wednesday,$week2_thursday,$week2_friday);

        $holiday = $this->customers_model->get_holiday();

        $tab_date = Array();
        $i = 0 ;
        $set_date_action = "";
        $set_date_action_new = "";
        foreach($day_in_week as $day_in){
            $set_date_time = strtotime($day_in);
            $tempDate = mdate($MonthDayString, $set_date_time);
            $day_of_week_number =  mdate('%N', $set_date_time);
            $day_of_week_string =  mdate('%D', $set_date_time);
            $Date=  mdate("%d-%M", $set_date_time);
            if($day_of_week_number!=6 && $day_of_week_number!=7){
                if (!in_array($tempDate, $holiday['results'])){
                    $tab_date[$i]['DayOfWeek'] = lang($day_of_week_string);
                    $tab_date[$i]['Date'] = $Date;
                    if($set_date_time==$time){
                        $class="btn-success";
                        $set_date_action = $set_date_time;
                    }else{
                        $class="normal";
                        if($i==0){
                            $set_date_action_new = $set_date_time;
                        }
                    }
                    $tab_date[$i]['DateFocus'] = $set_date_time;
                    $tab_date[$i]['DateActive'] = $class;
                    $tab_date[$i]['DateAction'] = THIS_URL . '/customers_task_list/'.$id."?date={$set_date_time}";
                }else{
                    $tab_date[$i]['DayOfWeek'] = $day_of_week_string;
                    $tab_date[$i]['Date'] = $Date;
                    $tab_date[$i]['DateFocus'] = $set_date_time;
                    $tab_date[$i]['DateActive'] = "btn-warning";
                    $tab_date[$i]['DateAction'] = "#";
                }
            }
            $i++;
        }
             
        if($set_date_action==""){
            foreach ($tab_date as $key => $tab){
                 if($tab['DateAction']!="#" && ($tab['DateFocus'] >= $time)){
                   $tab_date[$key]['DateActive'] = "btn-success";  
                   $this->customers_task_list_by_date($tab_date[$key]['DateFocus'],$holiday,$id);
                   $task_checked = $this->customers_model->get_checked_ByDate(mdate($DateString, $tab_date[$key]['DateFocus']),$id);
                   $date_last_week = $tab_date[$key]['DateFocus'];
                   break;
                 }
            }
        }else{
            $this->customers_task_list_by_date($set_date_action,$holiday,$id);
            $task_checked = $this->customers_model->get_checked_ByDate(mdate($DateString, $set_date_action),$id);
            $date_last_week = $set_date_action;
        }

        $customer_id_group = Array();
        if(!empty($task_checked)){
            foreach($task_checked as $kay => $value){
                $customer_id_group[$value["customer_id"]][$kay]=NULL;
                foreach($customer_id_group as $kay2 => $value2){
                    if($kay2==$value["customer_id"]){
                        $customer_id_group[$kay2][$kay] = $task_checked[$kay];
                    }
                }
            }
        }
        $task_checked_old_data = Array();

        for($j=1;$j<=14;$j++){
            $task_old = $this->customers_model->get_checked_ByDate(mdate($DateString, strtotime('-'.$j.' day',$date_last_week)),$id);
            if(!empty($task_old)){
                array_push($task_checked_old_data,$task_old);
            }
        }

        $customer_id_group_old = Array();
        if(!empty($task_checked_old_data)){
            foreach($task_checked_old_data as $k => $task_checked_old){
                foreach($task_checked_old as $kay => $value){
                    $customer_id_group_old[$value["customer_id"]][$kay]=NULL;
                    foreach($customer_id_group_old as $kay2 => $value2){
                        if($kay2==$value["customer_id"]){
                            $customer_id_group_old[$k][$kay2][$kay] = $task_checked_old[$kay];
                        }
                    }
                }
            }
        }
            
        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'back_week'        => $back_week,
            'nex_week'        => $nex_week,
            'tab_date'        => $tab_date,
            'data'        => $customer_id_group,
            'data_old'        => $customer_id_group_old,
            'site_id'       => $id
        );
        
        // load views
        $data['content'] = $this->load->view('admin/customers_task_list', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }
    
    public function customers_task_list_by_date($date_focus,$holiday,$site_id)
    {
        $DateString = "%Y-%m-%d";
        $MonthDayString = "%m-%d";
        $MonthString = "%m";
        $YearString = "%Y";
        $DayString = "%d";
        $n = 30;
        $date_today = mdate($DateString, strtotime('today',$date_focus));
        $Day = mdate($DayString, strtotime('today', $date_focus));
        $Month= mdate($MonthString, strtotime('today', $date_focus));
        $date_today_add[0]['day'] = $Day;
        $date_today_add[0]['month'] = $Month;
        $date_today_add[0]['date'] = $date_today;
        $Month_today = mdate($MonthString, strtotime('today',$date_focus));
        $date_next = Array();
        for($i=1;$i<=$n;$i++){
            $next_day = mdate($MonthDayString, strtotime('+'.$i.' day', $date_focus));
            $Day = mdate($DayString, strtotime('+'.$i.' day', $date_focus));
            $Month= mdate($MonthString, strtotime('+'.$i.' day', $date_focus));
            $Date = mdate($DateString, strtotime('+'.$i.' day', $date_focus));
            $day_of_week_number =  mdate('%N', strtotime('+'.$i.' day', $date_focus));
            if (!in_array($next_day, $holiday['results']) && ($day_of_week_number!=6 && $day_of_week_number!=7)){
                break;
            }else{
                $date_next[$i]['day'] = $Day;
                $date_next[$i]['month'] = $Month;
                $date_next[$i]['date'] = $Date;
            }
        }
        $this->customers_task_checked($date_today_add,$date_today,$Month_today,$site_id);
        if(!empty($date_next)){
            $this->customers_task_checked($date_next,$date_today,$Month_today,$site_id);
        }
    }
    
    public function customers_task_checked($data,$date_today,$Month_today,$site_id)
    {
        $DateString = "%Y-%m-%d";
        $MonthDayString = "%m-%d";
        $MonthString = "%m";
        $YearString = "%Y";
        $DayString = "%d";
        foreach ($data as $item){
              $task_list = $this->customers_model->get_task_list_by_day($item['day'],$site_id);
              if(!empty($task_list)){
                  foreach($task_list as $task){
                        $Day = mdate($DayString, strtotime($task["start_date"]));
                        $Month= mdate($MonthString, strtotime($task["start_date"]));
                        if($task["task_repeat"]==1 && $task["task_list_type"]==1 ){
                            $task_list_checked = $this->customers_model->get_task_list_checked_by_DayMonth($Day,$item['month'],$task['task_list_id']);
                            if(!empty($task_list_checked)){
                                foreach ($task_list_checked as $checked){
                                    $this->customers_model->update_task_list_checked($checked['task_checked_id'],$checked['task_list_id'],$date_today);
                                }
                            }else{
                                $task_list_checked = $this->customers_model->check_task_list_checked($task['task_list_id'],$date_today);
                                if(empty($task_list_checked)){
                                    $this->customers_model->add_task_list_checked($task['task_list_id'],$date_today);
                                }
                            }
                        }else if($task["task_repeat"]==2 && $task["task_list_type"]==1){
                            for($j=0;$j<=12;){
                                //echo $j.'<br>';
                                $Month_for_repeat = mdate($MonthString, strtotime("+".$j." month",strtotime($task["start_date"])));
                                  //echo $Month_today.'==='.$Month_for_repeat.'<br>';
                                if($Month_today==$Month_for_repeat){
                                     //echo $Day.'==='.$Month.'<br>';
                                    $task_list_checked = $this->customers_model->get_task_list_checked_by_DayMonth($Day,$item['month'],$task['task_list_id']);
                                    if(!empty($task_list_checked)){
                                        foreach ($task_list_checked as $checked){
                                            $this->customers_model->update_task_list_checked($checked['task_checked_id'],$checked['task_list_id'],$date_today);
                                        }
                                    }else{
                                        $task_list_checked = $this->customers_model->check_task_list_checked($task['task_list_id'],$date_today);
                                        if(empty($task_list_checked)){
                                            $this->customers_model->add_task_list_checked($task['task_list_id'],$date_today);
                                        }
                                    }
                                }
                                $j += 2;
                            }
                        }
                  }
              }

            $day_now =  mdate('%N', strtotime($item['date'])); 
            $day_of_week =  mdate('%N', strtotime($date_today)); 
            $data_entry = Array(1=>11,2=>12,3=>13,4=>14,5=>15);
            $data_posting = Array(1=>16,2=>17,3=>18,4=>19,5=>20);
    
            if($day_now==$day_of_week){
                if($day_now!=6&&$day_now!=7){
                    $data_entry_list = $this->customers_model->get_task_list_by_task_id($data_entry[$day_now],$site_id);  
                    $data_posting_list = $this->customers_model->get_task_list_by_task_id($data_posting[$day_now],$site_id); 
                    if(!empty($data_entry_list)){
                          foreach($data_entry_list as $task){
                                $Day = mdate($DayString, strtotime($task["start_date"]));
                                $Month= mdate($MonthString, strtotime($task["start_date"]));
                                if($task["task_list_type"]==0){
                                    $task_list_checked = $this->customers_model->get_task_list_checked_by_DayMonth($Day,$item['month'],$task['task_list_id']);
                                    if(!empty($task_list_checked)){
                                        foreach ($task_list_checked as $checked){
                                            $this->customers_model->update_task_list_checked($checked['task_checked_id'],$checked['task_list_id'],$date_today);
                                        }
                                    }else{
                                        $task_list_checked = $this->customers_model->check_task_list_checked($task['task_list_id'],$date_today);
                                        if(empty($task_list_checked)){
                                            $this->customers_model->add_task_list_checked($task['task_list_id'],$date_today);
                                        }
                                    }
                                }
                          }
                    }
                    if(!empty($data_posting_list)){
                          foreach($data_posting_list as $task){
                                $Day = mdate($DayString, strtotime($task["start_date"]));
                                $Month= mdate($MonthString, strtotime($task["start_date"]));
                                if($task["task_list_type"]==0 ){
                                    $task_list_checked = $this->customers_model->get_task_list_checked_by_DayMonth($Day,$item['month'],$task['task_list_id']);
                                    if(!empty($task_list_checked)){
                                        foreach ($task_list_checked as $checked){
                                            $this->customers_model->update_task_list_checked($checked['task_checked_id'],$checked['task_list_id'],$date_today);
                                        }
                                    }else{
                                        $task_list_checked = $this->customers_model->check_task_list_checked($task['task_list_id'],$date_today);
                                        if(empty($task_list_checked)){
                                            $this->customers_model->add_task_list_checked($task['task_list_id'],$date_today);
                                        }
                                    }
                                }
                          }
                    }
                }
            }
        }
    }
    
    public function customers_update_checked()
    {
        if($this->session->userdata("list_status")==0 && ($this->config->item('master_sitename')!=$this->config->item('sitename')))
             redirect($this->_redirect_url);
        
        if ($this->input->get('task_list_id'))
            $task_list_id = $this->input->get('task_list_id', TRUE);
        
        
       $saved = $this->customers_model->update_checked($this->input->post());
           exit();
        
    }
    
    public function customers_task_log($id=NULL)
    {
       
        // make sure we have a numeric id
        if (is_null($id) || ! is_numeric($id))
            redirect($this->_redirect_url);
        
        if($this->session->userdata("list_status")==0 && ($this->config->item('master_sitename')!=$this->config->item('sitename')))
             redirect($this->_redirect_url);
        
        // get parameters
        $limit  = $this->input->get('limit')  ? $this->input->get('limit', TRUE)  : DEFAULT_LIMIT;
        $offset = $this->input->get('offset') ? $this->input->get('offset', TRUE) : DEFAULT_OFFSET;
        $sort   = $this->input->get('sort')   ? $this->input->get('sort', TRUE)   : "task_log_id";
        $dir    = $this->input->get('dir')    ? $this->input->get('dir', TRUE)    : "DESC";

        // get filters
        $filters = array();
        
        // build filter string
        $filter = "";
        foreach ($filters as $key=>$value)
            $filter .= "&{$key}={$value}";

        // save the current url to session for returning
        $this->session->set_userdata(REFERRER, THIS_URL . "/customers_task_log/{$id}?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");

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

                if ($this->input->post('task_log_id'))
                    $filter .= "&task_log_id=" . $this->input->post('task_log_id', TRUE);
                
                // redirect using new filter(s)
                redirect(THIS_URL . "/customers_task_log/{$id}?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $task_log = $this->customers_model->get_task_log_all($limit, $offset, $filters, $sort, $dir,null,$id);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "/customers_task_log/{$id}?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $task_log['total'],
            'per_page'   => $limit
        ));

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'    => lang('users title task_log'),
            'js_files_i18n' => array(
                $this->jsi18n->translate("/application/modules/customers/assets/js/users_admin_i18n.js")
            )
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'task_log'      => $task_log['results'],
            'total'      => $task_log['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir
        );

        // load views
        $data['content'] = $this->load->view('admin/customers_task_log', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }
}
