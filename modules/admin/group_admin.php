<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Administer Groups');?></td>
</tr>
</table>
<P>

<?php
// The listing of the users
if ($SMObj->checkAccessLevel($SMObj->getSuperUserLevel())) {
	$SMObj->getGroupAdminForm("./index.php?m=admin&a=groups","./index.php?m=admin&a=groups");
} else {
	// They are not an admin, so give them an error message
	echo $LangUI->_('You do not have permission to edit users') . "<br />";
}
?>
