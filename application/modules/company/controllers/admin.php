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
        $this->lang->load('company');

        // load the company model
        $this->load->model('company_model');

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/company'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "sitename");
        define('DEFAULT_DIR', "asc");
        
        // use the url in session (if available) to return to the previous filter/sorted/paginated list
        if ($this->session->userdata(REFERRER))
            $this->_redirect_url = $this->session->userdata(REFERRER);
        else
            $this->_redirect_url = THIS_URL;
        
         if($this->config->item('master_sitename')!=$this->config->item('sitename'))
            redirect($this->_redirect_url);
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * Company list page
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

        if ($this->input->get('sitename'))
            $filters['sitename'] = $this->input->get('username', TRUE);
        
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

                if ($this->input->post('sitename'))
                    $filter .= "&sitename=" . $this->input->post('sitename', TRUE);
                
                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $companys = $this->company_model->get_all($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $companys['total'],
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
                'data-target'            => '#myModalCompany',
                'data-toggle'            => 'modal'
            )
        );

        // set content data
        $content_data = array(
            'this_url'   => THIS_URL,
            'company'      => $companys['results'],
            'total'      => $companys['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir
        );

        // load views
        $data['content'] = $this->load->view('admin/company_list', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }


    /**
     * Add new user
     */
    public function add()
    {
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('sitename', lang('admin input sitename'), 'required|trim|xss_clean');
        if ($this->form_validation->run($this) == TRUE)
        {
            // save the new user
            $saved = $this->company_model->add_company($this->input->post());

            if ($saved)
                $this->session->set_flashdata('message', sprintf(lang('users msg add_user_success'), $this->input->post('sitename')));
            else
                $this->session->set_flashdata('error', sprintf(lang('users error add_user_failed'), $this->input->post('sitename')));

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
            'title'              => lang("users button add_new_user"),
            'password_required' => TRUE
        );

        // load views
        $data['content'] = $this->load->view('admin/company_form', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }


    public function company_availability()
    {
        if ($this->input->post('sitename')){
            $sitename = $this->input->post('sitename', TRUE);
            if ($this->company_model->company_exists($sitename))
            {
               echo "false";
            }else{
               echo "true";
            }
        }else{
            echo "false";
        }
    }
    
}
