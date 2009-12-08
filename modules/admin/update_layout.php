<?php
require_once("classes/DBUtils.class.php");

if (!$SMObj->getUserLoginID())
	die($LangUI->_('You must be logged in to edit layouts!'));


$store_id = isValidID( $_POST['store_id'] ) ? $_POST['store_id'] : 0;

// Update
$iterator=0;
$layout = "";
$this_section = "section_id_" . $iterator;
while (isset($_POST[$this_section])) {
	if ($_POST[$this_section] != 0)
	{
		$layout .= $_POST[$this_section] . ",";
	}
	$iterator++;
	$this_section = "section_id_" . $iterator;
}

// remove the trailing comma
$layout = trim($layout, ",");

// update the entry to the new value
$sql = "UPDATE $db_table_stores SET store_layout='$layout' WHERE store_id=" .
		$DB_LINK->addq($store_id, get_magic_quotes_gpc()) . " AND store_owner='" . $SMObj->getUserLoginID() . "'";
$rc = $DB_LINK->Execute($sql);
DBUtils::checkResult($rc, NULL, NULL, $sql);


echo $LangUI->_('Layout Updated') . "<br />\n";

?>