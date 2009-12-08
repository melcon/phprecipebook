<?php
require_once("classes/Recipe.class.php");
require_once("classes/Ingredient.class.php");
require_once("classes/DBUtils.class.php");
?>
<script>
	function checkAll(val) {
		var al=document.shoppingList;
		var len = al.elements.length;
		var j=0
		var i=0;
		var k=0;
		for( i=0 ; i<len ; i++) {
			var rid = 'delete_recipe_' + j;
			var gid = 'delete_ingredient_' + k;
			if (al.elements[i].name==rid) {
				al.elements[i].checked=val;
				j++;
			} else if (al.elements[i].name==gid) {
				al.elements[i].checked=val;
				k++;
			}
		}
	}
</script>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title">
            <?php echo $LangUI->_('Shopping List'); ?>
        </td>
</tr>
<tr>
        <td class="nav" align="left">
                <a href="./index.php?m=lists&amp;a=print&mode=print"><?php echo $LangUI->_('Print Shopping List'); ?></a>
        </td>
</tr>
</table>
<P>
<?php
if (!isset($_SESSION['shoppinglist'])) {
	// The initialization of a new Session.
	$_SESSION['shoppinglist'] = new ShoppingList();
}

//Read the shopping list from the session
$listObj = $_SESSION['shoppinglist'];

// Set the mode
$mode = isset($_REQUEST['refresh']) ? "refresh" : (isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '');

// Add items to the Shopping List
if ($mode == "add") {
	$iterator = 0;
	// determine what type of item we are dealing with
	if (isset($_REQUEST['recipe_id_0'])) $itemType = 'recipe';
	else $itemType = 'ingredient';
	
	// First item
	$item_id = $itemType . "_id_" . $iterator;
	$item_scale = $itemType . "_scale_" . $iterator;
	$item_name = $itemType . "_name_" . $iterator; // The name if we got it
	$item_add = $itemType . "_selected_" . $iterator;
	
	while (isset($_REQUEST[$item_id])) 
	{
		// check to see if it is in the list
		if ($_REQUEST[$item_add] != NULL) 
		{
			if ($itemType == 'recipe') 
			{
				// Adding a recipe again increments the quantity
				$recipeObj = new Recipe($_REQUEST[$item_id]);
				$recipeObj->loadRecipe(); //load the recipe info into memory
				// Add the recipe
				$listObj->addRecipe($recipeObj, $_REQUEST[$item_scale]);
				// Check the related recipes to see which ones should added as well
				$children = $recipeObj->getRelated(true); // just get the required children
				foreach ($children as $relObj) {
					$listObj->addRecipe($relObj, $_REQUEST[$item_scale]);
				}
			} else if ($itemType == 'ingredient' && !$listObj->containsIngredient($_REQUEST[$item_id])){
				// It is an ingredient, add it. If extra info is present put it in as well
				$ing_qualifier = $itemType . "_qualifier_" . $iterator;
				$ing_quantity = $itemType . "_quantity_" . $iterator;
				$ing_units = $itemType . "_unit_" . $iterator;
				$ingObj = new Ingredient();
				$ingObj->setIngredientMap($_REQUEST[$item_id], NULL, NULL, isset($_REQUEST[$ing_quantity]) ? $_REQUEST[$ing_quantity] : 0, $_REQUEST[$ing_units]);
				$ingObj->loadIngredient();
				$listObj->addIngredient($ingObj);
			} else {
				// Ingredient is already in the list
				echo '<font size="-1" color="red">' . $LangUI->_('Ingredient is already in list') . "</font><br />";
			}
		}
		// Set the next item
		$iterator++;
		$item_id = $itemType . "_id_" . $iterator;
		$item_scale = $itemType . "_scale_" . $iterator;
		$item_name = $itemType . "_name_" . $iterator;
		$item_add = $itemType . "_selected_" . $iterator;
	}
// Delete Recipe(s) from the Shopping List
} else if ($mode == "delete") {
	// do some deletion
	$iterator = 0;
	
	$recipe_item = 'recipe_id_' . $iterator;
	$ing_item = 'ingredient_id_' . $iterator;
	// Look at all the items to see what we should delete
	while (isset($_POST[$recipe_item]) || isset($_POST[$ing_item])) {
		// Check the recipe
		$del_recipe = 'delete_recipe_'.$iterator;
		if (isset($_POST[$recipe_item]) && $_POST[$del_recipe]=='yes')
			$listObj->removeRecipe($_POST[$recipe_item]);
		// Check the ingredient
		$del_ing = 'delete_ingredient_'.$iterator;
		if (isset($_POST[$ing_item]) && $_POST[$del_ing]=='yes')
		{
			$listObj->removeIngredient($_POST[$ing_item]);
		}
		// Set next item
		$iterator++;
		$recipe_item = 'recipe_id_' . $iterator;
		$ing_item = 'ingredient_id_' . $iterator;
	}
// Save Shopping List to an exist Lists location (overwrite)
} else if ($mode == "save_update") {
	// Drop the old values
	$listObj->saveUpdatedList($_GET['list_id']);
	echo $LangUI->_('List Saved (Update)') . "<br />\n";
// Save Shopping List to a new slot
} else if ($mode =="save_new") {
	$listObj->saveNewList($_POST["list_name"]);
	echo $LangUI->_('List Saved (New)') . "<br />\n";
// Load a saved Shopping List
} else if ($mode == "load") {
	// Load the shopping list into the session
	$listObj->id = $_GET['list_id'];
	$listObj->loadItems(true);
	echo $LangUI->_('Shopping List Loaded') . "<br />\n";
} else if ($mode == "refresh") {
	// Update the values currently in the text boxes
	$iterator = 0;
	
	$recipe_item = 'recipe_id_' . $iterator;
	$ing_item = 'ingredient_id_' . $iterator;
	// Look at all the items to see what we should delete
	while (isset($_POST[$recipe_item]) || isset($_POST[$ing_item])) {
		// Check the recipes
		if (isset($_POST[$recipe_item])) {
			$scale = 'recipe_scale_' . $iterator;
			$item = $listObj->getRecipe($_REQUEST[$recipe_item]);
			$item['scale'] = $_REQUEST[$scale];
			$listObj->setRecipe($_REQUEST[$recipe_item], $item);
		}
		// Check the ingredients
		$del_ing = 'delete_ingredient_'.$iterator;
		if (isset($_POST[$ing_item])) {
			$ing_quantity = "ingredient_quantity_" . $iterator;
			$ing_qualifier = "ingredient_qualifier_" . $iterator;
			$ing_units = "ingredient_units_" . $iterator;
			$ingObj = $listObj->getIngredient($_REQUEST[$ing_item]);
			$ingObj->setIngredientMap($_REQUEST[$ing_item], NULL, $_REQUEST[$ing_qualifier], $_REQUEST[$ing_quantity], $_REQUEST[$ing_units]);
			$listObj->setIngredient($_REQUEST[$ing_item], $ingObj);
		}
		// Set next item
		$iterator++;
		$recipe_item = 'recipe_id_' . $iterator;
		$ing_item = 'ingredient_id_' . $iterator;
	}
	echo $LangUI->_('Shopping List Updated') . "<br />\n";
}

// Show the current contents of the Shopping List
if ($listObj->getItemCount()==0) {
	echo $LangUI->_('No items currently in list');
} else {
?>
		<p>
		<table cellspacing="1" cellpadding="2" border=0 width="95%" class="data">
		<FORM name="shoppingList" action="./index.php?m=lists&amp;a=current" method="post">
		<INPUT type="hidden" name="mode" value="delete">
		<tr valign="top">
			<td colspan=6 align=left>
				<input type="submit" value="<?php echo $LangUI->_('Remove Selected Items'); ?>" class="button">
				<input name="refresh" type="submit" value="<?php echo $LangUI->_('Update List'); ?>" class="button">&nbsp;
				<a href="javascript:checkAll(1)"><?php echo $LangUI->_('Check All');?></a> - 
				<a href="javascript:checkAll(0)"><?php echo $LangUI->_('Clear All');?></a>
			</td>
		</tr>
		<?php if (count($listObj->recipes)) include("modules/lists/current_recipes.php");?>
		<?php if (count($listObj->ingredients)) include ("modules/lists/current_ingredients.php");?>
		<tr valign="top">
			<td colspan=6 align=left>
				<input type="submit" value="<?php echo $LangUI->_('Remove Selected Items'); ?>" class="button">
				<input name="refresh" type="submit" value="<?php echo $LangUI->_('Update List'); ?>" class="button">&nbsp;
				<a href="javascript:checkAll(1)"><?php echo $LangUI->_('Check All');?></a> - 
				<a href="javascript:checkAll(0)"><?php echo $LangUI->_('Clear All');?></a>
			</td>
		</tr>
		
		</table>
		</form>
		<p><br />
		<?php if ($SMObj->checkAccessLevel("AUTHOR")) { ?>
		<form action="./index.php?m=lists&amp;a=current" method="post">
		<input type="hidden" name="mode" value="save_new">
		<?php echo $LangUI->_('Save to a new list'); ?> <input type="text" name="list_name">
		<input type="submit" value="<?php echo $LangUI->_('Save Shopping List'); ?>" class="button"><P>
		<?php echo $LangUI->_('Or'); ?> <a href="./index.php?m=lists&amp;a=saved"><?php echo $LangUI->_('Save to an existing shopping list'); ?></a><br /></form>
		<?php }?>
<?php
}
// save the shopping list to the session
$_SESSION['shoppinglist'] = $listObj;
?>
