<?php
require_once("classes/Recipe.class.php");
require_once("classes/DBUtils.class.php");

// Determine if the user has access to add new recipes, or edit this current one
if (!$SMObj->checkAccessLevel("AUTHOR")) {
	die($LangUI->_('You do not have sufficient privileges to delete recipes'));
}

// Proceed to the next step
$iterator = 0;
$item_id = "recipe_id_" . $iterator;
$item_delete = "recipe_selected_" . $iterator;

while (isset($_REQUEST[$item_id]))
{
	// check to see if it is in the list
	if (isset($_REQUEST[$item_delete]) && $_REQUEST[$item_delete] == "yes")
	{
		$recipe_id = isValidID( $_REQUEST[$item_id] ) ? $_REQUEST[$item_id] : 0;

		// Figure out who the owner of this recipe is, Editors can edit anyones recipes
		// The owner of a recipe does not change when someone edits it.
		if (!$SMObj->checkAccessLevel("EDITOR"))
		{
			$sql = "SELECT recipe_owner FROM $db_table_recipes WHERE recipe_id = " . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
			$rc = $DB_LINK->Execute($sql);
			// If the recipe is owned by someone else then do not allow editing
			if ($rc->fields['recipe_owner'] != "" && $rc->fields['recipe_owner'] != $SMObj->getUserLoginID())
				die($LangUI->_('You do not have sufficient privileges to delete this recipe!'));
		}

		/* Go ahead and do the delete */
		// clean up the old picture if we are suppose to
		if ($g_rb_database_type=="postgres") {
			$sql = "SELECT recipe_picture FROM $db_table_recipes WHERE recipe_id=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
			$rc = $DB_LINK->Execute($sql);
			if (trim($rc->fields['recipe_picture']) != "") {
				$rc = $DB_LINK->BlobDelete($rc->fields['recipe_picture']);
				DBUtils::checkResult($rc, $LangUI->_('Picture successfully deleted'), NULL, $sql);
			}
		}

		// In Postgres everything will be cleaned up with one delete
		$RecipeObj = new Recipe($recipe_id);
		$RecipeObj->delete();
	}
	$iterator++;
	$item_id = "recipe_id_" . $iterator;
	$item_delete = "recipe_selected_" . $iterator;

}
?>
<I><?php echo $LangUI->_('Recipe Deleted');?></I>
<P>
