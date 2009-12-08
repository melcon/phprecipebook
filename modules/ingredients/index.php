<?php
require_once("classes/Pager.class.php");

$where = isValidLetter($_GET['where'],"%") ? $_GET['where'] : '';
$page = isValidID($_GET['page']) ? $_GET['page'] : 1;

if (empty( $where )) {
        $where = $alphabet[0]; // Set the default on to 'a' so that we don't have everything listed (faster this way)
}

// Pull First Letters
$let = ":";
$sql = "SELECT DISTINCT LOWER(SUBSTRING(ingredient_name, 1, 1)) AS A FROM $db_table_ingredients";
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
        <td align="left" class="title"><?php echo $LangUI->_('Ingredient Index');?></td>
</tr>
</table>

<table border="0" cellpadding="2" cellspacing="1" class="std">
<tr>
        <td width="100%" align="right"><?php echo $LangUI->_('Show');?>:</td>
        <td align="center" bgcolor="silver"><a href="./index.php?m=ingredients&where=%">
		<?php echo $LangUI->_('All');?></a></td>
<?php
        for ($a=0; isset($alphabet[$a]); $a++) { // List the alphabet
                $cu = $alphabet[$a];
                $cl = chr( ord( $cu )+32 );
                $bg = strpos($let, "$cl") > 0 ? "><a href=\"./index.php?m=ingredients&amp;where=$cu\">$cu</a>" : 'style="background-color:#ffffff;">'.$cu;
                echo "<td align=\"center\" $bg</td>\n";
          }
?>
</tr>
</table>
<?php
$sql_count = "SELECT count(*) FROM $db_table_ingredients WHERE ingredient_name LIKE '". $DB_LINK->addq($where, get_magic_quotes_gpc()) ."%' OR ingredient_name LIKE '" . strtolower($where) .
			"%' ORDER BY ingredient_name";
$sql = "SELECT ingredient_id,ingredient_name FROM $db_table_ingredients WHERE ingredient_name LIKE '". $DB_LINK->addq($where, get_magic_quotes_gpc()) ."%' OR ingredient_name LIKE '" . strtolower($where) .
			"%' ORDER BY ingredient_name";
$pagerObj = new Pager($sql_count, $sql, $page);


while (!$pagerObj->dbResults->EOF)
{
	echo '<br /><a href="./index.php?m=ingredients&amp;a=view&amp;ingredient_id='.$pagerObj->dbResults->fields['ingredient_id'].'">'.$pagerObj->dbResults->fields['ingredient_name']."</a>\n";
    $pagerObj->dbResults->MoveNext();
}

echo "<br /><br /><center>";
$pagerObj->getPagerScript("./index.php?m=ingredients&where=".$where);
$pagerObj->showPager($page);
echo "</center>";
?>

