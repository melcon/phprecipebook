<?php
$sm_mode = isset ($_REQUEST['sm_mode'] ) ? $_REQUEST['sm_mode'] : "new";
$sm_group = isset ($_REQUEST['sm_group'] ) ? $_REQUEST['sm_group'] : "";

if ($sm_mode=="edit") {
	$group_desc = $this->getGroupDetails($sm_group);
	$group_members = $this->getGroupMembers($sm_group);
}

$list = $this->getUsers();
$user_list=array();
foreach ($list as $key=>$value) {
	$user_list[$key] = "$key - " . $value['name'];
}

?>
<script language="javascript">
function doSubmit() {
	f = document.UMNewGroupFrm;
	msg = '';

	if (f.sm_name.value.length < 3) {
		msg += '<?php echo $this->_('Please enter a valid group name');?>.\n';
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
				echo $this->_('Edit Group');
			else 
				echo $this->_('New Group');
			?></td>
</tr>
</table>
<br />
<table cellspacing="0" cellpadding="3" border="0" class="std">
<form name="UMNewGroupFrm" action="<?php echo $submitURL;?>" method="POST">
<input type=hidden name="sm_mode" value="<?php echo $sm_mode;?>">

<tr>
	<td align="right"><?php echo $this->_('Group Name');?>:</td>
	<td><input type="text" name="sm_name" value="<?php echo $group_desc;?>"></td>
</tr>
<tr>
	<td align="right">
		<?php echo $this->_('Members');?>:</td>
	<td>
		<?php
			echo $this->arraySelect2( $user_list, 'sm_members[]', 'size="1"', $group_members);
		?>
	</td>
</tr>
<tr>
	<td align="center" colspan="2">
		<input type="button" name="sm_submit" value="<?php 
		if ($sm_mode=="edit") 
			echo $this->_('Update');
		else 
			echo $this->_('Create');
		?>" class="button" onclick="doSubmit()">
	<?php if ($sm_mode=="edit" && $this->checkAccessLevel($this->getSuperUserLevel())) {?>
		<input type="submit" name="sm_delete" value="<?php echo $this->_('Delete');?>" class="button">
	<?php } ?>
	</td>
</tr>
<input type="hidden" name="sm_submit_form" value="yes">
</form>
</table>