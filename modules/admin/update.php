<?php
// Exit if not admin
if (!$SMObj->checkAccessLevel($SMObj->getSuperUserLevel()))
	die($LangUI->_('You must have Administer privilages in order to customize the database!'));

$edit_table = isset( $_POST['edit_table'] ) ? $_POST['edit_table'] : '';
$mode = isset( $_POST['mode'] ) ? $_POST['mode'] : '';
$total_entries = isset( $_POST['total_entries'] ) ? $_POST['total_entries'] : 0;
$list_order = isset( $_POST['list_order'] ) ? $_POST['list_order'] : '';
$new_entry = isset( $_POST['new_entry'] ) ? $_POST['new_entry'] : '';

if ($mode == "add") {
	$sql = "INSERT INTO ".$DB_LINK->addq($edit_table, get_magic_quotes_gpc())." (" . $DB_LINK->addq($db_fields[$edit_table][1], get_magic_quotes_gpc()) . ") VALUES ('" . $DB_LINK->addq(htmlentities(trim($new_entry)), get_magic_quotes_gpc()) . "')";
	$rc = $DB_LINK->Execute( $sql );
	echo $LangUI->_('Entry Added') . "<br />";
} else {
	$error = false;
	for ($i=0; $i<$total_entries; $i++) {
		$entry_delete = "delete_".$i;
		$entry_id = "entry_".$i;
		$entry_desc = "desc_".$i;
		if ($_POST[$entry_delete] == "yes") {
			// then delete it from the database
			$sql = "DELETE FROM $edit_table WHERE " . $db_fields[$edit_table][0] . "=" . $_POST[$entry_id];
			$result = $DB_LINK->Execute($sql);
			if (!$result) {
				$error=true;
				echo '<font color=red>'. $DB_LINK->ErrorMsg().'</font><p>';
			}
		} else {
			// update the entry to the new value
			$sql = "UPDATE ".$DB_LINK->addq($edit_table, get_magic_quotes_gpc())." SET " . $DB_LINK->addq($db_fields[$edit_table][1], get_magic_quotes_gpc()) . "='" .
						$DB_LINK->addq(htmlentities(trim($_POST[$entry_desc])), get_magic_quotes_gpc()) . "'";

			$sql .= " WHERE " . $DB_LINK->addq($db_fields[$edit_table][0], get_magic_quotes_gpc()) . "=" . $DB_LINK->addq($_POST[$entry_id], get_magic_quotes_gpc());
			$result = $DB_LINK->Execute($sql);
			if (!$result) {
				$error=true;
				echo '<font color=red>'. $DB_LINK->ErrorMsg().'</font><p>';
			}
		}
	}
	// If we are dealing with recipe_types, then update settings_list_order as well (the shopping list order)
	if ($edit_table==$db_table_types) {
			$sql = "UPDATE $db_table_settings SET setting_list_order='".$DB_LINK->addq($list_order, get_magic_quotes_gpc())."'";
			$result = $DB_LINK->Execute($sql);
			if (!$result) {
				$error=true;
				echo '<font color=red>'. $DB_LINK->ErrorMsg().'</font><p>';
			}
	}
	if (!$error)
		echo $LangUI->_('Table Updated') . "<br />";
}
?>