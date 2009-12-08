<?php
	$sm_login_id = isset( $_POST['sm_login_id'] ) ? $_POST['sm_login_id'] : ''; 
	// the name of this var is intentional different then login_id to avoid auto login
	$sm_password = isset( $_POST['sm_password'] ) ? $_POST['sm_password'] : '';
	$sm_logout = isset( $_POST['sm_logout'] ) ? $_POST['sm_logout'] : '';
	if ($sm_login_id) {
		// login if they are passing us a login ID
		if (!$this->login($sm_login_id,$sm_password)) {
			$this->addErrorMsg($this->_('Login Failed!'));
		}
	} else if ($sm_logout) 
		$this->logout();
?>
