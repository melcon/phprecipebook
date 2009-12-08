<?php
include_once(SECURITYMANAGER_PHPMAILER);

class SecurityManager {
	// System wide settings
	var $_debug = FALSE;
	var $_authType = NULL;
	var $_secureLogin = TRUE;			// Enabled AutoLogin?
	var $_openRegistration = TRUE; 		// Is Registration open to everyone?
	var $_newUserAccessLevel = NULL; 	// Default access level for new users.
	var $_autoLoginUser = NULL;
	var $_autoLoginPasswd = NULL;
	var $_accessArray = array();
	var $_supportedLanguages = array();
	var $_newUserSetPassword = FALSE;	// can a new user set their own password.
	var $_superUserLevel = NULL;
	var $_translationObject = NULL;
	var $_errorMsg = NULL;				// stores last error message
	
	// Email settings
	var $_adminEmail = NULL;
	var $_adminName = NULL;
	var $_emailHosts = NULL;
	var $_emailNewUserSubject = NULL;
	var $_emailNewUserMessage = NULL;
	
	// Stored user information
	var $_userLoginID = NULL;
	var $_userName = NULL;
	var $_userLanguage = NULL;
	var $_userCountry = NULL;
	var $_userEmail = NULL;
	var $_userGroups = array();
	var $_userLastLogin = NULL;
	var $_userDateCreated = NULL;
	var $_userAccessLevel = NULL;
	
	// Constants and settings that can be tweaked
	var $_formsPath = "forms/";
	var $_pagesPath = "pages/";
	var $_formUserAddEdit = "userAddEditForm.php";
	var $_formUserAddEditSubmit = "userAddEditFormSubmit.php";
	var $_pageUserAddEdit = "userAddEditPage.php";
	var $_formUserLogin = "userLoginForm.php";
	var $_formUserLoginSubmit = "userLoginFormSubmit.php";
	var $_formUserAdmin = "userAdminForm.php";
	var $_formGroupAdmin = "groupAdminForm.php";
	var $_formGroupAddEdit = "groupAddEditForm.php";
	var $_formGroupAddEditSubmit = "groupAddEditFormSubmit.php";
	var $_pageGroupAddEdit = "groupAddEditPage.php";
	
	function SecurityManager() {} // an initialization with defaults

	/* Functions to get and set defaults */
	
	function setDebug($debug) {
		$this->_debug = $debug;
	}
	
	function isDebug() {
		return ($this->_debug);
	}
	
	function setErrorMsg($msg) {
		$this->_errorMsg = $msg;
	}
	
	function addErrorMsg($msg) {
		$this->_errorMsg .= $msg;
	}
	
	function getErrorMsg() {
		return $this->_errorMsg;
	}
	
	function setTranslationObject($obj) {
		$this->_translationObject=$obj;
	}
	
	function getTranslationObject() {
		return ($this->_translationObject);
	}
	
	function setAuthType($type) {
		$this->_authType=$type;
	}
	
	function getAuthType() {
		return $this->_authType;
	}
	
	function setSecureLogin( $secure ) {
		$this->_secureLogin = $secure;
	}
	
	function isSecureLogin() {
		return $this->_secureLogin;
	}
	
	function setOpenRegistration( $reg ) {
		$this->_openRegistration = $reg;
	}
	
	function isOpenRegistration() {
		return $this->_openRegistration;
	}
	
	function setNewUserAccessLevel( $level ) {
		$this->_newUserAccessLevel=$level;
	}
	
	function getNewUserAccessLevel() {
		return $this->_newUserAccessLevel;
	}
	
	function setAutoLoginUser($user) {
		$this->_autoLoginUser = $user;
	}
	
	function setAutoLoginPasswd($passwd) {
		$this->_autoLoginPasswd = $passwd;
	}
	
	function setAccessArray($levels) {
		$this->_accessArray=$levels;
	}
	
	function getAccessArray() {
		return ($this->_accessArray);
	}
	
	function getRevAccessArray() {
		$arr = array();
		foreach ($this->_accessArray  as $j=>$k) {
			$arr[$k] = $j;
		}
		return $arr;
	}
	
	function setSupportedLanguages($langs) {
		$this->_supportedLanguages = $langs;
	}
	
	function getSupportedLanguages() {
		return ($this->_supportedLanguages);
	}
	
	function setSupportedCountries($countries) {
		$this->_supportedCountries = $countries;
	}
	
	function getSupportedCountries() {
		return ($this->_supportedCountries);
	}
	
	function setNewUserSetPasswd($set) {
		$this->_newUserSetPassword = $set;
	}
	
	function getNewUserSetPasswd() {
		return ($this->_newUserSetPassword);
	}
	
	function setAdminEmail($email) {
		$this->_adminEmail = $email;
	}
	
	function getAdminEmail() {
		return ($this->_adminEmail);
	}
	
	function setAdminName($name) {
		$this->_adminName = $name;
	}
	
	function getAdminName() {
		return ($this->_adminName);
	}
	
	function setEmailHosts($hosts) {
		$this->_emailHosts = $hosts;
	}
	
	function getEmailHosts() {
		return ($this->_emailHosts);
	}
	
	function setSuperUserLevel($level) {
		$this->_superUserLevel=$level;
	}
	
	function getSuperUserLevel() {
		return ($this->_superUserLevel);
	}
	
	/* Functions that get and set user attributes */
	
	function getUserLoginID() {
		return $this->_userLoginID;
	}

	function getUserName() {
		return $this->_userName;
	}
	
	function getUserEmail() {
		return $this->_userEmail;
	}
	
	function getUserLanguage() {
		return $this->_userLanguage;
	}
	
	function getUserCountry() {
		return $this->_userCountry;
	}
	
	function getUserGroups() {
		return $this->_userGroups;
	}
	
	function getUserLastLogin() {
		return $this->_userLastLogin;
	}
	
	function getUserDateCreated() {
		return $this->_userDateCreated;
	}
	
	function getUserAccessLevel() {
		return $this->_userAccessLevel;
	}
	
	/** Specific tests to run on users **/
	
	// checks the access level of the current user
	function checkAccessLevel($level) {
		if ($this->getUserAccessLevel()>=$this->_accessArray[$level])
			return true;
		else
			return false;
	}
	
	/** Functions to handle data requests, login, get lists of users... **/

	function logout() {
		global $g_sm_session_id;
		$this->_userLoginID = NULL;
		$this->_userAccessLevel = 0;
		$this->_userGroups = array();
		$this->_userEmail = NULL;
		$this->_userName = NULL;
		$this->_userLastLogin = NULL;
		$this->_userAccountCreated = NULL;
		session_unregister($g_sm_session_id);
		session_destroy();
		//$_SESSION[$g_sm_session_id] = NULL; //destroy the session, just to be sure
	}

	/** Functions to get the different forms and pages **/

	function getLoginForm($submitURL='',$regURL='') {
		if (!$submitURL) $submitURL= $_SERVER['REQUEST_URI']; // it will submit to itself if not specified
		if (!$regURL) $regURL= SECURITYMANAGER_URI . $this->_pagesPath . $this->_pageUserAddEdit;
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formUserLogin);
	}
	
	function getLoginFormSubmit() {
		// just include it and when are done, it will have access to $this
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formUserLoginSubmit);
	}
	
	function getUserAddEditForm($submitURL='') {
		if (!$submitURL) $submitURL= $_SERVER['REQUEST_URI']; // it will submit to itself if not specified
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formUserAddEdit);
	}
	
	function getUserAddEditFormSubmit() {
		// just include it and when are done, it will have access to $this
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formUserAddEditSubmit);
	}
	
	function getUserAdminForm($editURL='') { //edit page is also the reg page
		if (!$editURL) $editURL= SECURITYMANAGER_URI . $this->_pagesPath . $this->_pageUserAddEdit;
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formUserAdmin);
	}
	
	function getGroupAdminForm($editURL='',$deleteURL='') {
		if (!$editURL) $editURL= SECURITYMANAGER_URI . $this->_pagesPath . $this->_pageGroupAddEdit;
		if (!$deleteURL) $deleteURL= SECURITYMANAGER_URI . $this->_pagesPath . $this->_pageGroupAddEdit;
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formGroupAdmin);
	}
	
	function getGroupAddEditForm() {
		if (!$submitURL) $submitURL= $_SERVER['REQUEST_URI']; // it will submit to itself if not specified
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formGroupAddEdit);
	}
	
	function getGroupAddEditFormSubmit() {
		// just include it and when are done, it will have access to $this
		include(SECURITYMANAGER_DIR . $this->_formsPath . $this->_formGroupAddEditSubmit);
	}	


	/** Utility functions that have varying purposes **/

	// given an integer will determine what access level the user falls under
	function getAccessLevel($level) {
		$found=NULL;
		$arr = $this->getAccessArray();
		foreach ($arr as $key=>$value) {
			if (!($level > $value) && !$found) {
				$found=$key;
			}
		}
		// we made it to the top, must be an admin
		if (!$found) {
			$found=$key;
		}
		
		return $found; // return the match
	}
	
	/*
		given an integer will determine what access level the user falls under by returning the numeric
		value for that access level rounded to the exact match
		@param $level the access level to round
		@return the access level rounded
	*/
	function getAccessLevelRounded($level) {
		$found=NULL;
		$match=NULL;
		$arr = $this->getAccessArray();
		foreach ($arr as $key=>$value) {
			if (!($level > $value) && !$match) {
				$found=$value;
				$match=1;
			}
		}
		// we made it to the top, must be an admin
		if (!$match) {
			$found=$value;
		}
		return $found; // return the match
	}
	
	// Checks to see if the currently logged in user shares groups with another given user
	function hasGroupsWith($user) {
		$compGroups = $this->getUsersGroups($user);
		$myGroups = $this->getUserGroups();
		if (is_array($compGroups) && is_array($myGroups)) {
			foreach ($myGroups as $mg) {
				foreach ($compGroups as $cg) {
					if ($mg == $cg) return true;
				}
			}
		}
		return false;
	}
	
	// return the string if we have no way of handling it.
	function _($str) {
		if ($this->_translationObject!=NULL) {
			return ($this->_translationObject->_($str));
		}
		else
			return $str;
	}
	
	// And some utility functions, should go in there own file soon
	function arraySelect( &$arr, $select_name, $select_attribs, $selected ) {
		reset( $arr );
		$s = "<select name=\"$select_name\" $select_attribs>";
		while (list( $k, $v ) = each( $arr)) {
			$s .= '<option value="'.$k.'"'.($k == $selected ? ' selected' : '').'>'.$v;
		}
		$s .= '</select>';
		return $s;
	}
	
	// Multiple select
	function arraySelect2( &$arr, $select_name, $select_attribs, $selected ) {
		reset( $arr );
		$s = "<select multiple size=3 name=\"$select_name\" $select_attribs>";
		while (list( $k, $v ) = each( $arr)) {
			$s .= '<option value="'.$k.'"';
			if (count($selected)) {
				foreach ($selected as $key) {
					if ($k == $key) 
						$s .= ' selected';
				}
			}
			$s .= '>'.$v;
		}
		$s .= '</select>';
		return $s;
	}
	
	# creates a random 12 character password
	function createRandomPassword() {
		$t_val = mt_rand( 0, mt_getrandmax() ) + mt_rand( 0, mt_getrandmax() );
		$t_val = md5( $t_val );
		return substr( $t_val, 0, 12 );
	}
	
	# this function sends the actual email
	function sendEmail( $recipient_address, $recipient_name, $subject, $message) {
		## The php builtin mailing functions do not work all the time so I have
		## selected to use phpMailer (phpmailer.sourceforge.net/)
		$mail = new phpmailer();

		$mail->From     = $this->_adminEmail;
		$mail->FromName = $this->_adminName;
		$mail->Host     = $this->_emailHosts;
		$mail->Mailer   = "smtp";
		$mail->Subject 	= $subject;

		$mail->Body = $message;
		$mail->AddAddress($recipient_address, $recipient_name);
		if(!$mail->Send()) {
			echo $this->_('There has been a mail error sending to ' . $recipient_address . " " . $mail->ErrorInfo . "<br />");
			return false;
		}
	
		// Clear all addresses and attachments for next loop
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		return true;
	}
}
?>