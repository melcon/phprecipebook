<?php
require_once("classes/MealPlan.class.php");
// Initalize the POST/GET Vars
$view = isset( $_GET['view'] ) ? $_GET['view'] : 'weekly';
$date = isset( $_GET['date'] ) ? $_GET['date'] : date('m-d-Y');

if ($SMObj->getUserLoginID() == NULL) 
	die($LangUI->_('You must be logged in to use the Meal Planner!'));

// Create a new meal plan object
global $MPObj;
$MPObj = new MealPlan($date);

// Setup the forward and backward links and the title
$forwardLink = NULL;
$backwardLink = NULL;
$title = NULL;
$subtitle = NULL; // title of what day/week/month we are in
$editLink1 = '<a href="index.php?m=meals&a=edit&date=';
$editLink2 = '">[' . $LangUI->_('Edit') . ']<a/>';
// Now set the forward/backward/titles...
if ($view == "daily") {
	// daily view
	list($day,$month,$year) = $MPObj->getNextDay($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear, 1);
	$forwardLink="<a href=\"index.php?m=meals&view=daily&date=$month-$day-$year\">" . $LangUI->_('Next Day') . "</a>\n";
	list($day,$month,$year) = $MPObj->getPreviousDay($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear, 1);
	$backwardLink="<a href=\"index.php?m=meals&view=daily&date=$month-$day-$year\">" . $LangUI->_('Previous Day') . "</a>\n";
	$title = $LangUI->_('Daily Meal Planner');
	$subtitle = $MPObj->monthsFull[($MPObj->currentMonth-1)] . " " . $MPObj->currentDay . " " . $MPObj->currentYear;
	$shoppingLink = "<a href=\"index.php?m=meals&a=createlist&view=daily&date=$date\">" . $LangUI->_('Create Shopping List') . '</a>';
} else if ($view == "monthly") {
	// Monthly view
	list($day,$month,$year) = $MPObj->getNextMonth($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	$forwardLink="<a href=\"index.php?m=meals&view=monthly&date=$month-$day-$year\">" . $LangUI->_('Next Month') . "</a>\n";
	list($day,$month,$year) = $MPObj->getPreviousMonth($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	$backwardLink="<a href=\"index.php?m=meals&view=monthly&date=$month-$day-$year\">" . $LangUI->_('Previous Month') . "</a>\n";
	$title = $LangUI->_('Monthly Meal Planner');
	$subtitle = $MPObj->monthsFull[($MPObj->currentMonth-1)] . " " . $MPObj->currentYear;
	$shoppingLink = "";
} else if ($view == "weekly") { 
	// Weekly view as default
	list($day,$month,$year) = $MPObj->getNextWeek($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	$forwardLink="<a href=\"index.php?m=meals&view=weekly&date=$month-$day-$year\">" . $LangUI->_('Next Week') . "</a>\n";
	list($day,$month,$year) = $MPObj->getPreviousWeek($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	$backwardLink="<a href=\"index.php?m=meals&view=weekly&date=$month-$day-$year\">" . $LangUI->_('Previous Week') . "</a>\n";
	$title = $LangUI->_('Weekly Meal Planner');
	$weekList = $MPObj->getWeekDaysList($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	$subtitle = $LangUI->_('Week of') . " " . $MPObj->monthsFull[($MPObj->currentMonth-1)] . " " . $weekList[0][0] . " " . $MPObj->currentYear;
	$shoppingLink = "<a href=\"index.php?m=meals&a=createlist&view=weekly&date=$date\">" . $LangUI->_('Create Shopping List') . '</a>';
}
?>
<!----------------------- Display Header --------------------------------------->
<table border=0 cellspacing=0 cellpadding=2 width=\"100%\" bgcolor="#FFFFFF">
<tr>
	<td class="title">
		<?php echo $title;?>
	</td>
</tr>
</table>
<table border="1" cellspacing="0" cellpadding="2" width="100%" bgcolor="#FFFFFF">
<tr>
	<td colspan="3">
	<table border="0" cellspacing="0" cellpadding="2" width="100%" bgcolor="#FFFFFF">
    	<tr>
			<td width="33%">&nbsp;</td>
			<td align="center" width="34%" nowrap>
				<?php echo $SMObj->getUserName() . " " . $LangUI->_('Meal Plan');?>
			</td>
			<td align=right width="33%" nowrap>
				<?php echo $shoppingLink;?>
			</td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td width="33%">&nbsp;</td>
	<td align="center" class="nav" width="34%" nowrap>
		<a href="index.php?m=meals&view=monthly&date=<?php echo $MPObj->currentDate . '">' . $LangUI->_('Monthly View');?></a> | 
		<a href="index.php?m=meals&view=weekly&date=<?php echo $MPObj->currentDate . '">' . $LangUI->_('Weekly View');?></a> | 
		<a href="index.php?m=meals&view=daily&date=<?php echo $MPObj->currentDate . '">' . $LangUI->_('Daily View');?></a> 
	</td>
	<td align=right width=\"33%\" nowrap>&nbsp;</td>
</tr>
<tr>
	<th>
		<?php echo $backwardLink;?>
	</th>
	<th>
		<?php echo $subtitle;?>
	</th>
	<th>
		<?php echo $forwardLink;?>
	</th>
</tr>
<!--------------- End of headers ----------------------------->
<?php

if ($view == "daily") {
//-------------------------------------------------------------------------------
// Daily View
//-------------------------------------------------------------------------------
$weekDay = date('w',mktime(0,0,0,$MPObj->currentMonth,$MPObj->currentDay,$MPObj->currentYear));
$dbDate = DBUtils::dbDate($date); // get the date in ISO format so that we have the key
$MPObj->load($dbDate,$dbDate); //just want this one day

echo '<tr><td colspan="3" valign="top" height=150><b>' . $MPObj->daysFull[$weekDay] . '</b>    ';
echo $editLink1 . $date . $editLink2;
echo '<br /><center><table border=0 cellpadding=0 cellspacing=0><tr><td>';
echo $MPObj->getMeals($dbDate,1);
echo '</td></tr></table></center><br />';
echo '</td></tr>';

} else if ( $view == "monthly" ) {
//-------------------------------------------------------------------------------
// monthly view
//-------------------------------------------------------------------------------
	printWeekDays();
	// Print out the weeks
	$month = $MPObj->currentMonth;
	$day = 1;
	$year = $MPObj->currentYear;
	while ($month == $MPObj->currentMonth) {
		$weekdays = $MPObj->getWeekDaysList($day, $month, $year);
		// Load the meals into memory
		$startDate = DBUtils::dbDate($weekdays[0][1].'-'.$weekdays[0][0].'-'.$weekdays[0][2]);
		$endDate = DBUtils::dbDate($weekdays[6][1].'-'.$weekdays[6][0].'-'.$weekdays[6][2]);
		$MPObj->load($startDate,$endDate);
		echo '<tr>';
		// Print out each day
		foreach ($weekdays as $d) {
			$dbDate = DBUtils::dbDate($d[1].'-'.$d[0].'-'.$d[2]); // get the date in ISO format so that we have the key
			echo "<td width=70 height=90 valign=top";
			if ($d[1] != $MPObj->currentMonth) echo " bgcolor=#DBDBDB";
			else if ($d[0] == $MPObj->realDay && $d[1] == $MPObj->realMonth && $d[2] == $MPObj->realYear) echo " bgcolor=#d6e6f5";
			echo '><a href="index.php?m=meals&view=daily&date=' . $d[1] . '-' . $d[0] . '-' . $d[2] . '">' . $d[0] . '</a>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $editLink1 . $d[1] . '-' . $d[0] . '-' . $d[2] . $editLink2;
			echo '<center><table border=0 cellpadding=0 cellspacing=0><tr><td>';
			echo $MPObj->getMeals($dbDate,2);
			echo '</td></tr></table></center><br /></td>';
		}
		echo "</tr>";
		// Set the vars for the next week
		$day = $weekdays[6][0];
		$month =  $weekdays[6][1];
		$year =  $weekdays[6][2];
		// This should put as at sunday of the next week
		list($day, $month, $year) = $MPObj->getNextDay($day, $month, $year, 1);
	}
	echo "</table>";

} else if ($view == "weekly") {
//-------------------------------------------------------------------------------
// Weekly View
//-------------------------------------------------------------------------------
	printWeekDays();
	$weekdays = $MPObj->getWeekDaysList($MPObj->currentDay, $MPObj->currentMonth, $MPObj->currentYear);
	// Load the meals into memory
	$startDate = DBUtils::dbDate($weekdays[0][1].'-'.$weekdays[0][0].'-'.$weekdays[0][2]);
	$endDate = DBUtils::dbDate($weekdays[6][1].'-'.$weekdays[6][0].'-'.$weekdays[6][2]);
	$MPObj->load($startDate,$endDate);
	// Print out the week
	foreach ($weekdays as $d) {
		$dbDate = DBUtils::dbDate($d[1].'-'.$d[0].'-'.$d[2]); // get the date in ISO format so that we have the key
		echo "<td width=80 height=140 valign=top";
		if ($d[1] != $MPObj->currentMonth) echo " bgcolor=#DBDBDB";
		else if ($d[0] == $MPObj->realDay && $d[1] == $MPObj->realMonth && $d[2] == $MPObj->realYear) echo " bgcolor=#d6e6f5";
		echo '><a href="index.php?m=meals&view=daily&date=' . $d[1] . '-' . $d[0] . '-' . $d[2] . '">' . $d[0] . '</a>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $editLink1 . $d[1] . '-' . $d[0] . '-' . $d[2] . $editLink2;
		echo '<center><table border=0 cellpadding=0 cellspacing=0><tr><td>';
		echo $MPObj->getMeals($dbDate,2);
		echo '</td></tr></table></center><br />';
		echo"</td>\n";
	}
	echo "</tr>";
}

/*
	Prints out the abbreviated names of the days of the week, starting with the globally set start of the week
	that is set in the config file
*/
function printWeekDays() {
	global $MPObj;
	$day = $MPObj->startWeekDay;
	// Print out the week days (abbr)
	echo '<tr><td colspan="3">';
	echo '<table border="1" cellspacing="0" cellpadding="1" width="100%" bgcolor="#FFFFFF"><tr>';
	for ($i=0; $i<7; $i++) 
	{
		echo "<th>" . $MPObj->daysAbbr[$day] . "</th>\n";
		// if we get to 6 wrap around to the next day (sunday)
		if ($day == 6) $day = 0;
		else $day++;
	}
	echo "</tr><tr>\n";
}
?>
</td>
</tr>
</table>
