<!-- Load Data Section -->
<?php
    $store_id = isValidID( $_GET['store_id'] ) ? $_GET['store_id'] : 0;

	$sql = "SELECT store_name,store_layout FROM $db_table_stores WHERE store_id=" .
		$DB_LINK->addq($store_id, get_magic_quotes_gpc()) . " AND store_owner='".$SMObj->getUserLoginID() . "'";
	$rc = $DB_LINK->Execute($sql);
	DBUtils::checkResult($rc, NULL, NULL, $sql);

	$store_name = $rc->fields['store_name'];
	$store_layout = split(',', $rc->fields['store_layout']);

	$sql = "SELECT location_id, location_desc FROM $db_table_locations";
	$rc = $DB_LINK->Execute($sql);
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	$locations = DBUtils::createList($rc, 'location_id', 'location_desc');
?>
<!-- Navigation section -->
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php echo $LangUI->_('Store Layout For:  ') . $store_name . "<br>";?>
	</td>
</tr>
</table>
<P>
<table cellspacing="1" cellpadding="2" border="0" class="ing" width="40%">
<tr>
	<th><?php echo $LangUI->_('Section Name');?></th>
</tr>
<?php
	foreach ($store_layout as $section)
	{
		if ($section)
		{
			echo "<tr>";
			if (isset($locations[$section]))
				echo '<td align="center">' . $locations[$section] . "</td>";
			else
				echo '<td align="center"><b>section does not exist: ' . $section . '</b></td>';
			echo "</tr>";
		}
	}
?>
</table>
