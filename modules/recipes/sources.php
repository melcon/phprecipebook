<?php
require_once("classes/DBUtils.class.php");

$source_id = isValidID( $_REQUEST['source_id'] ) ? $_REQUEST['source_id'] : 0;

// We need to get the data now
$sql = "SELECT source_title,source_desc FROM $db_table_sources";

if ($source_id > 0)
	$sql .= " WHERE source_id = " . $DB_LINK->addq($source_id, get_magic_quotes_gpc());

$sources = $DB_LINK->Execute($sql);
DBUtils::checkResult($sources, NULL, NULL, $sql); // Error check
?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="center" class="title">
		<?php
			if ($source_id == 0)
			{
				echo $LangUI->_('Sources');
			}
			else
			{
				echo $LangUI->_('Source');
			}
		?>
	</td>
</tr>
</table>
<p>
<table cellspacing="5" cellpadding="2" border="0" class="ing" width="100%">
<?php
while (!$sources->EOF) {
	echo "<tr><td nowrap width=\"25%\"><b>" . $sources->fields['source_title'] . "</b></td>\n";
	echo "<td>" . $sources->fields['source_desc'] . "</td></tr>\n";
	$sources->MoveNext();
}
?>
</table>
<a href="index.php?m=admin&a=customize">[Edit Sources]</a>
</p>
