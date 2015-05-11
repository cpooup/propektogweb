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
        $this->load->helper('directory');
        // load the language files
        $this->lang->load('holiday');

        // load the holiday model
        $this->load->model('holiday_model');

        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/holiday'));
        define('DEFAULT_LIMIT', 50);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "holiday_name");
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

        if ($this->input->get('holiday_name'))
            $filters['holiday_name'] = $this->input->get('holiday_name', TRUE);
        
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

                if ($this->input->post('holiday_name'))
                    $filter .= "&holiday_name=" . $this->input->post('holiday_name', TRUE);
                
                // redirect using new filter(s)
                redirect(THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}&offset={$offset}{$filter}");
            }
        }

        // get list
        $holidays = $this->holiday_model->get_all($limit, $offset, $filters, $sort, $dir);

        // build pagination
        $this->pagination->initialize(array(
            'base_url'   => THIS_URL . "?sort={$sort}&dir={$dir}&limit={$limit}{$filter}",
            'total_rows' => $holidays['total'],
            'per_page'   => $limit
        ));

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title'    => lang('users title user_list'),
            'js_files_i18n' => array(
                $this->jsi18n->translate("/application/modules/holiday/assets/js/users_admin_i18n.js")
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
            'holiday'      => $holidays['results'],
            'total'      => $holidays['total'],
            'filters'    => $filters,
            'filter'     => $filter,
            'pagination' => $this->pagination->create_links(),
            'limit'      => $limit,
            'offset'     => $offset,
            'sort'       => $sort,
            'dir'        => $dir
        );

        // load views
        $data['content'] = $this->load->view('admin/holiday_list', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }


    /**
     * Add new user
     */
    public function add()
    {
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('holiday_name', lang('admin input sitename'), 'required|trim|xss_clean');
        if ($this->form_validation->run($this) == TRUE)
        {
            // save the new user
            $saved = $this->holiday_model->add_holiday($this->input->post());

            if ($saved){
                $this->session->set_flashdata('message', sprintf(lang('users msg add_user_success'), $this->input->post('holiday_name')));
            }else{
                $this->session->set_flashdata('error', sprintf(lang('users error add_user_failed'), $this->input->post('holiday_name')));
            }
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
            'method'        => 'add',
            'cancel_url'        => $this->_redirect_url,
            'title'              => lang("users button add_new_user")
        );

        // load views
        $data['content'] = $this->load->view('admin/holiday_form', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }

    /**
     * Edit existing holiday logo
     *
     * @param int $id
     */
    public function edit($id=NULL)
    {
        // make sure we have a numeric id
        if (is_null($id) || ! is_numeric($id))
            redirect($this->_redirect_url);

        // get the data
        $holiday = $this->holiday_model->get_holiday($id);
        $id = $holiday['holiday_id'];
        
        // if empty results, return to list
        if ( ! $holiday)
            redirect($this->_redirect_url);

        if($this->input->post()){
                
                 // save the changes
                $saved = $this->holiday_model->edit_holiday($this->input->post());

                if ($saved)
                    $this->session->set_flashdata('message', sprintf(lang('users msg edit_logo_success'), $this->input->post('holiday_name')));
                else
                    $this->session->set_flashdata('error', sprintf(lang('users error edit_user_failed'), $this->input->post('holiday_name')));

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
            'method'        => 'edit',
            'holiday'       => $holiday,
            'holiday_id'           => $id,
            'cancel_url'        => $this->_redirect_url,
            'title'              => lang("users title edit_logo")
        );

        // load views
        $data['content'] = $this->load->view('admin/holiday_form', $content_data, TRUE);
        echo $data['content'];
        exit();
        //$this->load->view('admin_template', $data);
    }
    public function delete($id=NULL)
    {
        // make sure we have a numeric id
        if ( ! is_null($id) || ! is_numeric($id))
        {
            // get user details
            $holiday = $this->holiday_model->get_holiday($id);

            if ($holiday)
            {
                $delete = $this->holiday_model->delete_holiday($id);
                if ($delete)
                    $this->session->set_flashdata('message', sprintf(lang('customers msg delete_user'), $holiday['holiday_name']));
                else
                    $this->session->set_flashdata('error', sprintf(lang('customers error delete_user'), $holiday['holiday_name']));
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
    
}
