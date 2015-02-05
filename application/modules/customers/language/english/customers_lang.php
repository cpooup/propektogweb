<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin Customers Language File
 */

// Titles
$lang['customers title forgot']                   = "Forgot Password";
$lang['customers title login']                    = "Login";
$lang['customers title profile']                  = "Profile";
$lang['customers title register']                 = "Register";
$lang['customers title user_add']                 = "Add Customer";
$lang['customers title user_delete']              = "Confirm Delete Customer";
$lang['customers title user_edit']                = "Edit Customers";
$lang['customers title user_list']                = "Customer List";
$lang['customers title account_login']            = "Use a local account to log in.";

// Buttons
$lang['customers button add_new_user']            = "Add New Customer";
$lang['customers button register']                = "Create Account";
$lang['customers button reset_password']          = "Reset Password";

// Tooltips
$lang['customers tooltip add_new_user']           = "Create a brand new user.";

// Links
$lang['customers link forgot_password']           = "Forgot your password?";
$lang['customers link register_account']          = "Register for an account.";

// Table Columns
$lang['customers col first_name']                 = "First Name";
$lang['customers col is_admin']                   = "Admin";
$lang['customers col last_name']                  = "Last Name";
$lang['customers col user_id']                    = "ID";
$lang['customers col username']                   = "Username";

// Form Inputs
$lang['customers input email']                    = "Email";
$lang['customers input first_name']               = "First Name";
$lang['customers input is_admin']                 = "Is Admin";
$lang['customers input last_name']                = "Last Name";
$lang['customers input password']                 = "Password";
$lang['customers input password_repeat']          = "Repeat Password";
$lang['customers input status']                   = "Status";
$lang['customers input username']                 = "Username";
$lang['customers input username_email']           = "Username or Email";
$lang['customers input remember']                 = "Remember me?";

// Help
$lang['customers help passwords']                 = "Only enter passwords if you want to change it.";

// Messages
$lang['customers msg add_user_success']           = "%s was successfully added!";
$lang['customers msg delete_confirm']             = "Are you sure you want to delete <strong>%s</strong>? This can not be undone.";
$lang['customers msg delete_user']                = "You have succesfully deleted <strong>%s</strong>!";
$lang['customers msg edit_profile_success']       = "Your profile was successfully modified!";
$lang['customers msg edit_user_success']          = "%s was successfully modified!";
$lang['customers msg register_success']           = "Thanks for registering, %s! Check your email for a confirmation message. Once
                                                 your account has been verified, you will be able to log in with the credentials
                                                 you provided.";
$lang['customers msg password_reset_success']     = "Your password has been reset, %s! Please check your email for your new temporary password.";
$lang['customers msg validate_success']           = "Your account has been verified. You may now log in to your account.";
$lang['customers msg email_new_account']          = "<p>Thank you for creating an account at %s. Click the link below to validate your
                                                 email address and activate your account.<br /><br /><a href=\"%s\">%s</a></p>";
$lang['customers msg email_new_account_title']    = "New Account for %s";
$lang['customers msg email_password_reset']       = "<p>Your password at %s has been reset. Click the link below to log in with your
                                                 new password:<br /><br /><strong>%s</strong><br /><br /><a href=\"%s\">%s</a>
                                                 Once logged in, be sure to change your password to something you can
                                                 remember.</p>";
$lang['customers msg email_password_reset_title'] = "Password Reset for %s";

// Errors
$lang['customers error add_user_failed']          = "%s could not be added!";
$lang['customers error delete_user']              = "<strong>%s</strong> could not be deleted!";
$lang['customers error edit_profile_failed']      = "Your profile could not be modified!";
$lang['customers error edit_user_failed']         = "%s could not be modified!";
$lang['customers error email_exists']             = "The email <strong>%s</strong> already exists!";
$lang['customers error email_not_exists']         = "That email does not exists!";
$lang['customers error invalid_login']            = "Invalid username or password";
$lang['customers error register_failed']          = "Your account could not be created at this time. Please try again.";
$lang['customers error user_id_required']         = "A numeric user ID is required!";
$lang['customers error user_not_exist']           = "That user does not exist!";
$lang['customers error username_exists']          = "The username <strong>%s</strong> already exists!";
$lang['customers error validate_failed']          = "There was a problem validating your account. Please try again.";
