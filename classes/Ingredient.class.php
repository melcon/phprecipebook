<?php
require_once("classes/Units.class.php");
require_once("classes/DBUtils.class.php");

/**
	This class handles all manipulation of ingredients, This includes converting them to fractions, combining them
	converting between units, and giving locale specific units to use.
*/
class Ingredient {
	var $recipe_id = 0;
	var $id = 0;
	var $name = NULL;
	var $description = NULL;
	var $qualifier = NULL;
	var $price = NULL;		// Ingredient Only, first non NULL Difference (key distinction)
	var $quantity = 0;
	var $unit = 'NULL';
	var $unitMap = 'NULL';
	var $location = 'NULL';
	var $solid = "TRUE";
	var $system = NULL;
	var $optional = "FALSE";
	var $order = 0;

	/**
		The constructor for creating a list of ingredients, the setting of values is done
		in setIngredient and setIngredientMap
	*/
	function Ingredient() {
		$this->system = Units::getLocalSystem();
	}

	/**
		Sets the values for an ingredient
	*/
	function setIngredient($id=0,$name='',$description='',$price='NULL',$unit='NULL',$location='NULL',$solid="TRUE") {
		global $DB_LINK;
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->price = $price;
		$this->unit = $unit;
		$this->location = $location;
		if ($solid == "TRUE") $this->solid = $DB_LINK->true;
		else $this->solid = $DB_LINK->false;
	}

	/**
		Sets the values for an ingredient map (an ingredient added with association with a
		recipe).
	*/
	function setIngredientMap($id=0,$recipe_id=0,$qualifier='',$quantity=0,$unitMap='NULL',$optional='FALSE',$order=0) {
		global $DB_LINK;
		$this->id = $id;
		$this->recipe_id = $recipe_id;
		$this->qualifier = $qualifier;
		$this->quantity = $quantity;
		$this->unitMap = $unitMap;
		if ($optional == "TRUE") $this->optional = $DB_LINK->true;
		else $this->optional = $DB_LINK->false;
		$this->order = $order;
	}

	/**
		Sets the recipe_id to associate this ingredient with
	*/
	function setID($id) { $this->recipe_id = $id;}

	/**
		Gets the Ingredient ID (could use $obj->id instead if you want)
	*/
	function getID() { return $this->id; }

	/*********************************************************
		SQL Data Functions
	*********************************************************/
	/**
		Add or Update the current instance of the ingredient/mapping
	*/
	function addUpdate() {
		global $LangUI, $DB_LINK;
		// ingredient exists, update the current values
		if ($this->id != 0) {
			$result = $this->update();
		} else {
			// Ingredient Update
			$this->id = $this->insert();
		}
	}
	/**
		Inserts the needed values to link an ingredient to a recipe
	*/
	function insertMap() {
		global $db_table_ingredientmaps, $DB_LINK, $LangUI;
		$sql="INSERT INTO $db_table_ingredientmaps
							(map_recipe,
							 map_ingredient,
							 map_qualifier,
							 map_quantity,
							 map_unit,
							 map_optional,
							 map_order)
						VALUES
							(".$DB_LINK->addq($this->recipe_id, get_magic_quotes_gpc()).",
							".$DB_LINK->addq($this->id, get_magic_quotes_gpc()).",
							'".$DB_LINK->addq($this->qualifier, get_magic_quotes_gpc())."',
							".$DB_LINK->addq($this->quantity, get_magic_quotes_gpc()).",
							".$DB_LINK->addq($this->unitMap, get_magic_quotes_gpc()).",
							'".$DB_LINK->addq($this->optional, get_magic_quotes_gpc())."',
							".$DB_LINK->addq($this->order, get_magic_quotes_gpc()).")";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, $LangUI->_('There was an error adding the ingredient/recipe mapping'), $sql);
	}

	/**
		Inserts a new ingredient into the database
	*/
	function insert() {
		global $db_table_ingredients, $DB_LINK, $g_rb_ingredient_id_seq, $LangUI;

		$sql="INSERT INTO $db_table_ingredients
							(ingredient_name,
							 ingredient_desc,
							 ingredient_price,
							 ingredient_unit,
							 ingredient_location,
							 ingredient_solid,
							 ingredient_system)
						VALUES
							('".$DB_LINK->addq($this->name, get_magic_quotes_gpc())."',
							'".$DB_LINK->addq($this->description, get_magic_quotes_gpc())."',
							".$DB_LINK->addq($this->price, get_magic_quotes_gpc()).",
							".$DB_LINK->addq($this->unit, get_magic_quotes_gpc()).",
							".$DB_LINK->addq($this->location, get_magic_quotes_gpc()).",
							'".$DB_LINK->addq($this->solid, get_magic_quotes_gpc())."',
							'".$DB_LINK->addq($this->system, get_magic_quotes_gpc())."')";
		// Insert the new ingredient into the database
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, $LangUI->_('Ingredient Added') . ": ".  $this->name,
			$LangUI->_('There was an error adding the ingredient'), $sql);
		// retrieve incremented sequence value (PostgreSQL)
		$id = DBUtils::getSequenceValue($g_rb_ingredient_id_seq);
		return $id;
	}

	/**
		Updates an existing ingredient in the database
	*/
	function update() {
		global $db_table_ingredients, $DB_LINK, $LangUI;
		$sql = "
			UPDATE $db_table_ingredients
			SET ingredient_name='".$DB_LINK->addq($this->name, get_magic_quotes_gpc())."',
				ingredient_desc='".$DB_LINK->addq($this->description, get_magic_quotes_gpc())."',
				ingredient_location=".$DB_LINK->addq($this->location, get_magic_quotes_gpc()).",
				ingredient_solid='".$DB_LINK->addq($this->solid, get_magic_quotes_gpc())."',
				ingredient_price=".$DB_LINK->addq($this->price, get_magic_quotes_gpc()).",
				ingredient_unit=".$DB_LINK->addq($this->unit, get_magic_quotes_gpc()).",
				ingredient_system='".$DB_LINK->addq($this->system, get_magic_quotes_gpc())."'
			WHERE ingredient_id=".$DB_LINK->addq($this->id, get_magic_quotes_gpc())."";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, $LangUI->_('Ingredient updated') . ": ".  $this->name,
			$LangUI->_('There was an error updating the ingredient'), $sql);
	}

	/**
		Deletes the current ingredient from the database
	*/
	function delete() {
		global $db_table_ingredients, $db_table_ingredientmaps, $db_table_list_ingredients,
			$DB_LINK, $LangUI;
		// Delete the ingredient
		$sql = "DELETE from $db_table_ingredients WHERE ingredient_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);

		$sql = "DELETE from $db_table_ingredientmaps WHERE map_ingredient=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$result = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);

		$sql = "DELETE from $db_table_list_ingredients WHERE list_ing_ingredient=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
		$result = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
	}

	/**
		Computes the price of this ingredient mapping based on the ingredient price per unit
	*/
	function computePrice() {
		global $DB_LINK;
		// Make sure we have the info we need
		$this->loadIngredient();
		if ($this->solid == $DB_LINK->true) $solid = true;
		$quantity = Units::convertTo($this->quantity, $this->unitMap, $this->unit, $this->solid);
		return ($this->quantity * $this->price);
	}

	/**
		Loads the ingredient information if it is not already set
	*/
	function loadIngredient() {
		global $DB_LINK, $db_table_ingredients, $LangUI;
		// Only run this if we have not loaded the information yet
		if ($this->price==NULL) {
			$sql = "SELECT * FROM $db_table_ingredients WHERE ingredient_id=" . $DB_LINK->addq($this->id, get_magic_quotes_gpc());
			$rc = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, $LangUI->_('There was an error loading the ingredient'), $sql);
			$this->name = $rc->fields['ingredient_name'];
			$this->description = $rc->fields['ingredient_desc'];
			$this->price = $rc->fields['ingredient_price'];
			$this->unit = $rc->fields['ingredient_unit'];
			$this->location = $rc->fields['ingredient_location'];
			if ($DB_LINK->true == $rc->fields['ingredient_solid'])
				$this->solid = true;
			else
				$this->solid = false;
			$this->system = $rc->fields['ingredient_system'];
		}
		return TRUE;
	}

	/**
		Converts an ingredients quantity and units to the price per Unit, units.  This way combining of
		ingredients will be easier
	*/
	function convertToBaseUnits($scaling) {
		$this->quantity = Units::convertTo($this->quantity*$scaling, $this->unitMap, $this->unit, $this->solid);
		$this->unitMap = $this->unit;
	}
}
?>
