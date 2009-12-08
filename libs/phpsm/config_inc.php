<?php
	// Set the path information
	global $g_sm_dir, $g_sm_uri, $g_sm_adodb;
	$g_sm_dir = "/var/www/html/dev/phpSecurityManager/";
	$g_sm_uri = "/dev/phpSecurityManager/";
	$g_sm_adodb = "/var/www/html/dev/adodb/adodb.inc.php";
	$g_sm_phpmailer = "/var/www/html/dev/phpmailer/class.phpmailer.php";
	
	// Debuging, this is useful to turn on if you are getting a general error
	global $g_sm_debug, $g_sm_session_id;
	$g_sm_debug=FALSE;
	
	$g_sm_session_id="SMSessionID";
	
	##################################################
	## 	Security Options:				 			##
	## 	 These options will effect the login system	##
	##################################################
	
	global $g_sm_open_reg, $g_sm_enable_sec, $g_sm_default_access_level, 
		$g_sm_autologin_user, $g_sm_autologin_passwd, $g_sm_user_privilages,
		$g_sm_admin_email, $g_sm_send_crlf, $g_sm_cleanup_files, 
		$g_sm_superuser_level, $g_sm_supported_countries;
	$g_sm_access_array = array(
		 "READER" => 0 , 
		 "AUTHOR" => 30  ,
		 "EDITOR" => 60  ,
		 "ADMINISTRATOR" => 90
	);
		 
	$g_sm_supported_languages = array(
		'en' => 'English', 
		'it' => 'Italian',
		'fr' => 'French',
		'sv' => 'Swedish',
		'da' => 'Danish',
		'de' => 'German',
		'es' => 'Spanish'
	);
	
	$g_sm_supported_countries = array(
		'us' => 'United States',
		'fr' => 'France',
		'it' => 'Italy',
		'de' => 'Germany',
		'sv' => 'Sweden',
		'da' => 'Denmark',
	);
	 
	$g_sm_open_reg=TRUE;  					// allow anyone to register as a new user
	$g_sm_enable_sec=TRUE; 					// turn on/off the login system
	
	$g_sm_newuser_access_level=30;			// set the default security level of a user registering on their own
	$g_sm_superuser_level="ADMINISTRATOR";	// The access level that denotes super users/admins

	$g_sm_newuser_set_password=FALSE;		// This determines if new users can set their passwords, or if it is emailed to them	
	$g_sm_admin_email = "manager@phprecipebook.com";	// set the system email address
	$g_sm_admin_name = "PHPRecipeBook Manager"; 		// name of admin in emails
	$g_sm_email_hosts = "mailserver.phprecipebook.com"; // set the smtp hosts to use for mailing (you could use sendmail if you wanted).

	/* 
		If security is disabled, then make sure the following
	   two settings point to a valid user with access level of your choice.
	*/
	$g_sm_autologin_user="admin";		// The username to automatically login as
	$g_sm_autologin_passwd="passwd";	// The password to login with
	
?>
