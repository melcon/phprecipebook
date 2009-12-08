<?php
require_once("classes/DBUtils.class.php");

$restaurant_id = isValidID( $_GET['restaurant_id'] ) ? $_GET['restaurant_id'] : 0;

// Determine if the user has access to add new restaurants
if (!$SMObj->checkAccessLevel("EDITOR")) {
	die($LangUI->_('You must have Editor or higher access to add/edit restaurants'));
}

?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php
			if ($recipe_id) {
				echo $LangUI->_('Edit Restaurant');
			} else {
				echo $LangUI->_('Add Restaurant');
			}
		?>
	</td>
</tr>
<?php
if ($restaurant_id) {
	// Do the query to get the values
	$sql = "SELECT * FROM $db_table_restaurants WHERE restaurant_id=" . $DB_LINK->addq($restaurant_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute($sql);
	DBUtils::checkResult($rc,NULL,NULL,$sql);

	$restaurant_name = $rc->fields['restaurant_name'];
	$restaurant_website = $rc->fields['restaurant_website'];
	$restaurant_cuisine = $rc->fields['restaurant_cuisine'];
	$restaurant_address = $rc->fields['restaurant_address'];
	$restaurant_city = $rc->fields['restaurant_city'];
	$restaurant_state = $rc->fields['restaurant_state'];
	$restaurant_zip = $rc->fields['restaurant_zip'];
	$restaurant_country = $rc->fields['restaurant_country'];
	$restaurant_phone = $rc->fields['restaurant_phone'];
	$restaurant_hours = stripslashes($rc->fields['restaurant_hours']);
	$restaurant_price = $rc->fields['restaurant_price'];
	$restaurant_delivery = $rc->fields['restaurant_delivery'];
	$restaurant_carry_out = $rc->fields['restaurant_carry_out'];
	$restaurant_dine_in = $rc->fields['restaurant_dine_in'];
	$restaurant_credit = $rc->fields['restaurant_credit'];
	$restaurant_menu_text = stripslashes($rc->fields['restaurant_menu_text']);
	$restaurant_comments = $rc->fields['restaurant_comments'];
}
?>
</table>

<p>
<table  cellspacing="1" cellpadding="2" border="0" class="data">
<form name="restaurant_form" enctype="multipart/form-data" action="index.php?m=restaurants&restaurant_id=<?php echo $restaurant_id;?>" method="post">
<input type="hidden" name="dosql" value="update">

<tr>
	<td><?php echo $LangUI->_('Restaurant Name');?>:</td>
	<td><input type="text" size="40" name="restaurant_name" value="<?php echo $restaurant_name;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Restaurant Website');?>:</td>
	<td><input type="text" size="40" name="restaurant_website" value="<?php echo $restaurant_website;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Address');?>:</td>
	<td><input type="text" size="40" name="restaurant_address" value="<?php echo $restaurant_address;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('City');?>:</td>
	<td><input type="text" size="20" name="restaurant_city" value="<?php echo $restaurant_city;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('State');?>:</td>
	<td><input type="text" size="2" maxsize="2" name="restaurant_state" value="<?php echo $restaurant_state;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Zip');?>:</td>
	<td><input type="text" size="10" name="restaurant_zip" value="<?php echo $restaurant_zip;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Country');?>:</td>
	<td><input type="text" size="40" name="restaurant_country" value="<?php echo $restaurant_country;?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Phone');?>:</td>
	<td><input type="text" size="15" name="restaurant_phone" value="<?php echo $restaurant_phone;?>"></td>
</tr>
<tr>
	<td valign="top"><?php echo $LangUI->_('Hours');?>:</td>
	<td><textarea name="restaurant_hours" cols="40" rows="4"><?php echo $restaurant_hours;?></textarea></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Price Range');?>:</td>
	<td>
	<?php
		$sql = "SELECT price_desc, price_id FROM $db_table_prices ORDER BY price_desc";
		$rc = $DB_LINK->Execute($sql);
		DBUtils::checkResult($rc,NULL,NULL,$sql);
		echo $rc->GetMenu2('restaurant_price', $restaurant_price, false);?>
	</td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Dining Options');?>:</td>
	<td>
		<input type="checkbox" name="restaurant_delivery" value="yes"
			<?php echo (($restaurant_delivery == $DB_LINK->true) ? ' checked' : '');?>> <?php echo $LangUI->_('Delivery');?>
		<input type="checkbox" name="restaurant_carry_out" value="yes"
			<?php echo (($restaurant_carry_out == $DB_LINK->true) ? ' checked' : '');?>> <?php echo $LangUI->_('Carry Out');?>
		<input type="checkbox" name="restaurant_dine_in" value="yes"
			<?php echo (($restaurant_dine_in == $DB_LINK->true) ? ' checked' : '');?>> <?php echo $LangUI->_('Dine In');?>
	</td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Credit');?>:</td>
	<td>
		<input type="checkbox" name="restaurant_credit" value="yes"
			<?php echo (($restaurant_credit == $DB_LINK->true) ? ' checked' : '');?>>
	</td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Menu Picture');?>:</td>
	<td>
		<input type="hidden" name="restaurant_picture_oid" value="<?php echo $restaurant_picture_oid;?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $g_rb_max_picture_size;?>">
		<input type="file" name="restaurant_picture">
		<input type="checkbox" name="remove_picture" value="yes"> <?php echo $LangUI->_('Remove Picture');?>
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $LangUI->_('Menu Text');?>:</td>
	<td><textarea rows="15" cols="40" name="restaurant_menu_text"><?php echo $restaurant_menu_text;?></textarea></td>
</tr>
<tr>
	<td valign="top"><?php echo $LangUI->_('Notes');?>:</td>
	<td><textarea rows="10" cols="40" name="restaurant_comments"><?php echo $restaurant_comments;?></textarea></td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="submit" value="<?php echo ($restaurant_id ? $LangUI->_('Update Restaurant') : $LangUI->_('Add Restaurant'));?>" class="button">
	</td>
</tr>
</table>
</form>