<?php
require_once("classes/DBUtils.class.php");
?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title"><?php echo $LangUI->_('Restaurants');?></td>
</tr>
</table>
<p>
<?php
// as default just show the favorite recipes
$sql = "SELECT * FROM $db_table_restaurants	
		LEFT JOIN $db_table_prices ON price_id = restaurant_price ORDER BY restaurant_name";
$rc = $DB_LINK->Execute( $sql );
DBUtils::checkResult($rc,NULL,NULL,$sql);

if ($rc->RecordCount()>0) {
?>
<p>
<table cellspacing="2" cellpadding="4" border="0" class="data">
<tr>
	<th><?php echo $LangUI->_('Restaurant');?></th>
	<th><?php echo $LangUI->_('Phone');?></th>
	<th><?php echo $LangUI->_('Address');?></th>
	<th><?php echo $LangUI->_('Hours');?></th>
	<th><?php echo $LangUI->_('Price');?></th>
	<th><?php echo $LangUI->_('Credit');?></th>
	<th><?php echo $LangUI->_('Menu');?></th>
	<th><?php echo $LangUI->_('Delivery');?></th>
	<th><?php echo $LangUI->_('Carry Out');?></th>
	<th><?php echo $LangUI->_('Dine In');?></th>
	<th><?php echo $LangUI->_('Notes');?></th>
	<?php
	// Determine if the user has access to add new restaurants
	if ($SMObj->checkAccessLevel("EDITOR")) {
		echo "<th colspan=\"2\">" . $LangUI->_('Actions') . "</th>\n";
	}
	?>
</tr>

<?php
	while (!$rc->EOF) {
		echo "<tr>\n";
		if ($rc->fields['restaurant_website'] != '') 
		{
			echo '<td><a href="' . $rc->fields['restaurant_website'] . '">' . $rc->fields['restaurant_name'] . '</a></td>';
		} 
		else 
		{
		    echo '<td>' . $rc->fields['restaurant_name'] . '</td>';
		}
		echo '<td>' . $rc->fields['restaurant_phone'] . '</td>';
		echo '<td><a href="http://www.mapquest.com/maps/map.adp?country=' . 
			$rc->fields['restaurant_country'] . '&addtohistory=&address=' . 
			preg_replace('/\s/', '+', $rc->fields['restaurant_address']) . '&city=' .
			preg_replace('/\s/', '+', $rc->fields['restaurant_city']) . '&state=' .
			$rc->fields['restaurant_state'] . '&zipcode=' . $rc->fields['restaurant_zip'] .'&homesubmit=Get+Map">' . 
			$rc->fields['restaurant_address'] . '<br />' .
			$rc->fields['restaurant_city'] . ', ' . $rc->fields['restaurant_state'] . ' ' . 
			$rc->fields['restaurant_zip'] . '<br />' .
			$rc->fields['restaurant_country'] . '</a></td>';
		echo '<td>' . stripslashes($rc->fields['restaurant_hours']) . '</td>';
		echo '<td>' . $rc->fields['price_desc'] . '</td>';
		echo '<td align="center">';
		if ($rc->fields['restaurant_credit'] == $DB_LINK->true) {
			echo $LangUI->_('Yes');
		} else {
			echo $LangUI->_('No');
		}
		echo '</td><td align="center">';
		if ($rc->fields['restaurant_picture_type'] != '' || $rc->fields['restaurant_menu_text'] != '') {
			echo "<a href=\"#\" onClick=window.open('index.php?m=restaurants&a=view&print=yes&restaurant_id=" . $rc->fields['restaurant_id'] . 
				"','restaurant_menus','height=400,width=700,toolbar=1,menubar=0,status=1,location=1,scrollbars=1')>";
			echo '<img src="themes/' . $g_rb_theme . '/images/view.png" alt="View" border="0"></a>';
		}
		echo '</td><td align="center">';
		if ($rc->fields['restaurant_delivery'] == $DB_LINK->true) {
			echo '<img src="themes/' . $g_rb_theme . '/images/checkmark.png" alt="X">';
		}
		echo '</td><td align="center">';
		if ($rc->fields['restaurant_carry_out'] == $DB_LINK->true) {
			echo '<img src="themes/' . $g_rb_theme . '/images/checkmark.png" alt="X">';
		}
		echo '</td><td align="center">';
		if ($rc->fields['restaurant_dine_in'] == $DB_LINK->true) {
			echo '<img src="themes/' . $g_rb_theme . '/images/checkmark.png" alt="X">';
		}
		echo '<td>' . stripslashes($rc->fields['restaurant_comments']) . '</td>';

		if ($SMObj->checkAccessLevel("EDITOR")) {
			echo '<td align="center">';
			echo '<a href="index.php?m=restaurants&a=addedit&restaurant_id=' . $rc->fields['restaurant_id'] . '">Edit</a>';
			echo '</td><td align="center">';
			echo '<a href="index.php?m=restaurants&dosql=delete&restaurant_id=' . $rc->fields['restaurant_id'] . '">Delete</a>';
		}
		echo "</td></tr>\n";
		$rc->MoveNext();
	}
	echo "</table>\n";
} else {
	echo $LangUI->_('No restaurants currently listed');
}
?>
