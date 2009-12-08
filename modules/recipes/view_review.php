<P>
<hr size=1 noshade>
<br />
<b><?php echo $LangUI->_('Average Rating');?></b>:<br /><br />
<?php
$sql = "SELECT avg(rating_score) FROM $db_table_ratings WHERE rating_recipe=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
$rc = $DB_LINK->Execute($sql);
// Error check
DBUtils::checkResult($rc, NULL, NULL, $sql);

// Print out the rating
$avg = $rc->fields[0] + 0; // cheap way of removing the 0's

if ($avg == 0)
{
	echo $LangUI->_('Not Rated');
}
else
{
	$num_stars = 0;
	// give full stars
	while ($avg >= 1)
	{
		echo '<img src="themes/' . $g_rb_theme . '/images/filled_star.gif" border="0" />';
		$avg--;
		$num_stars++;
	}

	// award a half star of greater then .65
	if ($avg >= 0.65)
	{
		echo '<img src="themes/' . $g_rb_theme . '/images/filled_star.gif" border="0" />';
		$num_stars++;
	}
}
?>

<p><br />
<b><?php echo $LangUI->_('Reviews');?></b>:<br />
<?php
$sql = "SELECT review_owner, review_comments, review_date, user_name FROM $db_table_reviews
		LEFT JOIN $db_table_users ON user_login = review_owner WHERE review_recipe=".$DB_LINK->addq($recipe_id, get_magic_quotes_gpc())." ORDER BY review_date";
$rc = $DB_LINK->Execute($sql);
// Error check
DBUtils::checkResult($rc, NULL, NULL, $sql);

echo '<br /><br />';
if ($rc->RecordCount()==0) echo $LangUI->_('This recipe has not been reviewed');
else {
	echo '<table cellspacing="1" cellpadding="5" border="0" class="ing" width="80%">';
	while (!$rc->EOF) {
		$dateTime = DBUtils::formatTimeStamp($rc->fields['review_date']);
		echo "<tr><th align=left>" . $rc->fields['user_name'] . ', ';
		echo "";
		echo ($dateTime[2]+0) . "/" . ($dateTime[3]+0) . "/" . $dateTime[1] . " ";
		echo ($dateTime[4] % 12) . ":" . $dateTime[5] . ' ';
		echo ($dateTime[4] / 12) ? $LangUI->_('pm') : $LangUI->_('am');
		echo "</th></tr><tr><td><p>" . $rc->fields['review_comments'] . '</td></tr>';
		$rc->MoveNext();
	}
	echo '</table>';
}
?>

