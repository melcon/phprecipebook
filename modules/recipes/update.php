<?php
require_once("classes/Recipe.class.php");
require_once("classes/Ingredient.class.php");
require_once("classes/DBUtils.class.php");

$recipe_id = isValidID( $_GET['recipe_id'] ) ? $_GET['recipe_id'] : 0;
$recipe_name = isset( $_POST['recipe_name'] ) ?
	htmlspecialchars( stripslashes( $_POST['recipe_name'] ), ENT_QUOTES, $LangUI->getEncoding() ) : '';
$recipe_ethnic = isset( $_POST['recipe_ethnic'] ) ? $_POST['recipe_ethnic'] : 0;
$recipe_base = isValidID( $_POST['recipe_base'] ) ? $_POST['recipe_base'] : 0;
$recipe_course = isValidID( $_POST['recipe_course'] ) ? $_POST['recipe_course'] : 0;
$recipe_prep_time = isValidID( $_POST['recipe_prep_time'] ) ? $_POST['recipe_prep_time'] : 0;
$recipe_difficulty = isValidID( $_POST['recipe_difficulty'] ) ? $_POST['recipe_difficulty'] : 0;
$recipe_directions = isset( $_POST['recipe_directions'] ) ?
	htmlspecialchars( stripslashes( $_POST['recipe_directions'] ), ENT_QUOTES, $LangUI->getEncoding() ) : '';
$recipe_comments = isset( $_POST['recipe_comments'] ) ?
	htmlspecialchars( stripslashes( $_POST['recipe_comments'] ), ENT_QUOTES, $LangUI->getEncoding() ) : '';
$recipe_source = isset( $_POST['recipe_source'] ) ? $_POST['recipe_source'] : '';
$recipe_source_desc = isset( $_POST['recipe_source_desc'] ) ?
	htmlspecialchars( stripslashes( $_POST['recipe_source_desc'] ), ENT_QUOTES, $LangUI->getEncoding() ) : '';
$recipe_serving_size = ($_POST['recipe_serving_size'] != "" ) ? $_POST['recipe_serving_size'] : 'NULL';
$recipe_cost = 0;
$recipe_private = isset($_POST['private']) ? 'TRUE' : 'FALSE';
$recipe_picture_oid = isset($_POST['recipe_picture_oid']) ? $_POST['recipe_picture_oid'] : 'NULL'; // to keep postgres clean
$recipe_picture_type = isset ($_FILES['recipe_picture']['type']) ? $_FILES['recipe_picture']['type'] : '';
$remove_picture = isset ($_POST['remove_picture']) ? $_POST['remove_picture'] : '';
$recipe_owner = isset ($_POST['recipe_owner']) ? $_POST['recipe_owner'] : $SMObj->getUserLoginID();
$total_related = isValidID($_POST['total_related']) ? $_POST['total_related'] : 0;

/*
	Make sure they are not trying to bypass the security
*/
if (!$SMObj->checkAccessLevel("AUTHOR")) {
	die($LangUI->_('You do not have sufficient privileges to add/edit recipes'));
} else if ($recipe_id && !$SMObj->checkAccessLevel("EDITOR")) {
	// Figure out who the owner of this recipe is, Editors can edit anyones recipes
	// The owner of a recipe does not change when someone edits it.
	$sql = "SELECT recipe_owner FROM $db_table_recipes WHERE recipe_id = " . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute($sql);
	// If the recipe is owned by someone else then do not allow editing
	if ($rc->fields['recipe_owner'] != "" && $rc->fields['recipe_owner'] != $SMObj->getUserLoginID())
		die($LangUI->_('You are not the owner of this recipe, you are not allowed to edit it'));
}

/*
	Load all the ingredients so we can compute the price of this recipe
*/
$count = 0;
$ingArray = array();

// Check for dupes first, they should have only entered one of each ingredient
for ($i=0; $i<$_POST['total_ingredients']; $i++)
{
	if (isset($_POST['ingredient_id_'.$i]) && $_POST['ingredient_quantity_'.$i] > 0)
	{
		$value = $_POST['ingredient_id_'.$i];
		$count = 0;
		for ($j=0; $j<$_POST['total_ingredients']; $j++)
		{
			if (isset($_POST['ingredient_id_'.$j]) && $_POST['ingredient_quantity_'.$j] > 0)
			{
				if ($value == $_POST['ingredient_id_'.$j])
					$count++;
			}
		}
		if ($count > 1)
		{
			die($LangUI->_("Ingredients cannot be entered more then once per recipe.  Please combine the quantities or create a new recipe and list it as required recipe. Go back to fix the problem."));
		}
	}
}

for ($i=0; $i<$_POST['total_ingredients']; $i++)
{
	// If user set the order then read it
	if (isset($_POST['show_ingredient_order']))	$order = $_POST['ingredient_order_'.$i];
	else $order = $count;
	// Set if the ingredient is optional or not
	if (isset($_POST['ingredient_optional_'.$i])) $optional = "TRUE";
	else $optional="FALSE";

	// Now add the updated/new recipes in.
	if (!isset($_POST['ingredient_delete_'.$i]) && isset($_POST['ingredient_id_'.$i]) &&
		 ($_POST['ingredient_quantity_'.$i] > 0) && isset($_POST['ingredient_unit_'.$i])) {
		    $ingObj = new Ingredient();
			$ingObj->setIngredientMap($_POST['ingredient_id_'.$i],
			 							$recipe_id,
										$_POST['ingredient_qualifier_'.$i],
										$_POST['ingredient_quantity_'.$i],
										$_POST['ingredient_unit_'.$i],
										$optional,
										$order);
			$count++; // keep track of which number we are only (for ordering)
			// Only add price information for required ingredients
			if (!isset($_POST['ingredient_optional_'.$i])) {
				if ($g_rb_debug) echo "$i ingredient costs " . $ingObj->computePrice() . "<br />";
				$recipe_cost += $ingObj->computePrice(); // Add the cost of this item to the total cost
			}
			$ingArray[] = $ingObj; //Add the object to the list
	}
}

//	Add the cost of the required related recipes in as well
for ($i=0; $i < $total_related; $i++) {
	if (isset($_POST['related_req_'.$i])) {
		$sql = "SELECT recipe_cost FROM $db_table_recipes WHERE recipe_id=".$_POST['related_id_'.$i];
		$rc = $DB_LINK->Execute($sql);
		if ($rc->fields['recipe_cost'] > 0)
			$recipe_cost += $rc->fields['recipe_cost'];
	}
}

/*
	Handle adding and editing of recipes
*/
$recipeObj = new Recipe($recipe_id,
						$recipe_name,
						$recipe_ethnic,
						$recipe_base,
						$recipe_course,
						$recipe_prep_time,
						$recipe_difficulty,
						$recipe_directions,
						$recipe_comments,
						$recipe_serving_size,
						$recipe_source,
						$recipe_source_desc,
						$recipe_cost,
						$recipe_owner,
						$recipe_private,
						$_FILES['recipe_picture'],
						$recipe_picture_type,
						$recipe_picture_oid);
// Add or update the recipe
$recipeObj->addUpdate();
// Handle the picture
if ($remove_picture=="yes") {
	$recipeObj->deletePicture();
} else {
	$recipeObj->updatePicture();
}

if ($recipe_id) {
	// Clear out the old ingredients, this could be done by an update if desired.
	$sql = "DELETE FROM $db_table_ingredientmaps WHERE map_recipe=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$result = $DB_LINK->Execute($sql);
	// Also clear out the related_recipes
	$sql = "DELETE FROM $db_table_related_recipes WHERE related_parent=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$result = $DB_LINK->Execute($sql);
}

$recipe_id = $recipeObj->getID();

/*
	Add the ingredients into the database. The order field is needed because mysql does not consistently put them in or retrieve them
		in a specific order.
*/
foreach ($ingArray as $ing) {
	$ing->setID($recipe_id);
	$ing->insertMap();
}

// Add all the related recipes in.
for ($i=0; $i<$_POST["total_related"]; $i++) {
	if (isset($_POST['related_req_'.$i]))
		$required = $DB_LINK->true;
	else
		$required = $DB_LINK->false;

	// If the user wishes to delete the related recipe, they just select the null drop down
	if ($_POST['related_id_'.$i] && !isset($_POST['related_delete_'.$i])) {
		$sql="INSERT INTO $db_table_related_recipes (related_parent, related_child, related_required) VALUES (" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc()) . ", " .
			$DB_LINK->addq($_POST['related_id_'.$i], get_magic_quotes_gpc()) . ", '" . $required . "')";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
	}
}
?>

