<?php
$recipe_id = isValidID( $_REQUEST['recipe_id'] ) ? $_REQUEST['recipe_id'] : 0;
$recipe_format = isset( $_REQUEST['recipe_format'] ) ? $_REQUEST['recipe_format'] : 0;
$export_all = isset( $_REQUEST['export_all'] ) ? TRUE : FALSE;
$mode = isset( $_REQUEST['mode'] ) ? $_REQUEST['mode'] : '';

/*if (!$SMObj->checkAccessLevel("EDITOR")) {
	die($LangUI->_('You do not have sufficient privileges to export recipes'));
}*/

/*
	If we are not just printing out the data then initialize everything
*/
if ($mode != 'download') {
	require_once("classes/Export.class.php");
	require_once("classes/DBUtils.class.php");
?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Export Recipes');?></td>
</tr>
</table>
<p>

<?php
	if ($mode == '') {
		// Read in the list of options now
		$rc = DBUtils::fetchColumn( $db_table_recipes, 'recipe_name', 'recipe_id', 'recipe_name' );
		$arr2 = array(
				'XML' => $LangUI->_('PHPRecipeBook Format'),
				'RecipeML' => $LangUI->_('RecipeML Format')
		);
?>
<form action="./index.php?m=recipes&a=export" method="POST">
<table cellpadding="2">
	<tr>
		<td ALIGN=left><?php echo $LangUI->_('Recipe');?>: </td>
		<td>
			<?php echo $rc->getMenu2('recipe_id', $recipe_id, false );?>
		</td>
		</tr><tr>
		<td align=left><?php echo $LangUI->_('Format');?>: </td>
		<td>
			<?php echo DBUtils::arraySelect( $arr2, 'recipe_format', 'size="1"', '');?>
		</td>
		</tr><tr>
		<td colspan=2>
			<input type="checkbox" name="export_all" value="yes">
			<?php echo $LangUI->_('Export All Recipes');?>
		</td>
		</tr><tr>
		<td colspan=2>
			<input type=hidden name="mode" value="export">
			<input type=submit name="export" value="<?php echo $LangUI->_('Export');?>" class=button>
		</td>
	</tr>
</table>
</form>

<?php
	/*
		They have submitted the form, export and print the values
	*/
	} else if ($mode=="export") {
		if ($export_all) $id = 0;
		else $id = $recipe_id;
		if ($recipe_format=='XML') {
			include_once("classes/Export_XML.class.php");
			$exportObj = new Export_XML($recipe_format);
		}
		else if ($recipe_format=='RecipeML')
		{
			include_once("classes/Export_RecipeML.class.php");
			$exportObj = new Export_RecipeML($recipe_format);
		}
		$exportObj->getData($id);
		$data = $exportObj->exportData();
		echo $LangUI->_('Download Exported Data') . ': <a href="modules/recipes/export.php?mode=download">' . $LangUI->_('Output') . '</a><p>';
		echo '<b>' . $LangUI->_('Exported XML Data') . "</b><hr size=1 noshade><pre>";
		echo '<textarea width="100%" height="400px" style="width:600px;height:400px" name="code" wrap="logical" rows="21" cols="42">';
		echo html_entity_decode($data, ENT_QUOTES, $LangUI->getEncoding());
		echo '</textarea>';
		echo '</pre><br /><hr size="1" noshade>';
	}
} else {
	/*
		We are in download mode, so we just print out the contents of the session variable
	*/
	session_start(); // start up the session
	header("text/html");
	echo $_SESSION['export_data'];
}
?>



