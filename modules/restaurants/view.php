<?php
require_once("classes/DBUtils.class.php");

$restaurant_id = isValidID( $_GET['restaurant_id'] ) ? $_GET['restaurant_id'] : 0;

$sql = "SELECT restaurant_name,restaurant_menu_text,restaurant_picture_type FROM $db_table_restaurants WHERE restaurant_id=" . $DB_LINK->addq($restaurant_id, get_magic_quotes_gpc());
$rc = $DB_LINK->Execute($sql);
DBUtils::checkResult($rc,NULL,NULL,$sql);

$restaurant_name = $rc->fields['restaurant_name'];
// Only use the entity decode if PHP supports it
if (version_compare(phpversion(),"4.3.0",">=")) {
	$restaurant_menu_text = html_entity_decode($rc->fields['restaurant_menu_text']);
} else {
	$restaurant_menu_text = $rc->fields['restaurant_menu_text'];
}

$restaurant_picture_type = $rc->fields['restaurant_picture_type'];
?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Restaurant Menu') . ': ' . $restaurant_name;?></td>
</tr>
</table>
<hr size=1 noshade>
<?php
if ($restaurant_picture_type != '') {
	echo '<a href="modules/restaurants/view_picture.php?restaurant_id=' . $restaurant_id . '">' . $LangUI->_('Scanned Menu') . '</a><p>';
	echo '<hr size=1 noshade>';
}

if ($restaurant_menu_text != '') {
	echo $restaurant_menu_text . '<p>';
}

