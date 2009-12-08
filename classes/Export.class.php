<?php
/*
	This class provides a framework to create a filter to export recipes from the database.
	Specific formats such as XML, MealMaster, RecipeML... should extend this class
*/
require_once("classes/Recipe.class.php");
require_once("classes/DBUtils.class.php");

class Export {
	var $exportAll = false;
	var $useFilter = NULL;
	var $exportRecipes = array();
	var $sessionVar = 'export_data'; // The place in the session to store the data
	
	// These are cached in this object to avoid looking them up twice
	var $units = array();
	var $locations = array();
	var $ethnicity = array();
	var $bases = array();
	var $courses = array();
	var $prep_times = array();
	var $difficulty = array();
	
	/**
		Constructor, creates the exporting object and the correct subclass based on the param $filter
	*/
	function Export($filter='XML') {
		global $db_table_courses, $db_table_bases, $db_table_ethnicity, 
			$db_table_prep_time, $db_table_difficulty, $db_table_units, $db_table_locations;
		$this->useFilter = $filter;
		// Look up the field values
		$this->courses = DBUtils::createList(DBUtils::fetchColumn( $db_table_courses, 'course_desc', 'course_id', 'course_desc'), 'course_id', 'course_desc');
		$this->bases = DBUtils::createList(DBUtils::fetchColumn( $db_table_bases, 'base_desc', 'base_id', 'base_desc'), 'base_id', 'base_desc');
		$this->ethnicity = DBUtils::createList(DBUtils::fetchColumn( $db_table_ethnicity, 'ethnic_desc', 'ethnic_id', 'ethnic_desc'), 'ethnic_id', 'ethnic_desc');
		$this->prep_times = DBUtils::createList(DBUtils::fetchColumn( $db_table_prep_time, 'time_desc', 'time_id', 'time_desc'), 'time_id', 'time_desc');
		$this->difficulty = DBUtils::createList(DBUtils::fetchColumn( $db_table_difficulty, 'difficult_desc', 'difficult_id', 'difficult_desc'), 'difficult_id', 'difficult_desc');
		$this->units = DBUtils::createList(DBUtils::fetchColumn( $db_table_units, 'unit_desc', 'unit_id', 'unit_desc'), 'unit_id', 'unit_desc');
		$this->locations = DBUtils::createList(DBUtils::fetchColumn( $db_table_locations, 'location_desc', 'location_id', 'location_desc'), 'location_id', 'location_desc');
	}
	
	/**
		Loads all of the recipes we are going to export into the $exportRecipes
		array.
		@param $id The recipe id to be exported, if set to 0 then export all recipes
	*/
	function getData($id) {
		global $DB_LINK, $db_table_recipes;
		if ($id == 0) {
			$this->exportAll=true;
			// recursively call for all the recipes in the database
			$sql="SELECT recipe_id FROM $db_table_recipes";
			$rc = $DB_LINK->Execute($sql);
			DBUtils::checkResult($rc, NULL, NULL, $sql);
			while (!$rc->EOF) {
				$this->getData($rc->fields['recipe_id']);
				$rc->MoveNext();
			}
		} else {
			$recipeObj = new Recipe($id);
			$recipeObj->loadRecipe();
			$this->exportRecipes[] = $recipeObj;
		}
	}
	
	/**
		Clears all of the currently set recipes out of this object
	*/
	function clearData() {
		$this->exportRecipe = array();
	}

	/**
		This function is to be extended by the specific filter that
		is used to export data from the database, before using this function
		getData() should be called. See Export_XML.class.php for an example.
	*/
	function exportData() {	}
}
?>
