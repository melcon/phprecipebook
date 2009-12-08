<?php
require_once("classes/Ingredient.class.php");
require_once("classes/DBUtils.class.php");

// Only editors or above can remove an ingredient
if (!$SMObj->checkAccessLevel("EDITOR")) {
	die($LangUI->_('You do not have sufficient privileges to delete ingredients'));
}

// Delete all selected ingredients
$iterator = 0;
$item_id = "ingredient_id_" . $iterator;
$item_delete = "ingredient_selected_" . $iterator;

while ($_REQUEST[$item_id] != '')
{
	// check to see if it is in the list
	if ($_REQUEST[$item_delete] == "yes" && isValidID($_REQUEST[$item_id]))
	{
		$ingObj = new Ingredient();
		$ingObj->setIngredient($_REQUEST[$item_id]); // Create an ingredient object for this ID
		$ingObj->delete();

		// In mysql cascading does not really work
		if ($g_rb_database_type=="mysql") {
			$sql = "DELETE from $db_table_ingredientmaps WHERE map_ingredient = " . $DB_LINK->addq($_REQUEST[$item_id], get_magic_quotes_gpc());
			$result = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, NULL, $sql);
		}
	}
	$iterator++;
	$item_id = "ingredient_id_" . $iterator;
	$item_delete = "ingredient_selected_" . $iterator;
}
?>
<P>
