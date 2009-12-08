<?php
// Exit if not admin
if (!$SMObj->checkAccessLevel($SMObj->getSuperUserLevel()))
	die($LangUI->_('You must have Administer privilages in order to customize the database!'));

$startday = (isset( $_POST['startday'] ) && is_numeric($_POST['startday'] )) ? $_POST['startday'] : '';

// update the entry to the new value
$sql = "UPDATE $db_table_settings SET setting_mp_day=" . $DB_LINK->addq($startday, get_magic_quotes_gpc());
$result = $DB_LINK->Execute($sql);
DBUtils::checkResult($result, NULL, NULL, $sql);
echo $LangUI->_('Meal Planner Settings Updated') . "<br />";
?>
