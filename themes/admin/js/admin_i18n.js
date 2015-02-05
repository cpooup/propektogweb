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
                        minlength: 5,
                        maxlength: 30,
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
                        required: true,
                        minlength: 5
                    },
                    password_repeat: {
                        minlength: 5,
                        equalTo: "input[name='password']"
                    }
            },
            messages: {
                    username: {
                        required: "Please enter username",
                        minlength: "Please enter username [min length is 5]",
                        maxlength: "Please enter username [max length is 30]",
                        remote: "The username already exists!"
                    },
                    password: {
                        required: "Please enter password",
                        minlength: "Please enter password [min length is 5]"
                    },
                    password_repeat: {
                        equalTo: "Enter enter Confirm Password Same as Password",
                        minlength: "Please enter password [min length is 5]"
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
                        minlength: 5,
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
                        required: true,
                        minlength: 5
                    },
                    password_repeat: {
                        minlength: 5,
                        equalTo: "input[name='password']"
                    }
            },
            messages: {
                    password_current: {
                        required: "Please enter password",
                        minlength: "Please enter password [min length is 5]",
                        remote: "Password do not match"
                    },
                    password: {
                        required: "Please enter password",
                        minlength: "Please enter password [min length is 5]"
                    },
                    password_repeat: {
                        equalTo: "Enter enter Confirm Password Same as Password",
                        minlength: "Please enter password [min length is 5]"
                    }
            },
            submitHandler: function(form) {
                form.submit();
            }
    });
    
    $('#users_form_edit').submit();
};