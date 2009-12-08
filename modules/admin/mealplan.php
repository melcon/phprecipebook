<?php
require_once("classes/DBUtils.class.php");
?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Edit Meal Planner Options');?></td>
</tr>
</table>
<P>
<?php
// The listing of the users
if ($SMObj->checkAccessLevel($SMObj->getSuperUserLevel())) {
?>
<form action="./index.php?m=admin&a=update_mealplan" method="POST">
<table  cellspacing="1" cellpadding="2" border="0" class="data">
	<tr>
		<td><?php echo $LangUI->_('Starting Weekday');?>:</td>
		<td>
			<?php
			$sql = "SELECT setting_mp_day FROM $db_table_settings";
			$result = $DB_LINK->Execute($sql);
			DBUtils::checkResult($result, NULL, NULL, $sql); // Error check
			$startWeekDay = $result->fields['setting_mp_day'];
			// Workaround to put the values in the dropdown
			$arr = array(
				"0" => $LangUI->_('Sunday'),
				"1" => $LangUI->_('Monday'),
				"2" => $LangUI->_('Tuesday'),
				"3" => $LangUI->_('Wednesday'),
				"4" => $LangUI->_('Thursday'),
				"5" => $LangUI->_('Friday'),
				"6" => $LangUI->_('Saturday')
			);
			echo DBUtils::arraySelect( $arr, 'startday', 'size=1', $startWeekDay );
			?>
		</td>
		<td>
			<input type="submit" value="<?php echo $LangUI->_('Update');?>" class="button">
		</td>
	</tr>
</table>
</form>
<?php
}
?>
