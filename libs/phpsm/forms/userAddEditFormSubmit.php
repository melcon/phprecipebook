<?php
$sm_login = isset( $_REQUEST['sm_login'] ) ? $_REQUEST['sm_login'] : '';
$sm_password = isset( $_REQUEST['sm_password'] ) ? $_REQUEST['sm_password'] : '';
$sm_old_password = isset( $_REQUEST['sm_old_password'] ) ? $_REQUEST['sm_old_password'] : '';
$sm_name = isset( $_REQUEST['sm_name'] ) ? $_REQUEST['sm_name'] : '';
$sm_email = isset( $_REQUEST['sm_email'] ) ? $_REQUEST['sm_email'] : '';
$sm_language = isset( $_REQUEST['sm_language'] ) ? $_REQUEST['sm_language'] : 'en';
$sm_country = isset( $_REQUEST['sm_country'] ) ? $_REQUEST['sm_country'] : 'us';
$sm_groups = isset( $_REQUEST['sm_groups'] ) ? $_REQUEST['sm_groups'] : NULL;
$sm_access_level = isset( $_REQUEST['sm_access_level'] ) ? $_REQUEST['sm_access_level'] : $this->getNewUserAccessLevel(); // sets the default user level
$sm_mode = isset( $_REQUEST['sm_mode'] ) ? $_REQUEST['sm_mode'] : "new";
$sm_delete = isset( $_REQUEST['sm_delete'] ) ? "yes" : "no";
$sm_submit_form = isset( $_REQUEST['sm_submit_form'] ) ? "yes" : "no";

// make sure we are logged in or it is an open reg system
if ($this->getUserLoginID() == "" && !$this->isOpenRegistration()) 
	die($this->_('This system is not in open registration mode, only the administrator can add users'));

// Only do this code if we are submiting
if ($sm_submit_form=="yes") {

if (!is_array($sm_groups)) $sm_groups = array();

if ($sm_mode == "new") {
	if ((!$this->isOpenRegistration()) && (!$this->checkAccessLevel($this->getSuperUserLevel()))) 
		die($this->_('The registration system is closed, you must be an administrator to add new users!'));
		
	// If it is an admin adding the value then let the type be set
	if ($this->checkAccessLevel($this->getSuperUserLevel()))
		$new_access_level = $sm_access_level;
	else 
		$new_access_level = $this->getNewUserAccessLevel();
	
	
	if (!$this->getNewUserSetPasswd() && (!$this->checkAccessLevel($this->getSuperUserLevel()))) {
		// we need to set the password and mail it to the user
		$sm_password = $this->createRandomPassword();
	}
	
	// create the user
	if ($this->addNewUser($sm_login,$sm_name,$sm_password,$sm_email,$sm_language,$sm_country,$sm_groups,$new_access_level)) {
		// Handle the password emailing, if admin is not creating user
		if (!$this->getNewUserSetPasswd() && (!$this->checkAccessLevel($this->getSuperUserLevel())))	{
			// mail out the password
			$subject = $this->_('PHPRecipeBook Password');
			$message = $this->_('Your password to login is included in this email below') . ":\n";
			$message .= $this->_('Login ID') . ":" . $sm_login . "\n";
			$message .= $this->_('Password') . ":" . $sm_password . "\n";
			$this->sendEmail($sm_email, $sm_name, $subject, $message);
		}
	}

} else if ($sm_mode == "edit") {
	if ($sm_delete == "no") {
		if (!$this->checkAccessLevel($this->getSuperUserLevel()) && $this->getUserLoginID() != $sm_login)
			die($AppUI->_('You must be an administrator in order to edit other users!'));
		
		// only the admin can change access levels and groups
		if (!$this->checkAccessLevel($this->getSuperUserLevel())) {
			$sm_access_level="";
			$sm_groups = array();
		}
		
		// If a user is changing the password, make sure the know the old one first
		if ($sm_password != "" && 
			(($this->getUserPassword($sm_login) != md5($sm_old_password)) && 
			(!$this->checkAccessLevel($this->getSuperUserLevel())))) {
				die($this->_('Old password does not match currently set password!'));
		}
		// all good, go modify the user
		$this->modifyUser($sm_login,$sm_name,$sm_password,$sm_email,$sm_language,$sm_country,$sm_groups,$sm_access_level);
		
	} else {
		if (!$this->checkAccessLevel($this->getSuperUserLevel())) 
			die($this->_('You must be an administrator in order to delete users!'));
		
		// delete the user and it's associations with groups
		$this->deleteUser($sm_login);
	}
}

}
?>