<?php
// The listing of the users
if ($this->checkAccessLevel($this->getSuperUserLevel())) {
	
	// spit out a list of list names to choose from
	$counter = 0;
	$user_list = $this->getUsers();
?>

<table cellspacing="1" cellpadding="2" border=0 width="95%" class="data">
	<tr>
		<th><?php echo $this->_('User Name');?></th>
		<th><?php echo $this->_('Login ID');?></th>
		<th><?php echo $this->_('Date Created');?></th>
		<th><?php echo $this->_('Last Logged In');?></th>
		<th><?php echo $this->_('Language');?></th>
		<th><?php echo $this->_('Access Level');?></th>
	</tr>	
<?php
	foreach ($user_list as $key=>$details) {
?>
	<tr>
		<td>
			<a href="<?php echo $editURL . "&sm_mode=edit&sm_login=" . $details['login'] . "\">" . $details['name'];?></a>
		</td>
		<td>
			<?php echo $details['login'];?>
		</td>
		<td>
			<?php echo $details['date_created'];?>
		</td>
		<td>
			<?php echo $details['last_login'];?>
		</td>
		<td>
			<?php echo $details['language'];?>
		</td>
		<td>
			<?php echo $this->getAccessLevel($details['access_level']);?>
		</td>
	</tr>
<?php
		$counter++;
	}
?>
	<tr>
	<td colspan=6 align=center>
		<I><?php echo $this->_('User(s) Found') . ": " . $counter;?>
	</td>
</TABLE>
<P>
<?php echo '<a href="' . $editURL . '">'. $this->_('Register a new user') . '</a>';

} else {
	// They are not an admin, so give them an error message
	echo $this->_('You do not have permission to edit users') . "<br />";
}
?>
