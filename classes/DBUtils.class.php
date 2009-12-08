<?php
/**
	This class provides some useful functions to interact with the database.  These are generally generic functions that add some functionlity
	that ADOdb does not provide, or that I am not happy with what they include.
*/
class DBUtils {
	/**
		Checks the recorded version of the database against the version of the software that we are running, if the
		versions do not sync up then a warning is printed.
	*/
	function checkDBSchemaVersion() {
		global $DB_LINK, $LangUI, $g_rb_project_version, $db_table_settings;
		$sql = "SELECT setting_version FROM $db_table_settings";
		$rc = $DB_LINK->Execute($sql);
		if ($rc->fields['setting_version'] < $g_rb_project_version)
		{
			return (
				$LangUI->_('Warning: You are running PHPRecipeBook Version ') .
				$g_rb_project_version . " " .
				$LangUI->_(' with DB Schema Version ') .
				$rc->fields['setting_version'] .
				"!<br />"
			);
		}
		else
		{
			return (
				$LangUI->_('Program Version: ') .
				$g_rb_project_version . ", " .
				$LangUI->_('DB Schema Version: ') .
				$rc->fields['setting_version'] .
				"<br />"
			);
		}
	}

	/**
		Function: getSequenceValue
		Description: returns a string that can be used to
		retrieve the current value of a sequence in the database
		@param:  sequence_name is only used for postgreSQL and can be
			safely ignored for mysql.
	*/
	function getSequenceValue($sequence_name) {
		global $g_rb_database_type, $DB_LINK, $LangUI;
		$sql = "";

		if ($g_rb_database_type=="postgres")
			$sql = "SELECT currval('" . $DB_LINK->addq($sequence_name, get_magic_quotes_gpc()) . "')";
		else if ($g_rb_database_type=="mysql")
			$sql = "SELECT LAST_INSERT_ID()";

		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc, NULL, NULL, $sql);
		return ($rc->fields[0]);
	}

	/**
		Creates a HTML select field that is based on columns in the database
		@param $arr A Hash Array that is used as the key and value
		@param $select_name The name of the HTML select field
		@param $select_attribs Any addition attributes needed to be specified (font...)
		@param $selected The name of the select field
		@return A String that contains a HTML formated select field
	*/
	function arraySelect( $arr, $select_name, $select_attribs, $selected ) {
		echo "</pre>";
		$s = "<select class=\"field_listbox\" name=\"$select_name\" $select_attribs>\n";
		foreach ($arr as $k=>$v) {
			$s .= '<option value="'.$k.'"'.($k == $selected ? ' selected' : '').'>'.$v . "\n";
		}
		$s .= '</select>';
		return $s;
	}

	/**
		Gets a Column out of the database and returns them as a ADOdb type result
		@param $table the table to query from
		@param $valFld the first field to select
		@param $keyFld The seconf field to select
		@param $order how to order the result (should be either $valFld or $keyFld)
		@return A ADOdb result
	*/
	function fetchColumn( $table, $valFld, $keyFld='', $order=''  ) {
		global $DB_LINK;

        $table = $DB_LINK->addq($table, get_magic_quotes_gpc());
        $valFld = $DB_LINK->addq($valFld, get_magic_quotes_gpc());
        $keyFld = $DB_LINK->addq($keyFld, get_magic_quotes_gpc());
        $order = $DB_LINK->addq($order, get_magic_quotes_gpc());

		$sql = "SELECT $valFld".($keyFld ? ",$keyFld" : "")." FROM $table".($order ? " ORDER BY $order" : "");
		$rc = $DB_LINK->Execute( $sql );
		DBUtils::checkResult($rc, NULL, NULL, $sql);
		return $rc;
	}

	/**
		Takes a result from adodb query and creates an associative array with $id as the key and $name as the value
	*/
	function createList( $rc, $id, $name ) {
		$list = array();
		if (!is_array($rc->fields)) return ($list);
		reset($rc->fields);
		$list = array();
		while (!$rc->EOF) {
			$q = trim($rc->fields[$id]);
			// only use entity decoding if it is supported
			if (version_compare(phpversion(), "4.3.0", ">=")) $list[$q] = trim($rc->fields[$name]);
			else $list[$q] = trim($rc->fields[$name]);
			$rc->MoveNext();
		}
		return $list;
	}

	/**
		Checks the result from a adodb query to see if it is true or not. If it is false then it prints out a message
		and the error passed into the program. If all is good then it will print the success message if given
		@param $rc the adodb result structure
		@param $smsg The message to print if it was successful (optional)
		@param $fmsg The message to print if the query failed (optional)
		@param $sql SQL code that failed, if available
		@return true if $rc is true, false if $rc is false
	*/
	function checkResult($rc, $smsg=NULL, $fmsg=NULL, $sql) {
		global $LangUI, $DB_LINK;
		if (!$rc) {
			if (!$fmsg)
				echo $LangUI->_('There was an error') . ":<br />";
			else
				echo $fmsg . "<br />";
			if ($sql)
				echo $sql . "<br>";
			echo $DB_LINK->ErrorMsg() . "<br />";
			return false;
		} else if ($smsg) {
			echo $smsg . "<br />";
			return true;
		}
	}

	/**
		Converts a database ISO timestamp into a set Month/Day/Year Hour/Minute format
		@param $time the time formated in database ISO type (postgres as example)
		@return time and date in array
	*/
	function formatTimeStamp($time) {
		if (preg_match("/^(\d*?)-(\d*?)-(\d*?) (\d*?):(\d*?):/", $time, $matches)) //postgres default
			return $matches;
		else if (preg_match("/^(....)(..)(..)(..)(..)/", $time, $matches)) //mysql default
			return $matches;
	}

	/**
		Converts a Database ISO date into an array that can be formated
		@param $date the database date
		@return array of elements of the date (Year, Month, Day)
	*/
	function formatDate($date) {
		$date = trim($date);
		preg_match("/^(\d*?)-(\d*?)-(\d*?)$/", $date, $matches);
		return $matches;
	}

	/**
		Takes a string date of the form m-d-Y and converts to DB ISO format with
		the leading 0's for the m and d fields
	*/
	function dbDate($date) {
		$date = trim($date);
		preg_match("/^(\d*?)-(\d*?)-(\d*?)$/", $date, $matches);
		$month = $matches[1];
		$day = $matches[2];
		$year = $matches[3];
		if (strlen($month) < 2) $month = "0" . $month;
		if (strlen($day) < 2) $day = "0" . $day;
		return ($year . '-' . $month . '-' . $day);
	}

}
?>