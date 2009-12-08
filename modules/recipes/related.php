<?php
require_once("classes/DBUtils.class.php");

$recipe_id = isValidID( $_GET['recipe_id'] ) ? $_GET['recipe_id'] : 0;

?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Parent recipes');?></td>
</tr>
</table>
<p>
<?php

$sql = "SELECT related_parent,recipe_name FROM $db_table_related_recipes
		LEFT JOIN $db_table_recipes ON recipe_id=related_parent
		WHERE related_child=". $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());

$rc = $DB_LINK->Execute( $sql );
DBUtils::checkResult($rc, NULL, NULL, $sql);
// Test to see if we found anything
if ($rc->RecordCount() == 0) echo $LangUI->_('No matching recipes found') . '.';
// Display all of the matching recipes that use said ingredient
while (!$rc->EOF) {
	echo '<a href="index.php?m=recipes&amp;a=view&recipe_id=' . $rc->fields['related_parent'] . '">' . $rc->fields['recipe_name'] . "</a><br />\n";
	$rc->MoveNext();
}

?>
