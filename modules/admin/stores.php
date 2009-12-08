<!-- Navigation section -->
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title">
            <?php echo $LangUI->_('Edit Stores'); ?>
        </td>
</tr>
</table>
<P>
<!--  Edit/Delete an existing store -->
<?php
	$sql = "SELECT store_id,store_name FROM $db_table_stores WHERE store_owner='".$SMObj->getUserLoginID() . "'";
	$rc = $DB_LINK->Execute($sql);
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	$iterator=0;
?>
<form action="./index.php?m=admin&a=stores&dosql=update_stores" method="POST">
<input type="hidden" name="mode" value="update">
<table cellspacing="1" cellpadding="2" border="0" class="data">
	<th><?php echo $LangUI->_('Delete');?></th>
	<th><?php echo $LangUI->_('Name');?></th>
	<th colspan="2" width="80"><?php echo $LangUI->_('Layout');?></th>
	<?php while (!$rc->EOF) { ?>
	<tr>
		<td>
			<input type="checkbox" name="delete_store_<?php echo $iterator;?>" value="yes">
		</td>
		<td>
			<input type="hidden" name="store_id_<?php echo $iterator . '" value="' . $rc->fields['store_id'];?>">
			<input type="text" name="store_name_<?php echo $iterator . '" value="' . $rc->fields['store_name'];?>">
		</td>
		<td align="center">
			<a href="index.php?m=admin&a=edit_layout&store_id=<?php echo $rc->fields['store_id'];?>">Edit</a>
		</td>
		<td align="center">
			<a href="index.php?m=admin&a=show_layout&store_id=<?php echo $rc->fields['store_id'];?>">Show</a>
		</td>
	</tr>
	<?php
		$rc->MoveNext();
		$iterator++;
}
?>
	<tr>	
		<td colspan="4">
			<input type="hidden" name="total_entries" value="<?php echo $iterator;?>">
			<input type="submit" value="<?php echo $LangUI->_('Update');?>" class="button">
		</td>
	</tr>
</table>
</form>
<!-- Add a new Store ---->
<P>
<form action="./index.php?m=admin&a=stores&dosql=update_stores" method="POST">
<table cellspacing="1" cellpadding="2" border="0" class="data">
	<tr>
		<td>
			<?php echo $LangUI->_('Create new store');?>:
		</td>
		<td>
			<input type="hidden" name="mode" value="add">
			<input type="text" name="store_name">
		</td>
		<td colspan=2>
			<input type="submit" value="<?php echo $LangUI->_('Add Entry');?>" class="button">
		</td>
	</tr>
</table>
</form>
