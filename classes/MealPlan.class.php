<?php
require_once("classes/DBUtils.class.php");
/*
	This class is used to contain values for meal planning.  Different views and
	styles can be set through this class.  Most of the data handling and parsing should
	be included in this class, including loading and saving
*/
class MealPlan {
	var $daysAbbr = array();
	var $daysFull = array();
	var $monthsAbbr = array();
	var $monthsFull = array();
	// Date that we are currently looking at
	var $currentDate = NULL;
	var $currentDay = NULL;
	var $currentMonth = NULL;
	var $currentYear = NULL;
	// The real date
	var $realDate = NULL;
	var $realDay = NULL;
	var $realMonth = NULL;
	var $realYear = NULL;
	// Save the meal plan info in memory
	var $mealplanItems = array();
	var $mealList = array(); // names and ids of the meals
	// The Start Day of the week
	var $startWeekDay = NULL;

	/**
		Initializes some default values (With translation)
	*/
	function MealPlan($date) {
		global $LangUI, $DB_LINK, $db_table_settings;

		// Get the Start Day
		$sql = "SELECT setting_mp_day FROM $db_table_settings";
		$result = $DB_LINK->Execute($sql);
		DBUtils::checkResult($result, NULL, NULL, $sql); // Error check
		$this->startWeekDay = $result->fields['setting_mp_day'];

		/* Initialize the months and days */
		$this->daysAbbr = array(
					      $LangUI->_('Sun'),
						  $LangUI->_('Mon'),
						  $LangUI->_('Tue'),
						  $LangUI->_('Wed'),
						  $LangUI->_('Thu'),
						  $LangUI->_('Fri'),
						  $LangUI->_('Sat')
					);
		$this->daysFull = array(
						  $LangUI->_('Sunday'),
						  $LangUI->_('Monday'),
						  $LangUI->_('Tuesday'),
						  $LangUI->_('Wednesday'),
						  $LangUI->_('Thursday'),
						  $LangUI->_('Friday'),
						  $LangUI->_('Saturday')
					);
		$this->monthsAbbr = array($LangUI->_('Jan'),
								  $LangUI->_('Feb'),
								  $LangUI->_('Mar'),
								  $LangUI->_('Apr'),
								  $LangUI->_('May'),
								  $LangUI->_('Jun'),
								  $LangUI->_('Jul'),
								  $LangUI->_('Aug'),
								  $LangUI->_('Sep'),
								  $LangUI->_('Oct'),
								  $LangUI->_('Nov'),
								  $LangUI->_('Dec')
							);
		$this->monthsFull = array($LangUI->_('January'),
								  $LangUI->_('February'),
								  $LangUI->_('March'),
								  $LangUI->_('April'),
								  $LangUI->_('May'),
								  $LangUI->_('June'),
								  $LangUI->_('July'),
								  $LangUI->_('August'),
								  $LangUI->_('September'),
								  $LangUI->_('October'),
								  $LangUI->_('November'),
								  $LangUI->_('December')
							);
		$this->initDate($date); // initalize the date variables
	}

	/**
		Initalizes the current date settings for use later on
	*/
	function initDate($date) {
		$this->currentDate = $date;
		list($this->currentMonth,$this->currentDay,$this->currentYear) = split("-",$date);
		// Now set the real date
		$date = date('m-d-Y');
		$this->realDate = $date;
		list($this->realMonth,$this->realDay,$this->realYear) = split("-",$date);
	}

	/**
		Loads the meal specified meal plan into memory
		@param $start The date to start at
		@param $end The date to end with
		@return true, if sucessful, false if not (the results are set in a class var)
	*/
	function load($start,$end) {
		global $SMObj, $DB_LINK, $db_table_mealplans, $db_table_recipes;
		$sql = "SELECT mplan_date,mplan_meal,mplan_servings,mplan_recipe,recipe_name FROM recipe_mealplans
				LEFT JOIN $db_table_recipes ON recipe_id = mplan_recipe
				WHERE mplan_owner = '" . $SMObj->getUserLoginID() . "' AND mplan_date BETWEEN '".$DB_LINK->addq($start, get_magic_quotes_gpc())."' AND '".$DB_LINK->addq($end, get_magic_quotes_gpc())."'";
		$mplan = $DB_LINK->Execute($sql);
		// Error check
		DBUtils::checkResult($mplan, NULL, NULL, $sql);
		//echo $sql;
		while (!$mplan->EOF) {
			$this->mealplanItems[($mplan->fields['mplan_date'])][] = array(
									'meal' => $mplan->fields['mplan_meal'],
									'name' => $mplan->fields['recipe_name'],
									'id' => $mplan->fields['mplan_recipe'],
									'servings' => $mplan->fields['mplan_servings']);
			$mplan->MoveNext();
		}
	}

	/**
		Deletes all of the meal planner items for the currently set date, this is mainly used so
		that a fresh set of items can be set
		@param $date the date to delete from (in ISO format)
	*/
	function delete($date) {
		global $DB_LINK, $db_table_mealplans;
		$sql = "DELETE FROM $db_table_mealplans WHERE mplan_date='".$DB_LINK->addq($date, get_magic_quotes_gpc())."'";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);// Error check
	}

	/**
		Adds a meal plan item into the database
		@param $date The ISO date to save it under
		@param $meal The id of the meal (breakfast, lunch...)
		@param $recipe The id of the recipe
		@param $servings The serving size
		@param $owner The owner of this item
	*/
	function insert($date, $meal, $recipe, $servings, $owner) {
		global $DB_LINK, $db_table_mealplans, $LangUI;

        $date = $DB_LINK->addq($date, get_magic_quotes_gpc());
        $meal = $DB_LINK->addq($meal, get_magic_quotes_gpc());
        $recipe = $DB_LINK->addq($recipe, get_magic_quotes_gpc());
        $servings = $DB_LINK->addq($servings, get_magic_quotes_gpc());
        $owner = $DB_LINK->addq($owner, get_magic_quotes_gpc());

		$sql = "INSERT INTO $db_table_mealplans (mplan_date, mplan_meal, mplan_recipe, mplan_servings, mplan_owner)
					VALUES ('$date', $meal, $recipe, $servings, '$owner')";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, $LangUI->_('Recipe is already added to Meal Plan for date:'.$date), $sql);// Error check
	}

	/**
		Removes all of the meals currently saved for a day so that meals will only be added if they are wanted.  This
		function can be used in combination with insert(...) in order to remove unwanted recipes
		@param $date The date to clear in ISO format (use DBUtils)
	*/
	function clearDay($date) {
		global $DB_LINK, $db_table_mealplans;
        $date = $DB_LINK->addq($date, get_magic_quotes_gpc());
		$sql = "DELETE FROM $db_table_mealplans WHERE mplan_date='$date'";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc,NULL,NULL,$sql);
	}

	/**
		Gets the meals for a given day and puts it into html and returns it as a string
		@param $date The date to get (ISO Format)
		@param $format The format to put it in, maily big=1, small=2
		@return String with HTML in it
	*/
	function getMeals($date, $format) {
		global $DB_LINK, $db_table_meals;
		$output = "";
		// If there is nothing to get, don't bother, just return
		if (!isset($this->mealplanItems[$date])) return "";
		// Get the list of all the meal types (breakfast, lunch...)
		if (count($this->mealList) == 0) {
			// we have to load the list of meals now
			$rc = DBUtils::fetchColumn( $db_table_meals, 'meal_name', 'meal_id', 'meal_id' );
			$this->mealList = DBUtils::createList($rc, 'meal_id', 'meal_name');
		}
		// Now that we know there is something to get
		foreach ($this->mealList as $k=>$v) {
			$count = 0;
			$str="";
			foreach ($this->mealplanItems[$date] as $item) {
				if ($item['meal'] == $k) {
					// add this one &#183;
					if ($format==1) $str .= '<li><a href="index.php?m=recipes&a=view&recipe_id=' . $item['id'] . '">' . $item['name'] . "</a>\n";
					else if ($format==2) $str .= '<tr><td class="nav" colspan=2 nowrap><a href="index.php?m=recipes&a=view&recipe_id=' . $item['id'] . '">' .
						'&#183; ' . $item['name'] . "</a></td></tr>\n";
					$count++;
				}
			}
			if ($count) {
				if ($format==1) $output .= "$v<ul>" . $str . "</ul>";
				else if ($format==2) {
					$output .= '<tr><td colspan=2 class="nav">' . $v . ':</font></td></tr>';
					$output .= $str;
				}

			}
		}
		return $output;
	}

	/**
		This function determines how many days are in a given month and year
		@param $month The month
		@param $year THe year
		@return the number of days in the month/year combo
	*/
	function daysInMonth($month,$year) {
		$dim = array(31,28,31,30,31,30,31,31,30,31,30,31);
		$value = $dim[$month-1];
		if ( $month == 2 && $year %4 == 0 && $year % 100 != 0 ) {
			$value++;
		}
		return $value;
	}

	/**
		Creates an array with the days of the week in them. This will account for weeks
		that wrap to the next month, or carry over from the previous month
		@param $day The day
		@param $month The month
		@param $year the year
		@return seven element array of arrays (day, month, year)
	*/
	function getWeekDaysList($day, $month, $year) {
		$dayList = array();
		$count = 1;
		// Figure out what day of the week this date is on
		$weekDay = date('w',mktime(0,0,0,$month,$day,$year));
		// Set the date so that it is the given start of the week (any day)
		if ($weekDay != $this->startWeekDay) {
			if ($weekDay < $this->startWeekDay) {
				$dec = 7 - $this->startWeekDay + $weekDay;
				list($day, $month, $year) = $this->getPreviousDay($day, $month, $year, $dec);
			} else if ($weekDay > $this->startWeekDay) {
				$dec = $weekDay - $this->startWeekDay;
				list($day, $month, $year) = $this->getPreviousDay($day, $month, $year, $dec);
			}
		}
		// Save the start date
		$dayList[] = array($day, $month, $year);
		// Add days to the list until we reach 7 days (one week)
		while ($count < 7) {
			list($day, $month, $year) = $this->getNextDay($day, $month, $year, 1);
			$dayList[] = array($day, $month, $year);
			$count++;
		}
		return $dayList;
	}

	/**
		This function gets the date of a day that is a given
		number of days in the future.
		@param $day The day
		@param $month The month
		@param $year The year
		@param $num the number of days to forward
		@return array of ($day, $month, $year) that is the new date
	*/
	function getNextDay($day, $month, $year, $num=1) {
		$maxdays = $this->daysInMonth($month,$year);
		while ($num > 0) {
			if ($day == $maxdays) {
				// We need to roll over to a new month
				if ($month < 12) $month++;
				else {
					$year++;
					$month=1;
				}
				$day = 1;
			} else $day++;
			$num--;
		}
		return array($day, $month, $year);
	}

	/**
		This function gets the date of a day that is a given
		number of days ago.
		@param $day The day
		@param $month The month
		@param $year The year
		@param $num the number of days to go back
		@return array of ($day, $month, $year) that is the new date
	*/
	function getPreviousDay($day, $month, $year, $num) {
		// Loop until we have gone $num days backwards
		while ($num > 0) {
			if ($day == 1) {
				// we need to roll back to the previous month
				if ($month > 1) {
					$month--;
				} else {
					$year--;
					$month=12;
				}
				// Set days to the max days of the new month
				$day = $this->daysInMonth($month,$year);
			} else $day--;
			$num--;
		}
		return array($day, $month, $year);
	}

	/**
		Gets the next week (Sunday) for a given date
	*/
	function getNextWeek($day, $month, $year) {
		$weekList = $this->getWeekDaysList($day,$month,$year);
		$day = $weekList[6][0];
		$month = $weekList[6][1];
		$year = $weekList[6][2];
		return ($this->getNextDay($day,$month,$year,1));
	}

	/**
		Gets the previous week (Sunday) for a given date
	*/
	function getPreviousWeek($day, $month, $year) {
		$weekList = $this->getWeekDaysList($day,$month,$year);
		$day = $weekList[0][0];
		$month = $weekList[0][1];
		$year = $weekList[0][2];
		return ($this->getPreviousDay($day,$month,$year,7));
	}

	/**
		Gets the next month
	*/
	function getNextMonth($day, $month, $year) {
		if ($month < 12) $month++;
		else {
			$month=1;
			$year++;
		}
		return array($day, $month, $year);
	}

	/**
		Gets the previous month
	*/
	function getPreviousMonth($day, $month, $year) {
		if ($month > 1) $month--;
		else {
			$month=12;
			$year--;
		}
		return array($day, $month, $year);
	}
}
?>
