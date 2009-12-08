<?php
require_once("classes/MealPlan.class.php");
$date = isset( $_GET['date'] ) ? $_GET['date'] : date('m-d-Y');
$view = isset( $_GET['view'] ) ? $_GET['view'] : 'weekly';

if ($SMObj->getUserLoginID() == NULL) 
	die($LangUI->_('You must be logged in to use the Meal Planner!'));

// Create a new meal plan object
$MPObj = new MealPlan($date);

// Depending on the view type, load the need amount of meals.
if ($view == "daily") {
	// Just need to load the current date
	$dbDate = DBUtils::dbDate($date); // get the date in ISO format so that we have the key
	$MPObj->load($dbDate,$dbDate);
} else if ($view == "weekly") {
	// Most likely to happen
	$weekList = $MPObj->getWeekDaysList($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	$startDate = DBUtils::dbDate($weekList[0][1].'-'.$weekList[0][0].'-'.$weekList[0][2]);
	$endDate = DBUtils::dbDate($weekList[6][1].'-'.$weekList[6][0].'-'.$weekList[6][2]);
	$MPObj->load($startDate,$endDate);
} // we are not going to create a list for an entire month, that is just crazy...
?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php echo $LangUI->_('Create Shopping List');?>
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
<b>
<?php echo $LangUI->_('The following recipes will be added to your shopping list') . ":</b><br />";?>
<form action="index.php?m=lists&a=current&mode=add" method="post">
<?php
// Has the combined items, adding the serving sizes
$combined = array();
foreach ($MPObj->mealplanItems as $arr) {
	foreach ($arr as $vals) {
		$id = $vals['id'];
		if (!isset($combined[$id])) {
			$combined[$id] = array('id'=>$id,'name'=>$vals['name'],'servings'=>$vals['servings']);
		} else {
			// we have to combine it;
			$combined[$id]['servings'] += $vals['servings'];
		}
	}
}
$counter=0;
foreach ($combined as $item) {
	echo $item['name'] . ': ' . $item['servings'] . ' ' . $LangUI->_('Servings') . "<br />\n";
	echo '<input type="hidden" name="recipe_selected_'.$counter.'" value="yes">';
	echo '<input type="hidden" name="recipe_id_'.$counter.'" value="' . $item['id'] . "\">\n";
	echo '<input type="hidden" name="recipe_scale_'.$counter.'" value="' . $item['servings'] . "\">\n";
	echo '<input type="hidden" name="recipe_name_'.$counter.'" value="' . $item['name'] . "\">\n";
	$counter++;
}
?>
<p>
<input type="submit" value="<?php echo $LangUI->_('Add to shopping list');?>" class="button">
</form>

