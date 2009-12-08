<?php
require_once ("classes/ShoppingList.class.php");

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title">
            <?php echo $LangUI->_('Saved Shopping Lists'); ?>
        </td>
</tr>
</table>
<p>
<?php
if ($mode == "delete") {
	// Drop the old values
	$listObj = new ShoppingList($_GET["list_id"]);
	$listObj->deleteShoppingList();
	echo $LangUI->_('List Deleted.') . "<p>";
} else if ($mode=="edit") {
?>
<!-- Edit form -->
<form name="editFrom" action="index.php?m=lists&amp;a=saved" method="post">
<input type="hidden" name="mode" value="edit_confirm">
<input type="hidden" name="list_id" value="<?php echo $_GET["list_id"];?>">
<?php echo $LangUI->_('List Name');?>: <input type="text" name="list_name" value="<?php echo htmlentities($_GET["list_name"], ENT_QUOTES);?>">
<input type="submit" value="<?php echo $LangUI->_('Update');?>">
</form>
<!-- End of Edit form -->
<?php
} else if ($mode == "edit_confirm") {
	$listObj = new ShoppingList(htmlentities($_POST["list_id"], ENT_QUOTES), htmlentities($_POST["list_name"], ENT_QUOTES));
	$listObj->updateListName();
	echo $LangUI->_('List Name Updated.') . "<br />";
}

if ($mode != "edit") {
	// spit out a list of list names to choose from
	$counter = 0;
	$sql = "SELECT * FROM $db_table_list_names WHERE list_owner='" . $SMObj->getUserLoginID() . "'";
	$rc = $DB_LINK->Execute($sql);
	
	if ($rc->RecordCount()>0) {
?>

<table cellspacing="1" cellpadding="2" border=0 width="95%" class="data">
	<tr>
		<th><?php echo $LangUI->_('List Name');?></th>
		<th colspan="4" align=center><?php echo $LangUI->_('Options');?></th>
	</tr>	
<?php
	while (!$rc->EOF) {
?>
	<tr>
		<td>
			<a href="./index.php?m=lists&amp;a=current&mode=load&list_id=<?php echo $rc->fields['list_id'] . "\">" . $rc->fields['list_name'];?></a>
		</td>
		<td>
			<a href="./index.php?m=lists&amp;a=saved&mode=edit&list_id=<?php echo $rc->fields['list_id'] . "&list_name=" . $rc->fields['list_name'] . "\">" . $LangUI->_('Edit');?></a>
		</td>
		<td>
			<a href="./index.php?m=lists&amp;a=current&mode=save_update&list_id=<?php echo $rc->fields['list_id'] . "\">" . $LangUI->_('Save to...');?></a>
		</td>
		<td>
			<a href="./index.php?m=lists&amp;a=saved&mode=delete&list_id=<?php echo $rc->fields['list_id'] . "\">" . $LangUI->_('Delete');?></a>
		</td>
	</tr>
<?php
		$counter++;
		$rc->MoveNext();
	}
?>
	<tr>
	<td colspan=6 align=center>
		<I><?php echo $LangUI->_('Saved List(s) Found') . ": " . $counter;?>
	</td>
</TABLE>
<?php
	} else
		echo $LangUI->_('No lists currently saved');
}
?>
