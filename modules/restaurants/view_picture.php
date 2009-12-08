<?php
        if (empty($GET['restaurant_id'])) exit;
        elseif ($GET['restaurant_id'] < 1) exit;
        elseif (phpversion() >= '5.2.0' && !filter_var($GET['restaurant_id'], FILTER_VALIDATE_INT)) exit;
        elseif (!is_numeric($GET['restaurant_id'])) exit;
    	else $restaurant_id = $_GET['restaurant_id'];


	require_once("../../includes/config_inc.php");
	require_once("../../custom_inc.php");
	include_once('../../libs/adodb/adodb.inc.php');

	$DB_LINK = ADONewConnection($g_rb_database_type);
	$DB_LINK->debug = FALSE; //debugging will ruin the headers for the image
	$DB_LINK->Connect($g_rb_database_host, $g_rb_database_user, $g_rb_database_password, $g_rb_database_name);

	$sql = "SELECT restaurant_picture,restaurant_picture_type FROM $db_table_restaurants WHERE restaurant_id=" . $DB_LINK->addq($restaurant_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute($sql);
	Header("Content-type: " . $rc->fields['restaurant_picture_type']);
	if ($g_rb_database_type=="postgres") {
		echo $DB_LINK->BlobDecode($rc->fields['restaurant_picture']);
	} else {
		echo $rc->fields['restaurant_picture'];
	}
?>
