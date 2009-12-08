<?php
	/* This file pulls in all the options and classes for user management. You include this file and all the config and default settings
		will be correctly included in your program
	*/

	global $g_sm_dir, $g_sm_url, $g_sm_adodb;
	
	define('SECURITYMANAGER_DIR', $g_sm_dir);
	define('SECURITYMANAGER_URI', $g_sm_url);
	define('SECURITYMANAGER_PHPMAILER', $g_sm_phpmailer);
	define('SECURITYMANAGER_ADODB', $g_sm_adodb); // needed if using database
	
	// include the main class
	include(SECURITYMANAGER_DIR . "classes/phpSM.class.php"); // this is were the guts are
	
	include_once(SECURITYMANAGER_DIR."classes/drivers/phpSM_database.class.php");
	GLOBAL $SecurityManager_Type;
	
	/**
		loadClassCode: Includes the requested driver so that a new instance of the SecurityManager class can be created
	 */
	function loadClassCode($type) 
	{
		GLOBAL $SecurityManager_Type;
	
		if (!$type) return false;
		$SecurityManager_Type = strtolower($type);
		return @include_once(SECURITYMANAGER_DIR."classes/drivers/phpSM_$SecurityManager_Type.class.php");
	}
	
	
	/**
		setClassDefaults: sets the defaults of the SecurityManager object, these values are read from config_inc
	 */
	function &setClassDefaults(&$obj) 
	{
		// turn on globals
		global $g_sm_open_reg, $g_sm_enable_sec, $g_sm_autologin_user,
			$g_sm_autologin_passwd, $g_sm_newuser_access_level,
			$g_sm_default_access_level, $g_sm_access_array,
			$g_sm_debug, $g_sm_supported_languages, $g_sm_newuser_set_password,
			$g_sm_admin_email, $g_sm_admin_name, $g_sm_email_hosts,
			$g_sm_superuser_level, $g_sm_supported_countries;
		// Set the values
		$obj->setDebug($g_sm_debug);
		$obj->setOpenRegistration($g_sm_open_reg);
		$obj->setAccessArray($g_sm_access_array);
		$obj->setSupportedLanguages($g_sm_supported_languages);
		$obj->setSupportedCountries($g_sm_supported_countries);
		
		$obj->setAdminEmail($g_sm_admin_email);
		$obj->setAdminName($g_sm_admin_name);
		$obj->setEmailHosts($g_sm_email_hosts);
		
		$obj->setNewUserAccessLevel($g_sm_newuser_access_level);
		$obj->setNewUserSetPasswd($g_sm_newuser_set_password);
		$obj->setSuperUserLevel($g_sm_superuser_level);
		$obj->setSecureLogin($g_sm_enable_sec);
		if (!$g_sm_enable_sec) {
			$obj->setAutoLoginUser($g_sm_autologin_user);
			$obj->setAutoLoginPasswd($g_sm_autologin_passwd);
		}

		return $obj;
	}
	
	/**
		newSecurityModel: creates a new SecurityManager object and sets it defaults.
	 */
	function &newSecurityModel($type='')
	{
		GLOBAL $SecurityManager_Type, $g_sm_session_id;
		$rez = true;
		if ($type) {
			if ($SecurityManager_Type != $type) {
				loadClassCode($type);
			}
		} else { 
			if (!empty($SecurityManager_Type)) {
				loadClassCode($SecurityManager_Type);
			} else {
				 $rez = false;
			}
		}
		
		if (!$rez) {
			// we got an error
			echo "Error Loading Security Class"; //We might now be able to translate this
		}
		
		// The class code is loaded, now we can start the session
		session_cache_limiter('must-revalidate'); // allow forms to use the back button
		session_start(); // start up a session
		session_register($g_sm_session_id);
		
		/* For right now you are not allowed to mix and match auth types in the same
			session, if you need a different auth type user a different session ID */

		if (!isset($_SESSION[$g_sm_session_id])) {
			$cls = 'SecurityManager_'.$SecurityManager_Type;
			$obj = new $cls();
			$obj = setClassDefaults($obj);
			$_SESSION[$g_sm_session_id] = $obj;
		}
		$obj =& $_SESSION[$g_sm_session_id];
		return $_SESSION[$g_sm_session_id];
	}
	
	/* 
		saveSecurityModel: for some odd reason it is not saving a reference to the object in the session, so we have to save
			again when we are done working with it, a pain in the ass, but not fatal. 
	*/
	function saveSecurityModel(&$obj) {
		global $g_sm_session_id;
		$_SESSION[$g_sm_session_id] = $obj;
	}
?>
