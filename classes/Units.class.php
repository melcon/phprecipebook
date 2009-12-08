<?php
require_once ("includes/unitdef.php");	//Unit conversion definitions
require_once ("classes/DBUtils.class.php");

/**
	This class provides functions to convert between different units and to functions to deal with different unit systems
*/
class Units {
	/**
		Gets the ID's of the units that someone would use locally based on the passed
		in system type
	*/
	function getLocalUnitIDs($system) {
		global $g_rb_units;
		$units = array();
		foreach ($g_rb_units['static'] as $key) $units[] = $key;		// Unit types
		if (isset($g_rb_units[$system]["wet"]))  foreach ($g_rb_units[$system]["wet"] as $key=>$val) $units[] = $key;	// Wet volume units
		if (isset($g_rb_units[$system]["dry"])) foreach ($g_rb_units[$system]["dry"] as $key=>$val) $units[] = $key;	// Dry volume units
		if (isset($g_rb_units[$system]["volume"])) foreach ($g_rb_units[$system]["volume"] as $key=>$val) $units[] = $key;	// volume units
		foreach ($g_rb_units[$system]["mass"] as $key=>$val) $units[] = $key;	// Mass units
		$units = array_unique($units);	// remove duplicates (dry/wet units could be duplicated in the units structure
		return $units;
	}
	
	/**
		Determines the unit system to use depending on the country the user is in.
		TODO: 
			This is kind of simple logic right now, a list should be constructed for what
		countries use what system and this should be checked.
	*/
	function getLocalSystem() {
		global $SMObj;
		$unitSystem="";
		// determine if we should be using Metric, U.S. Standard, or Imperial (mix)
		if ($SMObj->getUserCountry() == "us" || $SMObj->getUserCountry() == NULL)
			$unitSystem = "usa";
		else 
			$unitSystem = "metric";
		return $unitSystem;
	}
	
	/**
		Gets a list of units that are used locally, it is meant to filter out
		units that someone in a particular country would never use
	*/
	function getLocalUnits() {
		global $db_table_units, $alpha_sort_units, $DB_LINK, $LangUI;
		$localUnits = array();
		$unitSystem = Units::getLocalSystem();
		$localIDs = Units::getLocalUnitIDs($unitSystem);
		
		// Select all of the units from the database
		$sql = "SELECT unit_id,unit_desc FROM $db_table_units";
		// Sorting option
		if ($alpha_sort_units)
			$sql .=" ORDER BY unit_desc";
		
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, $LangUI->_('There was an error selecting the unit_id'), $sql);
		while (!$rc->EOF) {
			if (in_array($rc->fields['unit_id'], $localIDs)) {
				$id = $rc->fields['unit_id'];
				$localUnits[$id] = $rc->fields['unit_desc'];
			}
			$rc->MoveNext();
		}
		// Returns an associative array with id->desc
		return $localUnits;
	}
	
	/**
		Convert one type of unit to another type of unit
		@param $val value to convert
		@param $from unit to convert from (id)
		@param $to unit to convert to (id)
		@param $solid true if it is solid, false if not
	*/
	function convertTo($val, $from, $to, $solid) {
		global $SMObj, $LangUI, $DB_LINK, $g_rb_units;
		// If it is a unit type that cannot be converted then just return it (unit, pinch, clove...)
		if (in_array($to, $g_rb_units['static']) || in_array($from, $g_rb_units['static'])) return $val;
		// If it can be converted, try...
		$state = NULL;
		if ($solid == $DB_LINK->true) $state="dry";
		else $state="wet";
		$system = Units::getLocalSystem();
		$unitFrom = Units::getUnitInfo($from, $state, $system);
		$unitTo = Units::getUnitInfo($to, $state, $system);
		if ($unitFrom == NULL || $unitTo == NULL) {
			echo $LangUI->_('Conversion failure') . ':<br />';
			echo $LangUI->_('while trying to convert from unit') .  " '". $from . "' "
				. $LangUI->_('to unit') . " '". $to . "'<br />";
			return -1;
		} else
			return (round($val*$unitFrom[0]/$unitTo[0]*10000)/10000);
	}
	
	/**
		Gets information (coeff) about a specific unit, this looks in unitdef.php
		@param $id the id to look for
		@param $state the state to look in
		@param $system the system to look in
	*/
	function getUnitInfo($id, $state, $system) {
		global $g_rb_units;
		$toOK = false;
		$fromOK = false;;
		// we have several places we can check for info about this unit
		if ($system == "usa") {
			if (isset($g_rb_units[$system][$state][$id]))
			{
				$unitData = $g_rb_units[$system][$state][$id]; // get the info
			}
			else
			{
				$unitData = $g_rb_units[$system]["mass"][$id];
				// Maybe we know what to do with the unit if it as the other state
				if ($unitData == '') {
					if ($state == 'wet') $state = 'dry';
					else $state = 'wet';
					// last chance
					$unitData = $g_rb_units[$system][$state][$id]; // get the info
				}
			}
		} else if ($system == "metric") {
			// metric code here, just find the units where ever we can they will not be duplicated
			if ($g_rb_units[$system]["volume"][$id] != '') return $g_rb_units[$system]["volume"][$id];
			else if ($g_rb_units[$system]["mass"][$id] != '') return $g_rb_units[$system]["mass"][$id];
			else return ''; //we failed doh
		}
		return $unitData;
	}
	
	/**
		Returns an array with the index as the ID and the description and abbreviate as an array value, for all 
		the units in the database
		@return array of units
	*/
	function getUnits() {
		global $DB_LINK, $db_table_units;
		$units = array();
		$sql = "SELECT unit_id, unit_desc, unit_abbr FROM $db_table_units";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
		while (!$rc->EOF) {
			$id = $rc->fields['unit_id'];
			$units[$id] = array( $rc->fields['unit_desc'], $rc->fields['unit_abbr'] );
			$rc->MoveNext();
		}
		return $units;
	}
}
?>
