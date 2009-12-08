<?php
$sm_mode = isset($_REQUEST['sm_mode']) ? $_REQUEST['sm_mode'] : "new";
$sm_login = isset ($_REQUEST['sm_login'] ) ? $_REQUEST['sm_login'] : "";

if ($sm_login != "") $sm_mode="edit";

if ($sm_mode=="edit") {
	$user_details = $this->getUserDetails($sm_login);
	$user_groups = $this->getUsersGroups($sm_login);
}

if ($this->getUserLoginID() == "" && !$this->isOpenRegistration()) 
	die($this->_('This system is not in open registration mode, only the administrator can add users'));
	
?>
<script language="javascript">
function doSubmit() {
	f = document.SMNewUserFrm;
	msg = '';
	<?php if ($sm_mode != "edit") {?>
	if (f.sm_login.value.length < 3) {
		msg += '<?php echo $this->_('Please enter a valid')." ".$this->_('User login name');?>.\n';
	}
	<?php } ?>
	
	<?php if ($this->getNewUserSetPasswd() || $sm_mode == "edit") { ?>
	if (f.sm_password.value.length != 0 && f.sm_password.value.length < 3) {
		msg += '<?php echo $this->_('Please enter a valid')." ".$this->_('User login password');?>.\n';
	}

	if (f.sm_password.value != f.sm_password2.value) {
		msg += '<?php echo $this->_('Please re-enter your passwords as they do not match');?>.\n';
	}
	<?php } ?>
	
	if (f.sm_name.value.length < 3) {
		msg += '<?php echo $this->_('Please enter a valid')." ".$this->_('Real user name');?>.\n';
	}
	if (f.sm_email.value.length < 3) {
		msg += '<?php echo $this->_('Please enter an email address');?>.\n';
	}

	if (msg) {
		alert( msg );
	} else {
		f.submit();
	}
}
</script>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php 
			if ($sm_mode=="edit")
				echo $this->_('Edit User');
			else 
				echo $this->_('New User Registration');
			?></td>
</tr>
</table>

<p><?php 
		if ($sm_mode=="edit")
			echo $this->_('Only put a value in the password field if you wish to changed it');
		else 
			echo $this->_('Please fill in the following information.  All fields are required.');
	?></p>

<table cellspacing="0" cellpadding="3" border="0" width="50%" class="std">
<form name="SMNewUserFrm" action="<?php echo $submitURL;?>" method="POST">
<input type=hidden name="sm_mode" value="<?php echo $sm_mode;?>">

<tr>
	<td align="right"><?php echo $this->_('Login ID');?>:</td>
	<?php if ($sm_login == "") { ?>
		<td><input type="text" name="sm_login" value="<?php echo $sm_login;?>"></td>
	<?php } else { ?>
		<input type="hidden" name="sm_login" value="<?php echo $sm_login;?>">
		<td><?php echo $sm_login;?></td>
	<?php } ?>
</tr>
<?php if ($sm_mode == "edit" && (!$this->checkAccessLevel($this->getSuperUserLevel()))) { ?>
<tr>
	<td align="right"><?php echo $this->_('Old Password');?>:</td>
	<td><input type="password" name="sm_old_password"></td>
</tr>
<?php } ?>
<?php if ($this->getNewUserSetPasswd() || $sm_mode == "edit" || $this->checkAccessLevel($this->getSuperUserLevel())) {?>
<tr>
	<td align="right"><?php echo $this->_('Password');?>:</td>
	<td><input type="password" name="sm_password"></td>
</tr>
<tr>
	<td align="right"><?php echo $this->_('Confirm password');?>:</td>
	<td><input type="password" name="sm_password2"></td>
</tr>
<?php } ?>
<tr>
	<td align="right"><?php echo $this->_('Real Name');?>:</td>
	<td><input type="text" name="sm_name" value="<?php echo isset($user_details['name']) ? $user_details['name'] : "";?>"></td>
</tr>
<tr>
	<td align="right"><?php echo $this->_('Email Address');?>:</td>
	<td><input type="text" name="sm_email" value="<?php echo isset($user_details['email']) ? $user_details['email'] : "";?>"></td>
</tr>
<tr>
	<td align="right"><?php echo $this->_('Language');?>:</td>
	<td>
<?php
	$lang='';
 	if ($sm_mode=="edit")
		$lang = $user_details['language'];
	$arr = $this->getSupportedLanguages();
	echo $this->arraySelect( $arr, 'sm_language', 'size="1"', $lang );
?>
	</td>
</tr>
<tr>
	<td align="right"><?php echo $this->_('Country');?>:</td>
	<td>
<?php
	$lang='';
	$country = "";
 	if ($sm_mode=="edit")
		$country = $user_details['country'];
	$arr = $this->getSupportedCountries();
	echo $this->arraySelect( $arr, 'sm_country', 'size="1"', $country );
?>
	</td>
</tr>
<tr>
	<td align="right">
		<?php echo $this->_('Access Level');?>:</td>
	<td>
		<?php
		if ($this->checkAccessLevel($this->getSuperUserLevel())) {
			$arr = $this->getRevAccessArray();
			echo $this->arraySelect( $arr, 'sm_access_level', 'size="1"', $this->getAccessLevelRounded($user_details['access_level']));
		} else {
			if ($this->getUserLoginID() == "") {
				$access_level = $this->getNewUserAccessLevel();
			} else
				$access_level = $user_details['access_level'];
			echo $this->getAccessLevel($access_level);
		}
		?>
	</td>
</tr>
<tr>
	<td align="right">
		<?php echo $this->_('Groups');?>:</td>
	<td>
		<?php
		$arr = $this->getGroups();
		if ($this->checkAccessLevel($this->getSuperUserLevel())) {
			echo $this->arraySelect2( $arr, 'sm_groups[]', 'size="1"', $user_groups);
		} else {
			if (isset($user_groups) && is_array($user_groups)) 
			{
				foreach ($user_groups as $group) {
					echo $arr[$group] . '<br />';
				}
			}
			else
			{
				echo $this->_("N/A");
			}
		}
		?>
	</td>
</tr>
<tr>
	<td align="center" colspan="2">
		<input type="button" name="sm_submit" value="<?php 
		if ($sm_mode=="edit") 
			echo $this->_('Update');
		else 
			echo $this->_('Register');
		?>" class="button" onclick="doSubmit()">
	<?php if ($sm_mode=="edit" && $this->checkAccessLevel($this->getSuperUserLevel())) {?>
		<input type="submit" name="sm_delete" value="<?php echo $this->_('Delete');?>" class="button">
	<?php } ?>
	</td>
</tr>
<input type="hidden" name="sm_submit_form" value="yes">
</form>
</table>