<?php
require_once("classes/DBUtils.class.php");

$edit_table = isset( $_POST['edit_table'] ) ? $_POST['edit_table'] : '';
?>
<script language="JavaScript">
<!--
function addEntry()
{
	if (document.addForm.new_desc.value == "")
	{
		alert("<?php echo $LangUI->_("Please enter a value for your new entry");?>");
		document.addForm.new_desc.focus();
	}
	else
		document.addForm.submit();
}
// -->
</script>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Customize DropDowns');?></td>
</tr>
</table>
<P>

<?php
// The listing of the users
if ($SMObj->checkAccessLevel($SMObj->getSuperUserLevel())) {
?>
<form action="./index.php?m=admin&a=customize" method="POST">
<table  cellspacing="1" cellpadding="2" border="0" class="data">
	<tr>
		<td><?php echo $LangUI->_('Table to Customize');?>:</td>
		<td>
			<?php
			// Workaround to put the values in the dropdown
			$arr = array(
				$db_table_ethnicity => $LangUI->_('Ethnicity'),
				$db_table_bases => $LangUI->_('Bases'),
				$db_table_prep_time => $LangUI->_('Preparation Time'),
				$db_table_courses => $LangUI->_('Courses'),
				$db_table_difficulty => $LangUI->_('Difficulty'),
				$db_table_locations => $LangUI->_('Store Sections'),
				$db_table_prices => $LangUI->_('Restaurant Prices'),
				$db_table_meals => $LangUI->_('Meals'),
				$db_table_sources => $LangUI->_('Sources')
			);
			echo DBUtils::arraySelect( $arr, 'edit_table', 'size=1', $edit_table );
			?>
		</td>
		<td>
			<input type="submit" value="<?php echo $LangUI->_('Edit Table');?>" class="button">
		</td>
	</tr>
</table>
</form>

<?php
	if ($edit_table != "") {
		$counter=0;
		$sql = "SELECT * FROM " . $DB_LINK->addq($edit_table, get_magic_quotes_gpc()) . " ORDER BY " . $DB_LINK->addq($db_fields[$edit_table][0], get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute( $sql );
		DBUtils::checkResult($rc, NULL, NULL, $sql);
?>
<form action="./index.php?m=admin&a=customize&dosql=update_customize" method="POST">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="edit_table" value="<?php echo $edit_table;?>">
<table cellspacing="1" cellpadding="2" border="0" class="data">
	<th><?php echo $LangUI->_('Delete');?></th>
	<th><?php echo $LangUI->_('Description');?></th>

	<?php while (!$rc->EOF) { ?>
	<tr>
		<td>
			<input type="hidden" name="entry_<?php echo $counter;?>" value="<?php echo $rc->fields[0];?>">
			<input type="checkbox" name="delete_<?php echo $counter;?>" value="yes">
		</td>
		<td>
			<input type="textbox" size="40" name="desc_<?php echo $counter . '" value="' . $rc->fields[1];?>">
		</td>
	</tr>
			<?php if (count($db_fields[$edit_table]) == 3) { ?>
	<tr>
			<td></td>
			<td>
				<textarea cols="60" rows="15" name="text_<?php echo $counter;?>"><?php echo $rc->fields[2];?></textarea>
			</td>
	</tr>
	<?php
			}
			$rc->MoveNext();
			$counter++;
		}
?>
	<tr>
		<td colspan=3>
			<input type="hidden" name="total_entries" value="<?php echo $counter;?>">
			<input type="submit" value="<?php echo $LangUI->_('Update');?>" class="button">
		</td>
	</tr>
</table>
</form>

<P>
<form action="./index.php?m=admin&a=customize&dosql=update_customize" name="addForm" method="POST">
<table cellspacing="1" cellpadding="2" border="0" class="data">
	<tr>
		<td>
			<?php echo $LangUI->_('Create new entry');?>:
		</td>
		<td>
			<input type="hidden" name="edit_table" value="<?php echo $edit_table;?>">
			<input type="hidden" name="mode" value="add">
			<input type="textbox" name="new_desc">
		</td>
		<td colspan=2>
			<input type="button" value="<?php echo $LangUI->_('Add Entry');?>" onClick="Javascript:addEntry();" class="button">
		</td>
		</tr>
		<?php if (count($db_fields[$edit_table]) == 3) { ?>
		<tr>
			<td></td>
			<td colspan="2"><textarea cols="60" rows="15"  name="new_text"></textarea></td>
		</tr>
		<?php } ?>

</table>
</form>
<P>
<?php
	}
} else {
	// They are not an admin, so give them an error message
	echo $LangUI->_('You do not have permission to customize the database') . "<br />";
}
?>
