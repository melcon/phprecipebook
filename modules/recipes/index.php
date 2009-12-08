<?php
require_once("classes/Pager.class.php");

$where = isValidLetter($_GET['where'], "%" ) ? $_GET['where'] : '';
$page = isValidID($_GET['page']) ? $_GET['page'] : 1;

if (empty( $where )) {
        $where = $alphabet[0]; // show the letter 'a' by default to be faster
}

// Pull First Letters
$let = ":";
$sql = "SELECT DISTINCT LOWER(SUBSTRING(recipe_name, 1, 1)) AS A FROM $db_table_recipes";
$rc = $DB_LINK->Execute( $sql );
DBUtils::checkResult($rc, NULL, NULL, $sql);

while (!$rc->EOF) {
	if (ord($rc->fields[0]) >= 192 and ord($rc->fields[0]) <= 222 and ord($rc->fields[0]) != 215) // "Select lower"
		$rc->fields[0] = chr(ord($rc->fields[0])+32); // above doesn't work with ascii > 192, this fixes it
	$let .= $rc->fields[0]; // it could be "a" or "A", so just go with the only returned item
	$rc->MoveNext();
}
?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title"><?php echo $LangUI->_('Recipe Index');?></td>
</tr>
</table>

<table border="0" cellpadding="2" cellspacing="1" class="std">
<tr>
        <td width="100%" align="right"><?php echo $LangUI->_('Show');?>:</td>
		<td align="center" bgcolor="silver"><a href="./index.php?m=recipes&amp;where=%">
		<?php echo $LangUI->_('All');?></a></td>
<?php
        for ($a=0; isset($alphabet[$a]); $a++) { // List the alphabet
                $cu = $alphabet[$a];
                $cl = chr( ord( $cu )+32 );
                $bg = strpos($let, "$cl") > 0 ? "><a href=\"./index.php?m=recipes&amp;where=$cu\">$cu</a>" : 'style="background-color:#ffffff;">'.$cu;
                echo "<td align=\"center\" $bg</td>\n";
          }
?>
</tr>
</table>

<?php
    $sql_count = "SELECT count(*) FROM $db_table_recipes WHERE recipe_name LIKE '".$DB_LINK->addq($where, get_magic_quotes_gpc())."%' OR recipe_name LIKE '"
    . strtolower($DB_LINK->addq($where, get_magic_quotes_gpc())) . "%' ORDER BY recipe_name";

    $sql = "SELECT recipe_id,recipe_name FROM $db_table_recipes WHERE recipe_name LIKE '".$DB_LINK->addq($where, get_magic_quotes_gpc())."%' OR recipe_name LIKE '"
    . strtolower($DB_LINK->addq($where, get_magic_quotes_gpc())) . "%' ORDER BY recipe_name";

$pagerObj = new Pager($sql_count, $sql, $page);


while (!$pagerObj->dbResults->EOF) {
	echo '<br /><a href="./index.php?m=recipes&amp;a=view&amp;recipe_id='.$pagerObj->dbResults->fields['recipe_id'].'">'.$pagerObj->dbResults->fields['recipe_name'].'</a>';
	$pagerObj->dbResults->MoveNext();
}

echo "<br /><br /><center>";
$pagerObj->getPagerScript("./index.php?m=recipes&where=".$where);
$pagerObj->showPager($page);
echo "</center>";
?>

