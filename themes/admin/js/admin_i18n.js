/**
 * Configurations
 */
var config = {
    logging : true,
    baseURL : location.protocol + "//" + location.hostname + "/admin/"
};


/**
 * Let's get started
 */
$(document).ready(function() {
    
    window.app = new BBApp();
    
    /**
     * Enable tooltips
     */
    $('.tooltips').tooltip();


    /**
     * Detect items per page change on all list pages and send users back to page 1 of the list
     */
    $('select#limit').change(function() {
        var limit = $(this).val();
        var currentUrl = document.URL.split('?');
        var uriParams = "";
        var separator;

        if (currentUrl[1] != undefined) {
            var parts = currentUrl[1].split('&');

            for (var i = 0; i < parts.length; i++) {
                if (i == 0) {
                    separator = "?";
                } else {
                    separator = "&";
                }

                var param = parts[i].split('=');

                if (param[0] == 'limit') {
                    uriParams += separator + param[0] + "=" + limit;
                } else if (param[0] == 'offset') {
                    uriParams += separator + param[0] + "=0";
                } else {
                    uriParams += separator + param[0] + "=" + param[1];
                }
            }
        } else {
            uriParams = "?limit=" + limit;
        }

        // reload page
        window.location.href = currentUrl[0] + uriParams;
    });


    /**
     * Enable TinyMCE WYSIWYG editor on any textareas with the 'editor' class
     */
    tinymce.init({
        selector: "textarea.editor",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons paste textcolor"
        ],
        toolbar1: "styleselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | hr link image media emoticons | cut copy paste pastetext | print preview | code",
        image_advtab: true
    });


    /**
     * Apply form-control class and id to timezones dropdown on settings form
     */
    $('select[name=timezones]').addClass('form-control').attr('id', "timezones");

    $('input[name=task_checked_id]').click(function() {
        var task_checked_status = $(this).val();
        var token = $("#csrf-token").val();
        var checked = $(this).is(':checked');
        var task_name = $(this).data('task_name');
        var customer_id = $(this).data('customer_id');
        var site_id = $("#site_id").val();
        if(checked){
            data_check = 1;
        }else{
            data_check = 0;
        }
        
        var tr = $(this).parent().parent();
        var td = tr.find('input[name=task_checked_id]');
        var n = 0;
        $.each( td, function( key, value ) {
            var checked = $(this).is(':checked');
            if(checked){
                n++
            }
        });
         var zone = tr.data('zone');
            var color = tr.data('color');
            var datenow = tr.data('datenow');
            var date = tr.data('date');
            
        $.post( config.baseURL+"customers/customers_update_checked",{task_date:date,site_id:site_id,task_log_name:task_name,customer_id:customer_id,task_checked_id:task_checked_status,task_checked_status:data_check,csrf_token:token}, function( data ) {
            
        });    
        if(data_check==1){
            if(td.length==n){
                if(tr.hasClass( "now" )){
                   tr.removeClass('normal_zone danger').addClass('success green_zone'); 
                   tr.detach().insertBefore('.task_list .task_date tbody .green_zone_group') ;
                }
                if(tr.hasClass( "old" )){
                    tr.removeClass('danger red_zone').addClass('success green_zone'); 
                    tr.detach().insertBefore('.task_list .task_date tbody .green_zone_group') ;
                }
            }
        }else{
            if(tr.hasClass( "now" )){
                if(!tr.hasClass( "normal_zone" )){
                    if(datenow>date){
                        tr.removeClass('success green_zone').addClass('danger red_zone'); 
                        tr.detach().insertBefore('.task_list .task_date tbody .red_zone_group') ;
                    }else{
                        tr.removeClass('success green_zone').addClass('normal_zone'); 
                        tr.detach().insertBefore('.task_list .task_date tbody .normal_zone_group') ;    
                    }
                }
            }else{
                if(!tr.hasClass( "red_zone" )){
                    tr.removeClass('success green_zone').addClass('danger red_zone'); 
                    tr.detach().insertBefore('.task_list .task_date tbody .red_zone_group') ;
                }
            }
        }
    });

}); // end $(document).ready()


function BBApp() {
    // override jquery validate plugin defaults
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
}    
// Add new customer
BBApp.prototype.onCustomerAdd = function() {
    // validate signup form on keyup and submit
    $("#customers_form").validate({
            rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    }
            },
            messages: {
                    name: "Please enter name",
                    email: "Please input correct email format."
            },
            submitHandler: function(form) {
                form.submit();
            }
    });
    
    $('#customers_form').submit();
};

// Add new user
BBApp.prototype.onUserAdd = function() {
    // validate signup form on keyup and submit
    $("#users_form").validate({
            rules: {
                    username: {
                        required: true,
                        remote: {
                            url: config.baseURL+"users/user_availability/",
                            type: "post",
                            data:
                                  {
                                      username: function()
                                      {
                                          return $('#users_form :input[name="username"]').val();
                                      },
                                      csrf_token: function()
                                      {
                                          return $('#users_form :input[name="csrf_token"]').val();
                                      }
                                      
                                  }
                        }   
                    },
                    password: {
                        required: true
                    },
                    password_repeat: {
                        equalTo: "input[name='password']"
                    }
            },
            messages: {
                    username: {
                        required: "Please enter username",
                        remote: "The username already exists!"
                    },
                    password: {
                        required: "Please enter password"
                    },
                    password_repeat: {
                        equalTo: "Enter enter Confirm Password Same as Password"
                    }
            },
            submitHandler: function(form) {
                form.submit();
            }
    });
    
    $('#users_form').submit();
};

// Edit user
BBApp.prototype.onUserEdit= function() {
    // validate signup form on keyup and submit
    $("#users_form_edit").validate({
            rules: {
                    password_current: {
                        required: true,
                        remote: {
                            url: config.baseURL+"users/user_password_current/",
                            type: "post",
                            data:
                                  {
                                      password_current: function()
                                      {
                                          return $('#users_form_edit :input[name="password_current"]').val();
                                      },
                                      csrf_token: function()
                                      {
                                          return $('#users_form_edit :input[name="csrf_token"]').val();
                                      }
                                      
                                  }
                        }   
                    },
                    password: {
                        required: true
                    },
                    password_repeat: {
                        equalTo: "input[name='password']"
                    }
            },
            messages: {
                    password_current: {
                        required: "Please enter password",
                        remote: "Password do not match"
                    },
                    password: {
                        required: "Please enter password"
                    },
                    password_repeat: {
                        equalTo: "Enter enter Confirm Password Same as Password"
                    }
            },
            submitHandler: function(form) {
                form.submit();
            }
    });
    
    $('#users_form_edit').submit();
};

// Edit column view
BBApp.prototype.onCustomerColumnEdit= function() {
    $('#customers_column_form').submit();
};

// Add company
BBApp.prototype.onCompanyAdd= function() {
    // validate signup form on keyup and submit
    $("#company_form").validate({
            rules: {
                    sitename: {
                        required: true,
                        remote: {
                            url: config.baseURL+"company/company_availability/",
                            type: "post",
                            data:
                                  {
                                      password_current: function()
                                      {
                                          return $('#company_form :input[name="sitename"]').val();
                                      },
                                      csrf_token: function()
                                      {
                                          return $('#company_form :input[name="csrf_token"]').val();
                                      }
                                      
                                  }
                        }   
                    }
            },
            messages: {
                    sitename: {
                        required: "Please enter company",
                        remote: "The company already exists!"
                    }
            },
            submitHandler: function(form) {
                form.submit();
            }
    });
    
    $('#company_form').submit();
};

// Add company
BBApp.prototype.onHolidayAdd= function() {
    // validate signup form on keyup and submit
    $("#holiday_form").validate({
            rules: {
                    holiday_name: {
                        required: true
                    }
            },
            messages: {
                    sitename: {
                        required: "Please enter holiday"
                    }
            },
            submitHandler: function(form) {
                form.submit();
            }
    });
    
    $('#holiday_form').submit();
};