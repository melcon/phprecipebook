<?php
require_once("classes/DBUtils.class.php");
require_once("classes/Restaurant.class.php");

// Read in the POST values
$restaurant_id = isValidID( $_GET['restaurant_id'] ) ? $_GET['restaurant_id'] : 0;
$restaurant_name = isset( $_POST['restaurant_name'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_name'] ), ENT_QUOTES ) : '';
$restaurant_website = isset( $_POST['restaurant_website'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_website'] ), ENT_QUOTES ) : '';
$restaurant_address = isset( $_POST['restaurant_address'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_address'] ), ENT_QUOTES ) : '';
$restaurant_city = isset( $_POST['restaurant_city'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_city'] ), ENT_QUOTES ) : '';
$restaurant_state = isset( $_POST['restaurant_state'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_state'] ), ENT_QUOTES ) : '';
$restaurant_zip = isset( $_POST['restaurant_zip'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_zip'] ), ENT_QUOTES ) : '';
$restaurant_country = isset( $_POST['restaurant_country'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_country'] ), ENT_QUOTES ) : '';
$restaurant_phone = isset( $_POST['restaurant_phone'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_phone'] ), ENT_QUOTES ) : '';
$restaurant_hours = isset( $_POST['restaurant_hours'] ) ?  addslashes( $_POST['restaurant_hours'] ) : '';

$restaurant_price = isValidID( $_POST['restaurant_price'] ) ? $_POST['restaurant_price'] : 0;

$restaurant_delivery = isset( $_POST['restaurant_delivery'] ) ? $DB_LINK->true : $DB_LINK->false;
$restaurant_dine_in = isset( $_POST['restaurant_dine_in'] ) ? $DB_LINK->true : $DB_LINK->false;
$restaurant_carry_out = isset( $_POST['restaurant_carry_out'] ) ? $DB_LINK->true : $DB_LINK->false;

$restaurant_credit = isset( $_POST['restaurant_credit'] ) ? $DB_LINK->true : $DB_LINK->false;

$restaurant_picture_oid = isset($_POST['restaurant_picture_oid']) ? $_POST['restaurant_picture_oid'] : 'NULL'; // to keep postgres clean
$restaurant_picture_type = isset ($_FILES['restaurant_picture']['type']) ? $_FILES['restaurant_picture']['type'] : '';
$remove_picture = isset ($_POST['remove_picture']) ? $_POST['remove_picture'] : '';

$restaurant_menu_text = isset( $_POST['restaurant_menu_text'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_menu_text']), ENT_QUOTES ) : '';
$restaurant_comments = isset( $_POST['restaurant_comments'] ) ?
	htmlentities( stripslashes( $_POST['restaurant_comments'] ), ENT_QUOTES ) : '';

// Determine if the user has access to add new restaurants
if (!$SMObj->checkAccessLevel("EDITOR")) {
	die($LangUI->_('You must have Editor or higher access to add/edit restaurants'));
}

$restObj = new Restaurant($restaurant_id,
						  $restaurant_name,
						  $restaurant_website,
						  $restaurant_address,
						  $restaurant_city,
						  $restaurant_state,
						  $restaurant_zip,
						  $restaurant_country,
						  $restaurant_phone,
						  $restaurant_hours,
						  $restaurant_menu_text,
						  $_FILES['restaurant_picture'],
						  $restaurant_picture_type,
						  $restaurant_picture_oid,
						  $restaurant_comments,
						  $restaurant_price,
						  $restaurant_delivery,
						  $restaurant_carry_out,
						  $restaurant_dine_in,
						  $restaurant_credit);
// Add or Update the restaurant
$restObj->addUpdate();
// Handle the picture
if ($remove_picture=="yes") {
	$restObj->deletePicture();
} else {
	$restObj->updatePicture();
}
// Now that wasn't so painful was it?
if ($restaurant_id) {
	echo $LangUI->_('restaurant successfully updated');
} else {
	echo $LangUI->_('restaurant successfully added');
}
echo "<p>";
?>