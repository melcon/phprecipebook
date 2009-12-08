<?php
// The listing of the users
if ($this->checkAccessLevel($this->getSuperUserLevel())) {
	
	// spit out a list of list names to choose from
	$counter = 0;
	$group_list = $this->getGroups();
?>

<table cellspacing="1" cellpadding="2" border=0 width="95%" class="data">
	<tr>
		<th><?php echo $this->_('Group Name');?></th>
		<th><?php echo $this->_('Delete');?></th>
	</tr>	
<?php
	foreach ($group_list as $key=>$value) {
?>
	<tr>
		<td>
			<a href="<?php echo $editURL . "&sm_mode=edit&sm_group=" . $key . "\">" . $value;?></a>
		</td>
		<td>
			<a href="<?php echo $deleteURL . "&sm_mode=edit&sm_submit_form=yes&sm_delete=yes&sm_group=" . $key . "\">";?>x</a>
		</td>
	</tr>
<?php
		$counter++;
	}
?>
	<tr>
	<td colspan=6 align=center>
		<I><?php echo $this->_('Group(s) Found') . ": " . $counter;?>
	</td>
</TABLE>
<P>
<?php echo '<a href="' . $editURL . '">'. $this->_('Create a new Group') . '</a>';

} else {
	// They are not an admin, so give them an error message
	echo $this->_('You do not have permission to edit groups') . "<br />";
}
?>
