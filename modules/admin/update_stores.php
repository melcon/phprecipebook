<?php
require_once("classes/DBUtils.class.php");

if (!$SMObj->getUserLoginID())
	die($LangUI->_('You must be logged in to edit/create stores!'));

$mode = isset( $_POST['mode'] ) ? $_POST['mode'] : '';
$total_entries = isset( $_POST['total_entries'] ) ? htmlentities($_POST['total_entries'], ENT_QUOTES) : 0;

if ($mode == "add") {
	// Add
	$store_name = htmlentities($_POST['store_name'], ENT_QUOTES);
	$store_owner = $SMObj->getUserLoginID();
	$sql = "INSERT INTO $db_table_stores (store_name, store_layout, store_owner) VALUES ('".$DB_LINK->addq($store_name, get_magic_quotes_gpc())."', '', '".$DB_LINK->addq($store_owner, get_magic_quotes_gpc())."')";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	echo $LangUI->_('Store Added') . "<br />";
} else {
	// Update
	$iterator=0;
	$store_name = "store_name_";
	$store_id = "store_id_";
	$this_store_id = $store_id . $iterator;
	$this_store_name = $store_name . $iterator;
	while (isset($_POST[$this_store_name])) {
		$store_delete = "delete_store_".$iterator;
		if (isset($_POST[$store_delete])) {
			// then delete it from the database
			$sql = "DELETE FROM $db_table_stores WHERE store_id='" . $DB_LINK->addq($_POST[$this_store_id], get_magic_quotes_gpc()) . "' AND store_owner='" . $SMObj->getUserLoginID() . "'";
			$rc = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, NULL, $sql);
		} else {
			// update the entry to the new value
			$sql = "UPDATE $db_table_stores SET
							store_name='" . $DB_LINK->addq(htmlentities($_POST[$this_store_name], ENT_QUOTES), get_magic_quotes_gpc()) .
					"' WHERE store_id='" . $DB_LINK->addq($_POST[$this_store_id], get_magic_quotes_gpc()) . "' AND store_owner='" . $SMObj->getUserLoginID() . "'";
			$rc = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, NULL, $sql);
		}
		// increment to the next item
		$iterator++;
		$this_store_name = $store_name . $iterator;
		$this_store_id = $store_id . $iterator;
	}
	echo $LangUI->_('Store Updated') . "<br />\n";
}
?>