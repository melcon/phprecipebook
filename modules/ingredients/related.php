<?php
require_once("classes/DBUtils.class.php");

$ingredient_id = isValidID( $_GET['ingredient_id'] ) ? $_GET['ingredient_id'] : 0;

?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Recipes');?></td>
</tr>
</table>
<p>
<?php

$sql = "SELECT map_recipe,recipe_name FROM $db_table_ingredientmaps
		LEFT JOIN $db_table_recipes ON recipe_id=map_recipe
		WHERE map_ingredient=" . $DB_LINK->addq($ingredient_id, get_magic_quotes_gpc());

$rc = $DB_LINK->Execute( $sql );
DBUtils::checkResult($rc, NULL, NULL, $sql);
// Test to see if we found anything
if ($rc->RecordCount() == 0) echo $LangUI->_('No matching recipes found') . '.';
// Display all of the matching recipes that use said ingredient
while (!$rc->EOF) {
	echo '<a href="index.php?m=recipes&a=view&recipe_id=' . $rc->fields['map_recipe'] . '">' . $rc->fields['recipe_name'] . "</a><br />\n";
	$rc->MoveNext();
}

?>
