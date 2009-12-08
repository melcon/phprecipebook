<?php
require_once("classes/MealPlan.class.php");
require_once("classes/DBUtils.class.php");
// Initalize the Vars
$date = isset( $_GET['date'] ) ? $_GET['date'] : date('m-d-Y');
$dbDate = DBUtils::dbDate($date); // get the date in ISO format so that we have the key

if ($SMObj->getUserLoginID() == NULL) 
	die($LangUI->_('You must be logged in to use the Meal Planner!'));
	
// Create a new meal plan object
$MPObj = new MealPlan($date);
$MPObj->load($dbDate,$dbDate); //just want this one day
$minshow = 4;  // Min number of empty fields to show
$defaultServings = 2; // The default number of servings
// Read in a list of Meals and recipes
$rc = DBUtils::fetchColumn( $db_table_meals, 'meal_name', 'meal_id', 'meal_id' );
$mealList = DBUtils::createList($rc, 'meal_id', 'meal_name');
array_unshift_assoc($mealList, 0, ""); // add an empty element to the list
// Create a list of Recipes to select from, they should also specify how many servings.
$sql = "SELECT recipe_id,recipe_name,recipe_serving_size FROM $db_table_recipes ORDER BY recipe_name";
$rc = $DB_LINK->Execute($sql);
DBUtils::checkResult($rc, NULL, NULL, $sql);
while (!$rc->EOF) {
	$recipeList[($rc->fields['recipe_id'])] = $rc->fields['recipe_name'] . " (" . $rc->fields['recipe_serving_size'] . ")";
	$rc->MoveNext();
}
?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php echo $LangUI->_('Edit Meal Plan') . ': ' . $MPObj->monthsFull[($MPObj->currentMonth-1)] . " " . $MPObj->currentDay . ", " . $MPObj->currentYear; ;?>
	</td>
</tr>
<tr>
	<td class="nav" align="left">
		<a href="index.php?m=meals&view=monthly&date=<?php echo $MPObj->currentDate . '">' . $LangUI->_('Monthly View');?></a> | 
		<a href="index.php?m=meals&view=weekly&date=<?php echo $MPObj->currentDate . '">' . $LangUI->_('Weekly View');?></a> | 
		<a href="index.php?m=meals&view=daily&date=<?php echo $MPObj->currentDate . '">' . $LangUI->_('Daily View');?></a> 
	</td>
</tr>
</table>
<p>
<form action="index.php?m=meals&dosql=update&view=daily&date=<?php echo $date;?>" method="post">
<table cellspacing="1" cellpadding="4" border="0" width=80% class="data">
<tr>
	<th align="center"><?php echo $LangUI->_('Delete');?></th>
	<th align="center"><?php echo $LangUI->_('Select a Meal');?></th>
	<th align="center"><?php echo $LangUI->_('Servings');?></th>
	<th align="center"><?php echo $LangUI->_('Repeat for');?></th>
	<th align="center"><?php echo $LangUI->_('Recipe');?></th>
</tr>
<?php
// Print out all the existing meals, and some new ones
for ($i = 0; $i < 
	(isset($MPObj->mealplanItems[$dbDate]) ? count($MPObj->mealplanItems[$dbDate]) : 0) + 
	$minshow; $i++) {
	if ($i < (isset($MPObj->mealplanItems[$dbDate]) ? count($MPObj->mealplanItems[$dbDate]) : 0)) {
		// If it is an existing meal item, then set it
		$meal = $MPObj->mealplanItems[$dbDate][$i]['meal'];
		$servings = $MPObj->mealplanItems[$dbDate][$i]['servings'];
		$recipe = $MPObj->mealplanItems[$dbDate][$i]['id'];
	} else {
		// It is a new one, give it blank values
		$meal=NULL;
		$servings=$defaultServings;
		$recipe=NULL;
	}
	echo "<tr>\n";
	echo '<td align="center">';
	echo '<input type="checkbox" name="delete_'.$i.'" value="yes"></td>';
	echo '<td align="center">';
	echo DBUtils::arrayselect( $mealList, 'meal_id_'.$i, 'size=1', $meal);
	echo "</td><td align=\"center\">\n";
	echo '<input type="text" autocomplete="off" name="servings_'.$i.'" value=' . $servings . ' size=3>';
	echo '</td><td align="center">';
	echo '<input type="text" autocomplete="off" name="repeat_'.$i.'" value=1 size=3> ' . $LangUI->_('Day(s)');
	echo '</td><td align="center">';
	echo DBUtils::arrayselect( $recipeList, 'recipe_id_'.$i, 'size=1', $recipe);
	echo "</td></tr>\n";
}
?>
</table>
<p>
<input type="submit" value="<?php echo $LangUI->_('Update Meal Plan');?>" class="button">
</form>
