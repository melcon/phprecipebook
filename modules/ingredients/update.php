<?php
require_once("classes/Ingredient.class.php");

$ingredient_id = isValidID( $_GET['ingredient_id'] ) ? $_GET['ingredient_id'] : 0;
$ingredient_name = isset( $_POST['ingredient_name'] ) ?
	htmlentities( stripslashes( $_POST['ingredient_name'] ), ENT_QUOTES, $LangUI->getEncoding() ) : '';
$ingredient_desc = isset( $_POST['ingredient_desc'] ) ?
	htmlentities( stripslashes( $_POST['ingredient_desc'] ), ENT_QUOTES, $LangUI->getEncoding()) : '';
$ingredient_loc = ($_POST['ingredient_loc'] != "") ? $_POST['ingredient_loc'] : 'NULL';
$ingredient_price = ($_POST['ingredient_price'] != "") ? $_POST['ingredient_price'] : '0.00';
$ingredient_unit = ($_POST['ingredient_unit'] != "") ? $_POST['ingredient_unit'] : 'NULL';
$ingredient_solid = ($_POST['ingredient_solid'] == "TRUE") ? "TRUE" : "FALSE";

/*
	Make sure they are not trying to bypass the security
*/
if (!$SMObj->checkAccessLevel("AUTHOR")) {
	die($LangUI->_('You do not have sufficient privileges to add/edit ingredients'));
}

// Load the Ingredient into an ingredient object
$ingObj = new Ingredient();
$ingObj->setIngredient($ingredient_id,
					   $ingredient_name,
					   $ingredient_desc,
					   $ingredient_price,
					   $ingredient_unit,
					   $ingredient_loc,
					   $ingredient_solid);

// Add or Update the ingredient in the database
$ingObj->addUpdate();

?>

