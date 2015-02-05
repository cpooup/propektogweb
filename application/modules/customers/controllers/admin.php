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
        $this->lang->load('customers');

        // load the customers model
        $this->load->model('customers_model');

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/customers'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
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
        
        if ($this->input->get('sitename'))
            $filters['sitename'] = $this->input->get('sitename', TRUE);
        
        if ($this->input->get('name'))
            $filters['name'] = $this->input->get('name', TRUE);

        if ($this->input->get('email'))
            $filters['email'] = $this->input->get('email', TRUE);

        
        if ($this->input->get('comment'))
            $filters['comment'] = $this->input->get('comment', TRUE);
        
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
                if ($this->input->post('sitename'))
                    $filters['sitename'] = $this->input->post('sitename', TRUE);
                
                if ($this->input->post('name'))
                    $filters['name'] = $this->input->post('name', TRUE);

                if ($this->input->post('email'))
                    $filters['email'] = $this->input->post('email', TRUE);

                if ($this->input->post('comment'))
                    $filters['comment'] = $this->input->post('comment', TRUE);

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
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}&tab{$tab}",
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
                'url'                    => THIS_URL . '/',
                'text'                   => lang('customers tab1'),
                'active'                 => $tab==''?'active':''
            ),
            'tab2' => array(
                'url'                    => THIS_URL . '/?tab=2',
                'text'                   => lang('customers tab2'),
                'active'                 => $tab==2?'active':''
            ),
            'tab3' => array(
                'url'                    => THIS_URL . '/?tab=3',
                'text'                   => lang('customers tab3'),
                'active'                 => $tab==3?'active':''
            ),
            'tab4' => array(
                'url'                    => THIS_URL . '/?tab=4',
                'text'                   => lang('customers tab4'),
                'active'                 => $tab==4?'active':''
            ),
            'tab5' => array(
                'url'                    => THIS_URL . '/?tab=5',
                'text'                   => lang('customers tab5'),
                'active'                 => $tab==5?'active':''
            ),
            'tab6' => array(
                'url'                    => THIS_URL . '/?tab=6',
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
            'tab'        => $tab
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
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => NULL,
            'title'              => lang("customers title add_new"),
            'password_required' => TRUE
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
        $content_data = array(
            'cancel_url'        => $this->_redirect_url,
            'user'              => $user,
            'customer_id'           => $id,
            'title'              => sprintf(lang("customer edit"),$id)
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
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));

        if ($this->form_validation->run($this) == TRUE)
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
            'title'              => lang("customers title column_edit")
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
                    $users['results'][$key][lang('export col comment')] =  $user['comment'];
                }
                $users['results'][$key][lang('export col data_entry')] = lang('export data_entry_monday'.$user['data_entry_monday']).lang('export data_entry_tuesday'.$user['data_entry_tuesday']).lang('export data_entry_wednesday'.$user['data_entry_wednesday']).lang('export data_entry_thursday'.$user['data_entry_thursday']).lang('export data_entry_friday'.$user['data_entry_friday']);
                $users['results'][$key][lang('export col posting')] = lang('export posting_monday'.$user['posting_monday']).lang('export posting_tuesday'.$user['posting_tuesday']).lang('export posting_wednesday'.$user['posting_wednesday']).lang('export posting_thursday'.$user['posting_thursday']).lang('export posting_friday'.$user['posting_friday']);
                $users['results'][$key][lang('export col approveby')] =  $user['approveby'];
                $users['results'][$key][lang('export col alternativ')] = ($user['choice']?lang('customers select alternativ'.$user['choice']):'');
                $users['results'][$key][lang('export col priority')] = ($user['priority'] > 0?lang('customers select priority yes'):lang('customers select priority no'));
                $users['results'][$key][lang('export col updated')] =  $user['updated'];
                $users['results'][$key][lang('export col updateby')] =  $user['username'];
                if ($user['deleted'] == 0){
                    $users['results'][$key]['Status'] = lang('admin input inactive');
                }else{
                    $users['results'][$key]['Status'] = lang('admin input active');
                }
                
                //Fix column in array
                unset($users['results'][$key]['name']);
                unset($users['results'][$key]['email']);
                unset($users['results'][$key]['choice']);
                unset($users['results'][$key]['priority']);
                unset($users['results'][$key]['comment']);
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
            }
//            echo '<pre>';
//            print_r($users['results']);
//            echo '</pre>';
//            exit();
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
}
