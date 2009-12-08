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
	array_unshift_assoc($locations, 0, ""); // add an empty element to the list
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
<form action="./index.php?m=admin&a=stores&dosql=update_layout" method="POST">
<input type="hidden" name="store_id" value="<?php echo $store_id;?>">

<table cellspacing="2" cellpadding="4" border="0" class="ing">
<tr>
	<th><?php echo $LangUI->_('Section Name');?></th>
</tr>
<?php
	for ($i=0; $i < count($locations); $i++)
	{
		$selected = 0;
		echo "<tr>";
		echo "<td>";
		if ($i < count($store_layout))
			$selected = $store_layout[$i];

		echo DBUtils::arrayselect( $locations, 'section_id_'.$i, 'size=1', $selected);
		echo "</td>";
		echo "</tr>";
	}
?>
<tr>
	<th>
		<input type="submit" value="<?php echo $LangUI->_("Save Layout");?>" class="button">
	</th>
</tr>
</table>
</form>
