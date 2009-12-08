<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Add/Edit Groups');?></td>
</tr>
</table>
<P>
<br />
<?
	$SMObj->getGroupAddEditFormSubmit();
	if ($_REQUEST['sm_submit_form'] != "yes") // only show the form if they have not submitted
		$SMObj->getGroupAddEditForm();
?>