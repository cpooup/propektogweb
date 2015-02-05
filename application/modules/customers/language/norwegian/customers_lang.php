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
$lang['customers title add_new']                   = "Add New";
$lang['customer edit']                   = "Edit ID: %s";
$lang['customers title column_edit']                = "Edit view";
$lang['enable']                                 = "Enable";
$lang['disable']                                 = "Disable";
// Buttons
$lang['customers button add_new_user']            = "Legg til";
$lang['customers button register']                = "Create Account";
$lang['customers button reset_password']          = "Reset Password";
$lang['customers button setting_column']          = "View";


// Tooltips
$lang['customers tooltip add_new_user']           = "Create a new customer.";

// Links
$lang['customers link forgot_password']           = "Forgot your password?";
$lang['customers link register_account']          = "Register for an account.";

// Table Columns New
$lang['customers col name']                     = 'Kundenavn';
$lang['customers col email']                    = "Mailadresse";
$lang['customers col day']                      = "Dag";
$lang['customers col comment']                  = "Kommentarer";
$lang['customers col approveby']                = "Godkjennere";
$lang['customers col updated']                  = "Last Update";
$lang['customers col updateby']                 = "Update by";
$lang['customers col data_entry']               = "Data Entry";
$lang['customers col posting']                  = "Posting";
$lang['customers col sitename']                  = "Company";

// Result Data Entry Day
$lang['data_entry_monday1']                 = "mandag<br>";
$lang['data_entry_tuesday1']                 = "tirsdag<br>";
$lang['data_entry_wednesday1']                 = "onsdag<br>";
$lang['data_entry_thursday1']                 = "torsdag<br>";
$lang['data_entry_friday1']                 = "fredag";

$lang['2data_entry_monday1']                 = "mandag<br>";
$lang['3data_entry_tuesday1']                 = "tirsdag<br>";
$lang['4data_entry_wednesday1']                 = "onsdag<br>";
$lang['5data_entry_thursday1']                 = "torsdag<br>";
$lang['6data_entry_friday1']                 = "fredag";

// Result Posting Day
$lang['posting_monday1']                 = "mandag<br>";
$lang['posting_tuesday1']                 = "tirsdag<br>";
$lang['posting_wednesday1']                 = "onsdag<br>";
$lang['posting_thursday1']                 = "torsdag<br>";
$lang['posting_friday1']                 = "fredag";

$lang['2posting_monday1']                 = "mandag<br>";
$lang['3posting_tuesday1']                 = "tirsdag<br>";
$lang['4posting_wednesday1']                 = "onsdag<br>";
$lang['5posting_thursday1']                 = "torsdag<br>";
$lang['6posting_friday1']                 = "fredag";


// Form Inputs
$lang['customers input email']                    = "Mailadresse";
$lang['customers input name']               = "Kundenavn";
$lang['customers input approveby']               = "Godkjennere";
$lang['customers input alternativ']               = "Oppgaver";
$lang['customers select alternativ']               = "--Select Oppgaver--";
$lang['customers select alternativ1']               = "Prekontering";
$lang['customers select alternativ2']               = "Bokføring";
$lang['customers select alternativ3']               = "Prekontering og Bokføring";

$lang['customers input priority']               = "Priority";
$lang['customers select priority yes']               = "Yes";
$lang['customers select priority no']               = "No";

$lang['customers input first_name']               = "First Name";
$lang['customers input is_admin']                 = "Is Admin";
$lang['customers input last_name']                = "Last Name";
$lang['customers input password']                 = "Password";
$lang['customers input password_repeat']          = "Repeat Password";
$lang['customers input status']                   = "Status";
$lang['customers input username']                 = "Username";
$lang['customers input username_email']           = "Username or Email";
$lang['customers input remember']                 = "Remember me?";
$lang['customers input enter_name']                        = "Please enter name";
$lang['customers input enter_email']                        = "Please enter email";
$lang['customers input enter_comment']                        = "Please enter comment";


// Help
$lang['customers help passwords']                 = "Only enter passwords if you want to change it.";

// Messages
$lang['customers msg select at least one day']               = "Please select at least one day.";
$lang['customers msg add_user_success']           = "%s was successfully added!";
$lang['customers msg edit_user_success']           = "%s was successfully edited!";
$lang['customers msg edit_column_success']           = "View successfully edited!";
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
$lang['customers error edit_user_failed']          = "%s could not be edited!";
$lang['customers error edit_column_failed']          = "View could not be edited!";
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

//Export Columns Name
$lang['export col name']                     = 'Kundenavn';
$lang['export col email']                    = "Mailadresse";
$lang['export col day']                      = "Dag";
$lang['export col comment']                  = "Kommentarer";
$lang['export col approveby']                = "Godkjennere";
$lang['export col updated']                  = "Last Update";
$lang['export col updateby']                 = "Update by";
$lang['export col data_entry']               = "Data Entry";
$lang['export col posting']                  = "Posting";
$lang['export col sitename']                  = "Company";
$lang['export col alternativ']               = "Oppgaver";
$lang['export col priority']               = "Priority";

// Export Result Data Entry Day
$lang['export data_entry_monday1']                 = "mandag \r\n";
$lang['export data_entry_tuesday1']                 = "tirsdag \r\n";
$lang['export data_entry_wednesday1']                 = "onsdag \r\n";
$lang['export data_entry_thursday1']                 = "torsdag \r\n";
$lang['export data_entry_friday1']                 = "fredag";

// Export Result Posting Day
$lang['export posting_monday1']                 = "mandag \r\n";
$lang['export posting_tuesday1']                 = "tirsdag \r\n";
$lang['export posting_wednesday1']                 = "onsdag \r\n";
$lang['export posting_thursday1']                 = "torsdag \r\n";
$lang['export posting_friday1']                 = "fredag";

//Tabs
$lang['customers tab1']                 = "alle";
$lang['customers tab2']                 = "mandag";
$lang['customers tab3']                 = "tirsdag";
$lang['customers tab4']                 = "onsdag";
$lang['customers tab5']                 = "torsdag";
$lang['customers tab6']                 = "fredag";
