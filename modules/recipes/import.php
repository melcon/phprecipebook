<?php
require_once("classes/DBUtils.class.php");
require_once("classes/Import.class.php");

$recipe_format = isset( $_REQUEST['recipe_format'] ) ? $_REQUEST['recipe_format'] : '';
$mode = isset( $_REQUEST['mode'] ) ? $_REQUEST['mode'] : '';

if (!$SMObj->checkAccessLevel("EDITOR")) {
	die($LangUI->_('You do not have sufficient privileges to import recipes'));
}

$arr = array(
			'XML' => $LangUI->_('PHPRecipeBook Format (XML)'),
			'MM' => $LangUI->_('Meal Master')
		);
?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Import Recipes');?></td>
</tr>
</table>
<p>

<?php if ($mode == "") { ?>

<form action="index.php?m=recipes&a=import&mode=import" enctype="multipart/form-data" method="POST">
<table cellpadding=2>
	<tr>
		<td ALIGN=left><?php echo $LangUI->_('File');?>: </td>
		<td>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $g_rb_max_picture_size;?>">
			<input type="file" name="import_file">
		</td>
		</tr><tr>
		<td align=left><?php echo $LangUI->_('Format');?>: </td>
		<td>
			<?php echo DBUtils::arraySelect( $arr, 'recipe_format', 'size="1"', @$recipe_format );?>
		</td>
		</tr>
		<tr>
		<td colspan=2>
			<input type=submit name="import" value="<?php echo $LangUI->_('Import');?>" class=button>
		</td>
	</tr>
</table>
</form>


<?php
} else if ($mode == "import") {
	// Determine if the user has access to add new recipes, or edit this current one
	if (!$SMObj->checkAccessLevel("AUTHOR")) {
		die($LangUI->_('You do not have sufficient privileges to add/edit recipes'));
	}

	// confirm that we have an uploaded file
	if (is_uploaded_file($_FILES['import_file']['tmp_name'])) {
		$file = $_FILES['import_file']['tmp_name'];
	} else {
		echo $LangUI->_('Upload of file failed') . "<br />";
	}

	// Load the class and create the object
	if ($recipe_format == 'XML') {
		include_once("classes/Import_XML.class.php");
		$importObj = new Import_XML($recipe_format);
	} else if ($recipe_format == 'MM') {
		include_once("classes/Import_MM.class.php");
		$importObj = new Import_MM($recipe_format);
	}

	// Parse the file and import the data
	echo $LangUI->_('Starting import...') . "<br />";
	$importObj->parseDataFile($file);
	// Check for duplicates
	while (list($key, $val) = each($importObj->importRecipes)) {
		if (recipe_name_exists($val[0]->name)) {
			print "<font color=red>NOT importing ".$val[0]->name." since it already exists. sorry...</font><br />\n";
			unset($importObj->importRecipes[$key]);
		}
    }
    reset($importObj);
	$importObj->importData();
	echo $LangUI->_('Importing complete') . "<br />";
}

/*
	Check to see if a recipe name already exists
*/
function recipe_name_exists($recipe_name) {
    global $DB_LINK, $db_table_recipes;

    $SQL = "SELECT recipe_name from $db_table_recipes where recipe_name = '".$DB_LINK->addq($recipe_name, get_magic_quotes_gpc())."'";
    $rc = $DB_LINK->Execute($SQL);
    DBUtils::checkResult($rc, NULL, NULL, $SQL);
    if ($rc->RecordCount())
      return true;
    return false;
}
?>
