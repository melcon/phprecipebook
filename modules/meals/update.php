<?php
require_once("classes/MealPlan.class.php");
require_once("classes/DBUtils.class.php");
// Initalize the Vars
$date = isset( $_GET['date'] ) ? $_GET['date'] : date('m-d-Y');
$dbDate = DBUtils::dbDate($date);

if ($SMObj->getUserLoginID() == NULL) 
	die($LangUI->_('You must be logged in to use the Meal Planner!'));

// Create a new meal plan object
$MPObj = new MealPlan($date);
// Delete all of the existing items for this day if they exist
$MPObj->delete($dbDate);
$meal = "meal_id_";
$servings = "servings_";
$recipe = "recipe_id_";
$repeat = "repeat_";
$delete = "delete_";
$iterator=0;
// Remove all of the meals currently set for this day
$MPObj->clearDay($dbDate);
// Set the first value
$currentMeal = $meal . $iterator;
while (isset($_REQUEST[$currentMeal]) && $_REQUEST[$currentMeal] != 0) {
	$currentServ = $servings . $iterator;
	$currentRecipe = $recipe . $iterator;
	$currentRepeat = $_REQUEST[$repeat.$iterator];
	$currentDelete = isset($_REQUEST[$delete.$iterator]) ? $_REQUEST[$delete.$iterator] : "";
	if ($currentDelete != "yes") {
		// Now repeat this item for the given number of days
		$thisDate = $date; 						//save the current date
		$dbDate = DBUtils::dbDate($thisDate);   //and the database formated date
		for ($currentRepeat; $currentRepeat > 0; $currentRepeat--) {
			$MPObj->insert($dbDate, htmlentities($_REQUEST[$currentMeal], ENT_QUOTES), htmlentities($_REQUEST[$currentRecipe], ENT_QUOTES), 
				htmlentities($_REQUEST[$currentServ], ENT_QUOTES), $SMObj->getUserLoginID());
			list($month, $day, $year) = split("-", $thisDate);
			list($day, $month, $year) = $MPObj->getNextDay($day,$month,$year); //get the next day in case we need it.
			$thisDate = $month."-".$day."-".$year;
			$dbDate = $dbDate = DBUtils::dbDate($thisDate);
		}
	}
	// Setup for next loop
	$iterator++;
	$currentMeal = $meal . $iterator;
}
echo $LangUI->_('Meal Plan Updated') . "<br />\n";
?>
