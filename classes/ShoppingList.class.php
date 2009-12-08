<?php
require_once("classes/DBUtils.class.php");
/**
 * This class handles all operations dealing with the shopping list.  This includes adding ingredients/recipes, saving and loading
 * this class is used by various other files to provide a shopping list functionality.
 */
class ShoppingList {
	var $id;
	var $name;
	var $recipes = array();
	var $ingredients = array();

	/**
		creates a new shopping list (constructor)
	*/
	function ShoppingList($id=0, $name='') {
		$this->id = $id;
		$this->name = $name;
	}

	/**
		Adds a recipe to the shopping list if it is not already in the list
		@param $recipe the Recipe object to add
		@param $scale the scaling to use for the recipe
	*/
	function addRecipe($recipe, $scale) {
		if (!$this->containsRecipe($recipe->getID())) {
			$this->recipes[] = array('recipe' => $recipe, 'scale' => $scale);
		} else {
			// we need to increment the recipe
			$item = $this->getRecipe($recipe->getID());
			$item['scale'] += $scale;
			$this->setRecipe($recipe->getID(), $item);
		}
	}

	/**
		removes a given recipe from the list
		@param $id the recipe to remove
	*/
	function removeRecipe($id) {
		// create a new structure to remove the items
		$newarr = new ftk_array($this->recipes);
		// iterate through the list
		foreach ($this->recipes as $item) {
			$recipeObj = $item['recipe'];
			if ($recipeObj->getID() == $id) {
				// remove this item from the array
				$newarr->del(array_search($item,$newarr->data));
				$this->recipes = $newarr->data;
				return true;
			}
		}
		// could not delete the value from some reason
		return false;
	}

	/**
		Gets a given Recipe from the current list of recipes
	*/
	function getRecipe($id) {
		foreach ($this->recipes as $item) {
			$recipeObj = $item['recipe'];
			if ($recipeObj->getID() == $id) return $item;
		}
		return NULL;
	}

	/**
		Sets the value of an existing recipe
	*/
	function setRecipe($id, $setVal) {
		for ($i=0; count($this->recipes); $i++) {
			$recipeObj = $this->recipes[$i]['recipe'];
			if ($recipeObj->getID() == $id) {
				$this->recipes[$i] = $setVal;
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
		Adds a given ingredient to the shopping list if it is not already in the list
		@param $ingredient the object of an ingredient to add
	*/
	function addIngredient($ingredient) {
		if (!$this->containsIngredient($ingredient->getID())) {
			$this->ingredients[] = $ingredient;
			return true;
		} else return false;
	}

	/**
		removes a given ingredient from the list
		@param $id the ingredient to remove
	*/
	function removeIngredient($id) {
		// create a new structure to remove the items
		$newarr = new ftk_array($this->ingredients);
		// iterate through the list
		foreach ($this->ingredients as $ingObj) {
			if ($ingObj->getID() == $id) {
				// remove this item from the array
				$newarr->del(object_search($ingObj, $this->ingredients, true));
				$this->ingredients = $newarr->data;
				return true;
			}
		}
		// could not delete the value from some reason
		return false;
	}

	/**
		Gets a given Ingredient from the current list of recipes
	*/
	function getIngredient($id) {
		foreach ($this->ingredients as $ingObj) {
			if ($ingObj->getID() == $id) return $ingObj;
		}
		return NULL;
	}

	/**
		Sets the value of an existing Ingredient
	*/
	function setIngredient($id, $setVal) {
		for ($i=0; count($this->ingredients); $i++) {
			$ingObj = $this->ingredients[$i];
			if ($ingObj->getID() == $id) {
				$this->ingredients[$i] = $setVal;
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
		tests to see if this shopping list contains the given recipe
		@param $id the recipe ID to check for
	*/
	function containsRecipe($id) {
		foreach ($this->recipes as $item) {
			$recipeObj = $item['recipe'];
			if ($recipeObj->getID() == $id) return true;
		}
		return false;
	}

	/**
		tests to see if this shopping list contains the given ingredient
		@param $id the ingredient id to check for
	*/
	function containsIngredient($id) {
		foreach ($this->ingredients as $ing) {
			if ($ing->getID() == $id) return true;
		}
		return false;
	}

	/**
		Save current shopping list to an already existing list slot
	*/
	function saveUpdatedList($id) {
		$this->id = $id;
		$this->saveAllItems();
	}

	/**
		Saves the name of the list and that is all
	*/
	function updateListName() {
		global $DB_LINK,$db_table_list_names;
		$sql = "UPDATE $db_table_list_names SET list_name='" . $this->name . "' WHERE list_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
	}

	/**
		Save a new shopping list
	*/
	function saveNewList($name) {
		global $DB_LINK, $db_table_list_recipe, $db_table_list_ingredients,
			$db_table_list_names, $g_rb_list_id_seq, $SMObj;
		$this->name = $name;
        $name = $DB_LINK->addq($name, get_magic_quotes_gpc());
		$sql = "INSERT INTO $db_table_list_names (
						list_name,
						list_owner)
					VALUES ('$name','" . $SMObj->getUserLoginID() . "')";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
		$this->id = DBUtils::getSequenceValue($g_rb_list_id_seq);
		$this->saveAllItems();
	}

	/**
		called by update and save because they pretty much do the same thing
	*/
	function saveAllItems() {
		global $DB_LINK, $db_table_list_recipes, $db_table_list_ingredients;
		// Update the new recipes
		foreach ($this->recipes as $item) {
			$recipeObj = $item['recipe'];
			$scale = $item['scale'];
			if (!$scale) $scale = 0;

			$sql = "INSERT INTO $db_table_list_recipes (
							list_rp_id,
							list_rp_recipe,
							list_rp_scale)
					 VALUES (" . $DB_LINK->addq($this->id, get_magic_quotes_gpc()) . "," .
							 $recipeObj->getID() . ",".$DB_LINK->addq($scale, get_magic_quotes_gpc()).")";
			$rc = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, NULL, $sql);
		}
		// Update the new ingredients
		foreach ($this->ingredients as $ingObj) {
			$sql = "INSERT INTO $db_table_list_ingredients (
							list_ing_id,
							list_ing_ingredient,
							list_ing_unit,
							list_ing_qualifier,
							list_ing_quantity)
					 VALUES (" . $DB_LINK->addq($this->id, get_magic_quotes_gpc()) . "," .
							 $DB_LINK->addq($ingObj->id , get_magic_quotes_gpc()). "," .
							 $DB_LINK->addq($ingObj->unitMap, get_magic_quotes_gpc()) . ",'" .
							 $DB_LINK->addq($ingObj->qualifier, get_magic_quotes_gpc()) . "'," .
							 $DB_LINK->addq($ingObj->quantity, get_magic_quotes_gpc()) . ")";
			$rc = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, NULL, $sql);
		}
	}

	/**
		Removes this shopping list from the database
	*/
	function deleteShoppingList() {
		global $DB_LINK, $db_table_list_names;
		$sql = "DELETE FROM $db_table_list_names WHERE list_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
	}

	/**
		Removes the items of a shopping list from the database
	*/
	function deleteListItems() {
		global $DB_LINK, $db_table_list_recipes, $db_table_list_ingredients;
		// Remove the all items (ingredients and recipes)
		$sql = "DELETE FROM $db_table_list_recipes WHERE list_rp_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
		$sql = "DELETE FROM $db_table_list_ingredients WHERE list_ing_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
	}

	/**
		Get the count of the number of items on the list (ingredients and recipes)
	*/
	function getItemCount() {
		return (count($this->recipes) + count($this->ingredients));
	}

	/**
		This function serializes the shopping list into items. These items are combined recipe ingredients and user selected
		ingredients. Similair items are combined, and they are grouped by location
		@return array of ingredient objects
	*/
	function getShoppingList() {
		$ingredients = array();
		foreach ($this->recipes as $item) {
			// Get the ingredient and scale it
			$ingList = $item['recipe']->getIngredients($item['scale']);
			// merge these in with the master list
			foreach ($ingList as $ingObj) {
				$ingredients = $this->combineIngredients($ingredients, $ingObj);
			}
		}
		// now add the separate ingredients
		foreach ($this->ingredients as $ingObj) {
			$ingObj->convertToBaseUnits(1);	//scaling is set through quantity in this case
			$ingredients = $this->combineIngredients($ingredients, $ingObj);
		}
		return $ingredients; //combined and scaled shopping list
	}

	/**
		Combines ingredients in the given array that match by the set rules.  The rules are that
		the ingredient has the same name to be combined.   The quantity for items are already
		converted to the price/unit pricing of the ingredient so justt adding the quantity is safe.
		If the item cannot be merged with any items then it is added to the array in the appropriate section

		@param $arr array containing ingredient objects
		@param $addItem an ingredient item to merge in.
		@return modified array with item merged into it.
	*/
	function combineIngredients($arr, $addItem) {
		// Merge it in with the exitings ones
		for ($i=0; $i < count($arr); $i++) {
			if ($arr[$i]->name == $addItem->name) {
				// we have a match, combine these two items
				$arr[$i]->quantity += $addItem->quantity;
				return $arr;
			}
		}
		// this is a new one, add it.
		$arr[] = $addItem;
		return $arr;
	}

	/**
		Loads all of the ingredients and recipes saved in the database for this shopping
		list into an instance of this shopping list.
		@param $clear if true then the list is cleared before new items are added, if false then they are appended
	*/
	function loadItems($clear) {
		global $DB_LINK, $db_table_list_recipes, $db_table_list_ingredients;
		if ($clear) {
			// clear out the items if we are told to
			$this->recipes = array();
			$this->ingredients = array();
		}

		// Add the recipes
		$sql = "SELECT list_rp_recipe, list_rp_scale FROM $db_table_list_recipes WHERE list_rp_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);

		while (!$rc->EOF) {
			$recipeObj = new Recipe($rc->fields['list_rp_recipe']);
			$recipeObj->loadRecipe();
			$this->addRecipe($recipeObj, $rc->fields['list_rp_scale']);
			$rc->MoveNext();
		}

		// Add the ingredients
		$sql = "SELECT list_ing_ingredient,list_ing_unit,list_ing_qualifier,list_ing_quantity FROM $db_table_list_ingredients WHERE list_ing_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
		while (!$rc->EOF) {
			$ingObj = new Ingredient();
			$ingObj->setIngredientMap($rc->fields['list_ing_ingredient'],
										NULL,
										$rc->fields['list_ing_qualifier'],
										$rc->fields['list_ing_quantity'],
										$rc->fields['list_ing_unit']);
			$ingObj->loadIngredient();
			$this->addIngredient($ingObj);
			$rc->MoveNext();
		}
	}
}
?>
