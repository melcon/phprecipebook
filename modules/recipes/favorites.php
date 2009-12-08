<?php
require_once("classes/DBUtils.class.php");
// Determine if the user has access
if ($SMObj->getUserLoginID()=="") {
	die($LangUI->_('You must be a registered user in order to save favorites'));
}
?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title"><?php echo $LangUI->_('Favorites');?></td>
</tr>
</table>
<p>
<?php
$recipe_id = isValidID( $_GET['recipe_id'] ) ? $_GET['recipe_id'] : 0;
$mode = isset( $_REQUEST['mode'] ) ? $_REQUEST['mode'] : '';

if ($mode=="add") {
	$sql = "SELECT favorite_recipe FROM $db_table_favorites WHERE favorite_owner='" . $SMObj->getUserLoginID() . "' AND favorite_recipe=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc,NULL,NULL,$sql);
	if ($rc->RecordCount()>0) {
		echo "<font color=red>" . $LangUI->_('Favorite is already in list!') . "</font>";
	} else {
		$sql = "INSERT INTO $db_table_favorites (favorite_owner, favorite_recipe) VALUES ('" . $SMObj->getUserLoginID() . "',".$DB_LINK->addq($recipe_id, get_magic_quotes_gpc()).")";
		$rc = $DB_LINK->Execute( $sql );
		DBUtils::checkResult($rc,$LangUI->_('Favorite Successfully Added'),$LangUI->_('Failed to add Favorite'),$sql);
	}
} else if ($mode=="delete") {
	$sql = "DELETE FROM $db_table_favorites WHERE favorite_owner='" . $SMObj->getUserLoginID() . "' AND favorite_recipe=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc,$LangUI->_('Favorite Deleted'),$LangUI->_('Failed to remove favorite'),$sql);
}

// as default just show the favorite recipes
$sql = "SELECT recipe_name,favorite_recipe FROM $db_table_favorites
		LEFT JOIN $db_table_recipes ON recipe_id = favorite_recipe
		WHERE favorite_owner='" . $SMObj->getUserLoginID() . "'";
$rc = $DB_LINK->Execute( $sql );
DBUtils::checkResult($rc,NULL,NULL,$sql);

if ($rc->RecordCount()>0) {
?>
<p>
<table cellspacing="1" cellpadding="2" border=0 width="50%" class="data">
<tr>
	<th><?php echo $LangUI->_('Dish Name');?></th>
	<th width=70><?php echo $LangUI->_('Remove');?></th>
</tr>

<?php
	while (!$rc->EOF) {
		echo '<tr>';
		echo '<td align=center><a href="./index.php?m=recipes&a=view&recipe_id='.$rc->fields['favorite_recipe'].'">'.$rc->fields['recipe_name'].'</a></td>';
		echo '<td align=center width=70><a href="./index.php?m=recipes&a=favorites&mode=delete&recipe_id=' . $rc->fields['favorite_recipe'] . '">X</a></td></tr>';
		$rc->MoveNext();
	}
	echo "</table>\n";
}
?>
