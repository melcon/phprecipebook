<?php
require_once("classes/DBUtils.class.php");


$course_id = isValidID($_REQUEST['course_id']) ?  $_REQUEST['course_id'] : 0;
$base_id = isValidID($_REQUEST['base_id']) ? $_REQUEST['base_id'] : 0;
$ethnic_id = isValidID($_REQUEST['ethnic_id']) ?  $_REQUEST['ethnic_id'] : 0;
$time_id = isValidID($_REQUEST['time_id']) ? $_REQUEST['time_id'] : 0;
$difficult_id = isValidID($_REQUEST['difficult_id']) ? $_REQUEST['difficult_id'] : 0;
$cost = isset($_REQUEST['cost']) && is_numeric($_REQUEST['cost']) ? $_REQUEST['cost'] : 0.00;
$owner = isset($_REQUEST['owner']) ? $_REQUEST['owner'] : '';
$keywords = isset($_REQUEST['keywords']) ? $_REQUEST['keywords'] : '';

if (isset($_REQUEST['cost_compare'])) {
    $tmp = $_REQUEST['cost_compare'];
    if (!(
        $tmp == ">" ||
        $tmp == ">=" ||
        $tmp == "=" ||
        $tmp == "<" ||
        $tmp == "<="
        ))
    $tmp = null;
    $_REQUEST['cost_compare'] = $tmp;
}

?>
<script language="JavaScript">
<!--
	function checkAll(val)
	{
		var len = document.searchForm.elements.length;
		var j=0
		var i=0;
		for( i=0 ; i<len ; i++) {
			var id = 'recipe_selected_' + j;
			if (document.searchForm.elements[i].name == id) {
				document.searchForm.elements[i].checked = val;
				j++;
			}
		}
	}

	function confirmDelete()
	{
		return confirm("<?php echo $LangUI->_('Are you sure you wish to delete this recipe?');?>");
	}

	function submitForm(val)
	{
		if(val == "list")
		{
			document.searchForm.action="index.php?m=lists&a=current";
			document.searchForm.submit();
        }
		else if (val == "delete")
		{
			if (confirmDelete())
			{
				document.searchForm.action="index.php?dosql=delete&m=recipes&a=search";
				document.searchForm.submit();
			}
		}
    }
// -->
</SCRIPT>
</script>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Search Recipes');?></td>
</tr>
</table>

<p><table cellspacing="1" cellpadding="2" border="0" class="data" width="95%">
<form name="inputForm" action="index.php?m=recipes&a=search<?php if (isset($_REQUEST['advanced'])) echo "&advanced=yes"?>" method="post">
<input type=hidden name="search" value="yes">
<tr>
	<th><?php echo $LangUI->_('Course');?></th>
	<th><?php echo $LangUI->_('Base');?></th>
	<th><?php echo $LangUI->_('Ethnicity');?></th>
	<th><?php echo $LangUI->_('Preparation Time');?></th>
<?php if (isset($_REQUEST['advanced'])) {?>
	<th><?php echo $LangUI->_('Difficulty');?></th>
	<th colspan="2"><?php echo $LangUI->_('Approximate Cost');?></th>
	<th><?php echo $LangUI->_('Owner');?></th>
<?php } ?>
	<th><?php echo $LangUI->_('Keywords');?></th>
</tr>
<tr>
	<td>
<?php
	// Need this later on for the user owner display
	$db_users = DBUtils::fetchColumn( $db_table_users, 'user_name', 'user_login', 'user_name' );

	// Now back to the normal display
	$rc = DBUtils::fetchColumn( $db_table_courses, 'course_desc', 'course_id', 'course_desc' );
	echo $rc->getMenu2('course_id', $course_id, true);
?>
	</td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_bases, 'base_desc', 'base_id', 'base_desc' );
	echo $rc->getMenu2('base_id', $base_id, true);
?>
	</td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_ethnicity, 'ethnic_desc', 'ethnic_id', 'ethnic_desc' );
	echo $rc->getMenu2('ethnic_id', $ethnic_id, true);
?>
	</td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_prep_time, 'time_desc', 'time_id', 'time_desc' );
	echo $rc->getMenu2('time_id', $time_id, true);
?>
	</td>
<?php
// Need to simplify the search bar, so just display this extra stuff if
//	in advanced mode.
if (isset($_REQUEST['advanced'])) {
	echo "<td>\n";
	$rc = DBUtils::fetchColumn( $db_table_difficulty, 'difficult_desc', 'difficult_id', 'difficult_desc' );
	echo $rc->getMenu2('difficult_id', $difficult_id, true);
	echo "</td>\n<td>\n";
	// Workaround to put the values in the dropdown, it would be good to use adodb?
	$arr = array(
			">" => ">",
			">=" => ">=",
			"=" => "=",
			"<" => "<",
			"<=" => "<="
			);
	echo DBUtils::arraySelect( $arr, 'cost_compare', 'size=1', isset($_REQUEST["cost_compare"]) ? $_REQUEST["cost_compare"] : '');
?>
	</td>
	<td>
		<input type="text" size="5" name="cost" class="field_textbox" value="<?php echo $cost;?>">
	</td>
	<td>
	<?php
	$temp_users = $db_users;
	echo $temp_users->getMenu2('owner', $owner, true );
	echo "</td>";
	// End of Advanced Section
}

// convert the users to an array
$users = array();
while (!$db_users->EOF) {
	$j = $db_users->fields['user_login'];
	$users[$j] = $db_users->fields['user_name'];
	$db_users->MoveNext();
}

?>
	<td><input type="text" name="keywords" class="field_textbox" value="<?php echo $keywords;?>">
</tr>
<tr>
	<td align="center" colspan="9">
		<input type="submit" value="<?php echo $LangUI->_('Search');?>" class="button" style="width:75px">&nbsp;
		<input type="reset" value="<?php echo $LangUI->_('Clear');?>" class="button" style="width:75px">
		<?php if (!isset($_REQUEST['advanced'])) {?>
		&nbsp;&nbsp;&nbsp;&nbsp;<i>(<a href="./index.php?m=recipes&amp;a=search&amp;advanced=yes"><?php echo $LangUI->_('Advanced Search');?></a>)</i>
		<?php } else {?>
		&nbsp;&nbsp;&nbsp;&nbsp;<i>(<a href="./index.php?m=recipes&amp;a=search"><?php echo $LangUI->_('Simple Search');?></a>)</i>
		<?php } ?>
	</td>
</tr>
</tr>
</form>
</table>
<script language="JavaScript">
	document.inputForm.keywords.focus();
</script>
<hr size=1 noshade>
<?php
	// Query to get the rating of a recipe (may be disabled)
	$rating_query = "SELECT rating_recipe, avg(rating_score) FROM $db_table_ratings GROUP BY rating_recipe";
	// Construct the Query to search for recipes
	$query="";
	$query_order = " ORDER BY recipe_name";
	$query_all="SELECT
					recipe_id,
					recipe_name,
					recipe_comments,
					recipe_private,
					recipe_owner,
					recipe_serving_size,
					user_name
				FROM $db_table_recipes
				LEFT JOIN $db_table_users ON user_login = recipe_owner";
	// Do not display anything if no search has been requested
	if (!isset($_REQUEST["search"])) {
		$query = "";
	} else if (
		   !$course_id && !$base_id &&
		   !$ethnic_id && !$time_id  && !$cost &&
		   !$owner && !$difficult_id && $keywords=="") {
		// Nothing special specied, just spit it all out
		$query = $query_all . $query_order;
	} else {
		#Construct the Query
		$query = $query_all . " WHERE ";
		if ($course_id) $query .= " recipe_course=" . $DB_LINK->addq($course_id, get_magic_quotes_gpc()) . " AND";
		if ($base_id) $query .= " recipe_base=" . $DB_LINK->addq($base_id, get_magic_quotes_gpc()) . " AND";
		if ($ethnic_id) $query .= " recipe_ethnic=" . $DB_LINK->addq($ethnic_id, get_magic_quotes_gpc()) . " AND";
		if ($time_id) $query .= " recipe_prep_time=" . $DB_LINK->addq($time_id, get_magic_quotes_gpc()) . " AND";
		if ($difficult_id) $query .= " recipe_difficulty=" . $DB_LINK->addq($difficult_id, get_magic_quotes_gpc())  . " AND";
		if ($cost) $query .= " recipe_cost " . $DB_LINK->addq($_REQUEST["cost_compare"], get_magic_quotes_gpc()) . " " . $DB_LINK->addq(htmlentities($cost, ENT_QUOTES), get_magic_quotes_gpc()) . " AND";
		if ($owner) $query .= " recipe_owner='" . $DB_LINK->addq($owner, get_magic_quotes_gpc()) . "' AND";
		if ($keywords != "") {
			$query .= " recipe_name LIKE '%". $DB_LINK->addq(htmlentities($keywords, ENT_QUOTES), get_magic_quotes_gpc()) . "%' OR ";
			$query .= " recipe_directions LIKE '%".$DB_LINK->addq(htmlentities($keywords, ENT_QUOTES), get_magic_quotes_gpc()) . "%' OR ";
			$query .= " recipe_source LIKE '%". $DB_LINK->addq(htmlentities($keywords, ENT_QUOTES), get_magic_quotes_gpc()) . "%' OR ";
			$query .= " recipe_comments LIKE '%". $DB_LINK->addq(htmlentities($keywords, ENT_QUOTES), get_magic_quotes_gpc()) . "%'";
		}
		$query = preg_replace("/AND$/", "", $query);
		$query .= $query_order;
	}

	/* ----------------------
		The Query has been made, format and output the values returned from the database
	----------------------*/
	if ($query != "") {
		$counter=0;
		$recipes = $DB_LINK->Execute($query);
		DBUtils::checkResult($recipes, NULL, NULL, $query);
		$rc = $DB_LINK->Execute($rating_query);
		DBUtils::checkResult($rc, NULL, NULL, $rating_query);
		// The field name for an avg is different between databases, have to use numeric return position
		$ratings = DBUtils::createList($rc, 0, 1);
		# exit if we did not find any matches
		if ($recipes->RecordCount() == 0)
		{
			echo $LangUI->_('No values returned from search') . "<br>";
		}
		else
		{
?>
		<table cellspacing="1" cellpadding="2" border=0 width="95%" class="data">
		<form name="searchForm" action="" method="post">
		<input type="hidden" name="mode" value="add">
		<tr valign="top">
			<td colspan=6 align=left>
				<input type="button" value="<?php echo $LangUI->_('Add to shopping list');?>" class="button" onClick='submitForm("list")'>&nbsp;
				<?php if ($SMObj->checkAccessLevel("AUTHOR")) { ?>
				<input type="button" value="<?php echo $LangUI->_('Delete Selected');?>" class="button" onClick='submitForm("delete")'>&nbsp;&nbsp;
				<?php } ?>
				<a href="javascript:checkAll(1)"><?php echo $LangUI->_('Check All');?></a> -
				<a href="javascript:checkAll(0)"><?php echo $LangUI->_('Clear All');?></a>
			</td>
		</tr>
		<tr>
			<th width="10">+</th>
			<th><?php echo $LangUI->_('Dish Name');?></th>
			<?php if ($g_rb_enable_ratings) echo '<th>' . $LangUI->_('Rating') . '</th>';?>
			<th align=center><?php echo $LangUI->_('Options');?></th>
			<th><?php echo $LangUI->_('Comments');?></th>
		</tr>
<?php while (!$recipes->EOF) {
		$recipe_id = $recipes->fields['recipe_id'];
		/*
			If this is a private recipe and the user does not have access to it, then skip it
		*/
		if (($recipes->fields['recipe_private'] == $DB_LINK->true) &&
			(!$SMObj->getUserLoginID() ||
			 (!$SMObj->checkAccessLevel("EDITOR") &&
			 $SMObj->getUserLoginID() != $recipes->fields['recipe_owner'] &&
			 !$SMObj->hasGroupsWith($recipes->fields['recipe_owner'])))) {
				 $recipes->MoveNext();
				 continue;
		}
?>
		<tr>
			<td width="10">
				<input type="checkbox" name="recipe_selected_<?php echo $counter;?>" value="yes" class="field_checkbox">
				<input type="hidden" name="recipe_id_<?php echo $counter;?>" value="<?php echo $recipe_id; ?>">
				<input type="hidden" name="recipe_scale_<?php echo $counter;?>" value="<?php echo $recipes->fields['recipe_serving_size'];?>">
			</td>
			<td>
				<a href="./index.php?m=recipes&amp;a=view&amp;recipe_id=<?php echo $recipes->fields['recipe_id'];?>">
					<?php echo $recipes->fields['recipe_name'];?></a><br />
					<?php
						echo $LangUI->_('Submitted by') . ' ' .trim($recipes->fields['user_name']);
					?>
				</td>
				<?php
				if ($g_rb_enable_ratings) {
					echo '<td align="center">';
					// Print out the ratings Information (if it is enabled)
					$avg = 0;

					if (isset($ratings[$recipe_id]))
					{
						$avg = $ratings[$recipe_id] + 0; // cheap way of removing the 0's
					}

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
							echo '<img src="themes/' . $g_rb_theme . '/images/filled_star.gif" border="0">';
							$avg--;
							$num_stars++;
						}

						// award a half star of greater then .65
						if ($avg >= 0.65)
						{
							echo '<img src="themes/' . $g_rb_theme . '/images/filled_star.gif" border="0">';
							$num_stars++;
						}

						// print out the rest of them as empty stars
						while ($num_stars < 5)
						{
							echo '<img src="themes/' . $g_rb_theme . '/images/empty_star.gif" border="0">';
							$num_stars++;
						}
					}
					echo '</td>';
				}
				?>
			<td align="center">
				<a href="./index.php?m=recipes&amp;a=addedit&amp;recipe_id=<?php echo $recipe_id . "\">" . $LangUI->_('Edit');?></a>
			</td>
			<td>
				<?php echo $recipes->fields['recipe_comments'];?>
			</td>
		</tr>
<?php
			$recipes->MoveNext();
			$counter++;
		}
?>
		<tr>
			<td colspan="6" align="left">
				<input type="button" value="<?php echo $LangUI->_('Add to shopping list');?>" class="button" onClick='submitForm("list")'>&nbsp;
				<?php if ($SMObj->checkAccessLevel("AUTHOR")) { ?>
				<input type="button" value="<?php echo $LangUI->_('Delete Selected');?>" class="button" onClick='submitForm("delete")'>&nbsp;&nbsp;
				<?php } ?>
				<INPUT type="hidden" name="total_recipes" value="<?php echo $counter;?>">
				<a href="javascript:checkAll(1)"><?php echo $LangUI->_('Check All');?></a> -
				<a href="javascript:checkAll(0)"><?php echo $LangUI->_('Clear All');?></a>
			</td>
		</tr>
		</form>
		</table>
<?php
		}
	}
?>
</font>
</p>
