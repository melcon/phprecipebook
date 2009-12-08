<?php
    	if (empty($_GET['recipe_id'])) exit;
    	elseif ($_GET['recipe_id'] < 1) exit;
	elseif (phpversion() >= '5.2.0' && !filter_var($_GET['recipe_id'], FILTER_VALIDATE_INT)) exit;
   	elseif (!is_numeric($_GET['recipe_id'])) exit;
    	else $recipe_id = $_GET['recipe_id'];

	require_once("../../includes/config_inc.php");
	require_once("../../custom_inc.php");
	include_once('../../libs/adodb/adodb.inc.php');

	$DB_LINK = ADONewConnection($g_rb_database_type);
	$DB_LINK->debug = FALSE; //debugging will ruin the headers for the image
	$DB_LINK->Connect($g_rb_database_host, $g_rb_database_user, $g_rb_database_password, $g_rb_database_name);

	$sql = "SELECT recipe_picture,recipe_picture_type FROM $db_table_recipes WHERE recipe_id=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute($sql);
	Header("Content-type: " . $rc->fields['recipe_picture_type']);
	if ($g_rb_database_type=="postgres") {
		echo $DB_LINK->BlobDecode($rc->fields['recipe_picture']);
	} else {
		echo $rc->fields['recipe_picture'];
	}
?>
