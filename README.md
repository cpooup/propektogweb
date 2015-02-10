
#IMPORTANT ANNOUNCEMENT

##INTRODUCTION

Here is what's included:

* CodeIgniter 2.2.0
* Modular Extensions by wiredesignz (includes form validation callback fix)
* Single base controller with Public, Private, Admin and API classes
* JSi18n Library to support internationalization in your JS files
* The latest version of jQuery
* Bootstrap 3
* Basic templating with automatic module view overrides
* Includes auto-loaded core config file
* Includes auto-loaded core language file
* Includes auto-loaded core helper file
    + Array to CSV exporting

NOTE: This documentation assumes you are already familiar with PHP and CodeIgniter. If you need to learn more about
CodeIgniter, visit the user guide at http://ellislab.com/codeigniter/user-guide/. To learn more about PHP, visit
http://php.net/.

![Welcome Screen](http://puu.sh/fIxVH/60b1672d88.png)

##MODULAR

wiredesign's Modular Extensions
(https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc). The modules are located in
/application/modules.

4 modules:
* api - a place to build API functions
* users - a basic authentication module for registering and logging in and out
* company - a basic authentication module for registering and logging in and out
* customers - a basic authentication module for registering and logging in and out

##BASE CLASSES

Phil Sturgeon wrote a very helpful blog post years ago called "CodeIgniter Base Classes: Keeping it DRY"
(http://philsturgeon.co.uk/blog/2010/02/CodeIgniter-Base-Classes-Keeping-it-DRY). The methods he described have been
applied to CI Fire Starter. There is a file in /application/core called MY_Controller.php which includes the Public,
Private, Admin and API base classes. This allows you to use shared functionality.

####MY_Controller

This extends the MX (Modular Extensions) controller and defines header data that will get passed to views as well as has
a setting to enable or disable the CI Profiler.

####Understanding Header Data

* site_title    : the title of the website which gets inserted into the document head
* keywords      : meta keywords inserted into the document head
* description   : meta description inserted into the document head
* css_files     : an array of css files to insert into the document head
* js_files      : an array of javascript libraries to insert into the document body
* js_files_i18n : an array of javascript files with internationalization to insert into the document body (see more about these below)

Header data can be appended and/or overwritten from any controller function.

####Public_Controller

This extends MY_Controller and drives all your public pages (home page, etc). Any controller that extends
Public_Controller will be open for the whole world to see.

####Private_Controller

This extends MY_Controller and drives all your private pages (user profile, etc). Any controller that extends
Private_Controller will require authentication. Specific page requests are stored in session and will redirect upon
successful authentication.

####Admin_Controller

This extends MY_Controller and drives all your administration pages. Any controller that extends Admin_Controller will
require authentication from a user with administration privileges. Specific page requests are stored in session and will
redirect upon successful authentication.

####API_Controller

This extends MY_Controller and drives all your API functions. Any controller that extends API_Controller will be open
for the whole world to see (see below for details).



##CORE FILES

####Core Config

In application/config there is a file core.php. This file allows you to set site-wide variables. It is set up with site
name, site version, default templates, pagination settings, enable/disable the profiler and error delimiters.

####Core Language

In application/language/english is a file core_lang.php. This file allows you to set language variables that could be
used throughout the entire site (such as the words Home or Logout).

####Core Helper

In application/helper is a file core_helper.php. This includes the following useful functions:
* display_json($array) - used to output an array as JSON in a human-readable format - used by the API
* json_indent($array) - this is the function that actually creates the human-readable JSON string
* array_to_csv($array, $filename) - exports an array into a CSV file (see admin user list)



##LIBRARIES

####Jsi18n

In application/libraries is a file Jsi18n.php. This clever library allows you to internationalize your JavaScript files
through CI language files and was inspired by Alexandros D on coderwall.com (https://coderwall.com/p/j88iog).

Load this library in the autoload.php file or manually in your controller:

    $this->load->library('jsi18n');

In your language file:

    $lang['alert_message'] = "This is my alert message!";

In your JS files, place your language key inside double braces:

    function myFunction() {
        alert("{{alert_message}}");
    }

Render the Javascript file in your template file:

    <script type="text/javascript"><?php echo $this->jsi18n->translate("/themes/admin/js/my_javascript_i18n.js"); ?></script>

OR in your header data array:

    $this->header_data = array_merge_recursive($this->header_data, array(
        'js_files_i18n' => array(
            $this->jsi18n->translate("/themes/admin/js/my_javascript_i18n.js")
        )
    ));

####MY_Form_validation

In application/libraries is a file My_Form_validation.php. This small library fixes the issue with validation callback
functions not working when using Modular Extensions. This library is automatically loaded, so the only difference is you
have to include $this in your validation:

    if ($this->form_validation->run() == FALSE)

becomes

    if ($this->form_validation->run($this) == FALSE)

You can see this being used in the auth module login controller. For more about this fix, check out Mahbubur Rahman's
blog (http://www.mahbubblog.com/php/form-validation-callbacks-in-hmvc-in-codeigniter/).


##CUSTOMER MANAGEMENT

It does use a database table called
'customers'. This tool demonstrates a lot of basic but important functionality:

* Columns View
* Tabs
* Sortable list columns
* Pagination with customer-changeable items/page
* Exporting lists to CSV
* Form validation
* Harnessing the power of Bootstrap to accelerate development

![User Administration](http://puu.sh/fIxVH/60b1672d88.png)


##USER MANAGEMENT

It does use a database table called
'users'. This tool demonstrates a lot of basic but important functionality:

* Sortable list columns
* Pagination with user-changeable items/page
* Form validation
* Harnessing the power of Bootstrap to accelerate development

![User Administration](http://puu.sh/fIB3n/3ab94e4095.png)


##COMPANY MANAGEMENT

It does use a database table called
'sitename'. This tool demonstrates a lot of basic but important functionality:

* Sortable list columns
* Pagination with user-changeable items/page
* Form validation
* Harnessing the power of Bootstrap to accelerate development

![User Administration](http://puu.sh/fIBud/d842fd5608.png)


##THEMES

####Override Default Themes

In addition to overriding module views from within your theme, you can also override a theme from any controller:

    $this->set_theme('[THEME FOLDER]');



##APIS

With the API class, you can quite easily create API functions for external applications. There is no security on these,
so if you need a more robust solution, such as authentication and API keys, check out Phil Sturgeon's CI Rest Server
(https://github.com/philsturgeon/codeigniter-restserver).


##SYSTEM REQUIREMENTS

* PHP version 5.1.6 or newer
* A database: MySQL (4.1+), MySQLi, MS SQL, Postgres, Oracle, SQLite, and ODBC
* PHP GD extension for CAPTCHA to work
* PHP Mcrypt extension if you want to use the Encryption class



##INSTALLATION

This is really simple:

* Create a new database and import the included sql file
    + default mastersite username/password is admin/admin
* Modify the application/config/config.php
    + line 227: set your encryption key
    + line 378: set your mastersite domain name
* Modify application/config/database.php and connect to your database
* Upload all files to your server
* Make sure you log in to admin and change the administrator password!

