<?php
	/*
		This is the custom config file for PHPRecipeBook. Put the values you wish to customize
		in this file. The values that should work for a default install are pulled in from
		includes/config_inc.php.  You can look at that file for possible values you can put 
		in this one.  Any values put in this file will override config_inc.php
	*/
	
	##################################
	## Directory Settings 	  		##
	##################################
	// Set the path information (Leaving these commented out should work)
	//global $g_rb_basedir, $g_rb_baseurl;
	//$g_rb_basedir = "/var/www/html/phprecipebook/";
	//$g_rb_baseurl = "/phprecipebook/";
	
	// If you change the default paths above uncomment these
	//global $g_sm_adodb, $g_sm_phpmailer;
	//$g_sm_dir = $g_rb_basedir . "libs/phpsm/";
	//$g_sm_url = $g_rb_baseurl . "libs/phpsm/";
	//$g_sm_adodb = "/var/www/shared/libs/adodb/adodb.inc.php";
	//$g_sm_phpmailer = "/var/www/shared/libs/phpmailer/class.phpmailer.php";
	
	// Make sure these email settings are valid otherwise the new users will not get their passwords
	global $g_sm_admin_email, $g_sm_admin_name, $g_sm_email_hosts;
	$g_sm_admin_email = "user@host.com";		// set the system email address
	$g_sm_admin_name = "PHPRecipeBook Manager"; // name of admin in emails
	$g_sm_email_hosts = "mailserver.host.com"; 	// set the smtp hosts to use for mailing (you could use sendmail if you wanted).

	// Set the Theme
	global $g_rb_theme;
	$g_rb_theme="default";
	
	#############################################
	## Database Connection options (required)  ##
	#############################################
	/* 
		Select one type: (mysql,postgres)
		see adodb readme files for more options
	*/
	global $g_rb_database_type, $g_rb_database_host, $g_rb_database_name, $g_rb_database_user, $g_rb_database_password;
	
	// Example PostgreSQL Settings
	/*$g_rb_database_type = "postgres";
	$g_rb_database_host = "localhost:5432";
	$g_rb_database_name = "recipedb";
	$g_rb_database_user = "postgres";
	$g_rb_database_password = "";*/
	
	// Example MySQL settings
	$g_rb_database_type = "mysql";
	$g_rb_database_host = "localhost:/var/lib/mysql/mysql.sock";
	$g_rb_database_name = "recipedb";
	$g_rb_database_user = "root";
	$g_rb_database_password = "";
	
?>
