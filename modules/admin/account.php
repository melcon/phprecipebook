<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Account Settings');?></td>
</tr>
</table>
<P>
<?php
	$SMObj->getUserAddEditFormSubmit();
	if (!isset($_REQUEST['sm_submit_form'])) // only show the form if they have not submitted
		$SMObj->getUserAddEditForm();
?>