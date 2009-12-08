<?php
require_once("classes/DBUtils.class.php");
/**
	This class provides a framework to import recipes into the database
	Specific formats such as XML, MealMaster, RecipeML... should extend this class
*/
class Import {
	var $useFilter = NULL;
	var $importRecipes = array(); // List of (Recipe Object, List of Ingredient Objects)
	var $relatedRecipes = array();

	// These are cached in this object to avoid looking them up twice
	var $units = array();
	var $ethnicity = array();
	var $bases = array();
	var $courses = array();
	var $prep_times = array();
	var $difficulty = array();
	var $locations = array();
	var $ingredients = array();
	var $recipes = array();

	/**
		Constructor for this class, initializes some values
	*/
	function Import($filter='XML') {
		global $db_table_courses, $db_table_bases, $db_table_ethnicity, $db_table_prep_time,
			$db_table_difficulty, $db_table_units, $db_table_ingredients, $db_table_locations;
		$this->useFilter = $filter;

		// Look up the field values
		$this->courses = DBUtils::createList(DBUtils::fetchColumn( $db_table_courses, 'course_desc', 'course_id', 'course_desc'), 'course_desc', 'course_id');
		$this->bases = DBUtils::createList(DBUtils::fetchColumn( $db_table_bases, 'base_desc', 'base_id', 'base_desc'), 'base_desc', 'base_id');
		$this->ethnicity = DBUtils::createList(DBUtils::fetchColumn( $db_table_ethnicity, 'ethnic_desc', 'ethnic_id', 'ethnic_desc'), 'ethnic_desc', 'ethnic_id');
		$this->prep_times = DBUtils::createList(DBUtils::fetchColumn( $db_table_prep_time, 'time_desc', 'time_id', 'time_desc'), 'time_desc', 'time_id');
		$this->difficulty = DBUtils::createList(DBUtils::fetchColumn( $db_table_difficulty, 'difficult_desc', 'difficult_id', 'difficult_desc'), 'difficult_desc', 'difficult_id');
		$this->units = DBUtils::createList(DBUtils::fetchColumn( $db_table_units, 'unit_desc', 'unit_id', 'unit_desc'), 'unit_desc', 'unit_id');
		$this->locations = DBUtils::createList(DBUtils::fetchColumn( $db_table_locations, 'location_desc', 'location_id', 'location_desc'), 'location_desc', 'location_id');
		$this->ingredients = DBUtils::createList(DBUtils::fetchColumn( $db_table_ingredients, 'ingredient_name', 'ingredient_id', 'ingredient_name'), 'ingredient_name', 'ingredient_id');
	}

	/**
		Gets the data out of the given data file and loads it into the importRecipes, relatedRecipes arrays
		@param $file The file to parse
	*/
	function parseDataFile($file) {
		global $LangUI;
		if (!($fp = fopen($file, "r"))) {
			die($LangUI->_('could not data file for reading'). "<br />");
		}
		$this->parseDataFileImpl($fp); // call the function that is implementing the import
		fclose($fp); // close the data file
	}

	/**
		Implementation for parsing the file to import
	*/
	function parseDataFileImpl($fp) {}

	/**
		Imports all of the recipes currently loaded (do a parseDataFile first)
	*/
	function importData() {
		global $LangUI, $DB_LINK, $db_table_recipes, $db_table_related_recipes;
		// Iterate through all the recipes and create them
		foreach ($this->importRecipes as $item) {
			$recipeObj = $item[0];
			$id = $recipeObj->insert();
			$order = 0; // order the ingredients
			foreach ($item[1] as $ingObj) {
				if ($ingObj!=NULL) {
					// See if the ingredient exists
					$ing_id = $this->ingredients[($ingObj->name)];
					if ($ing_id == NULL) {
						// Note: lots of defaults are guessed if this option is taken
						$ingObj->solid = $DB_LINK->true;
						$ingObj->price = '0.00';
						// Create the Ingredient and then set the ID
						$ing_id = $ingObj->insert();
						$this->ingredients[($ingObj->name)] = $ing_id; // Save the ID for later use
					}
					// Map the ingredient
					$ingObj->id = $ing_id;		// We have an ID set it.
					$ingObj->recipe_id = $id; 	// Set the Recipe ID as well
					$ingObj->order = $order;	// Set the order of the ingredient
					// Insert the mapping
					$ingObj->insertMap();
					$order++;
				}
			}
		}
		// Now we can link in the related recipes...
		$this->recipes = DBUtils::createList(DBUtils::fetchColumn( $db_table_recipes, 'recipe_name', 'recipe_id', 'recipe_name'), 'recipe_name', 'recipe_id');
		foreach ($this->relatedRecipes as $link) {
			if (($this->recipes[($link[0])] != NULL) && ($this->recipes[($link[1])] != NULL)) {
				$sql="INSERT INTO $db_table_related_recipes (related_parent, related_child, related_required) VALUES (" . $DB_LINK->addq($this->recipes[($link[0])], get_magic_quotes_gpc()) . ", " .
					$this->recipes[($link[1])] . ", '" . $link[2] . "')";
				$rc = $DB_LINK->Execute($sql);
				DBUtils::checkResult($rc, NULL, NULL, $sql);
				echo $LangUI->_('Linking') . ": '" . $link[0] . "' ".$LangUI->_('to') . " '" . $link[1] . "'<br />";
			}
		}
	}
}
?>
