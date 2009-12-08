<?php
require_once("classes/Units.class.php");
require_once("classes/DBUtils.class.php");

$recipe_id = isValidID( $_GET['recipe_id'] ) ? $_GET['recipe_id'] : 0;
$total_ingredients = isValidID( $_REQUEST['total_ingredients'] ) ? $_REQUEST['total_ingredients'] : 0;
$total_related = isValidID($_REQUEST['total_related'] ) ? $_REQUEST['total_related'] : 0;
$show_ingredient_ordering = isset ($_REQUEST['show_ingredient_order'] ) ? 'yes' : '';
$private = isset($_REQUEST['private']) ? TRUE : FALSE;

// Declarations
$n = 0;
$p = 0;
$ingredients = null;

if ($g_rb_debug) $show_ingredient_ordering = "yes";

// Determine if the user has access to add new recipes, or edit this current one
if (!$SMObj->checkAccessLevel("AUTHOR")) {
	die($LangUI->_('You do not have sufficient privileges to add/edit recipes'));
} else if ($recipe_id && !$SMObj->checkAccessLevel("EDITOR")) {
	// Figure out who the owner of this recipe is, Editors can edit anyones recipes
	// The owner of a recipe does not change when someone edits it.
	$sql = "SELECT recipe_owner FROM $db_table_recipes WHERE recipe_id = " . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute($sql);
	// If the recipe is owned by someone else then do not allow editing
	if ($rc->fields['recipe_owner'] != "" && $rc->fields['recipe_owner'] != $SMObj->getUserLoginID())
		die($LangUI->_('You are not the owner of this recipe, you are not allowed to edit it'));
}

// Do a sanity check to make sure they have added ingredients first
$rc_ingredients = DBUtils::fetchColumn( $db_table_ingredients, 'ingredient_name', 'ingredient_id', 'ingredient_name' );
if ($rc_ingredients->RecordCount() <= 0) {
	die($LangUI->_('Please add ingredients before proceeding to add recipes'));
}

// get the information about the recipe (empty query if new recipe)
if ($recipe_id) {
	$sql = "SELECT *
			FROM $db_table_recipes
			WHERE recipe_id = " . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute( $sql );
}

if (isset($_REQUEST['refresh'])) {
	$recipe["recipe_name"] = $_POST["recipe_name"];
	$recipe["recipe_ethnic"] = $_POST["recipe_ethnic"];
	$recipe["recipe_base"] = $_POST["recipe_base"];
	$recipe["recipe_course"] = $_POST["recipe_course"];
	$recipe["recipe_prep_time"] = $_POST["recipe_prep_time"];
	$recipe["recipe_difficulty"] = $_POST["recipe_difficulty"];
	$recipe["recipe_directions"] = $_POST["recipe_directions"];
	$recipe["recipe_comments"] = $_POST["recipe_comments"];
	$recipe["recipe_source"] = $_POST["recipe_source"];
	$recipe["recipe_source_desc"] = $_POST["recipe_source_desc"];
	$recipe["recipe_serving_size"] = $_POST["recipe_serving_size"];
	// For PostgreSQL we pass around the Object ID of the picture
	if ($g_rb_database_type == "postgres")
		$recipe["recipe_picture_oid"] = $_POST["recipe_picture_oid"];

	$recipe["recipe_picture_type"] = $_POST["recipe_picture_type"];
	$recipe["recipe_owner"] = isset($_POST["recipe_owner"]) ? $_POST["recipe_owner"] : "";
	// escape 's and "s
	$recipe["recipe_name"] = htmlspecialchars( stripslashes( $recipe["recipe_name"] ), ENT_QUOTES );
	$recipe["recipe_comments"] = htmlspecialchars( stripslashes( $recipe["recipe_comments"] ), ENT_QUOTES );
	$recipe["recipe_directions"] = htmlspecialchars( stripslashes( $recipe["recipe_directions"] ), ENT_QUOTES );
	// Set if it is public or private
	if (isset($_POST['private']) && $_POST['private'] == 'yes') $private = true;
	else $private = false;
} else {
	$recipe["recipe_name"] = $rc->fields["recipe_name"];
	$recipe["recipe_ethnic"] = $rc->fields["recipe_ethnic"];
	$recipe["recipe_base"] = $rc->fields["recipe_base"];
	$recipe["recipe_course"] = $rc->fields["recipe_course"];
	$recipe["recipe_prep_time"] = $rc->fields["recipe_prep_time"];
	$recipe["recipe_difficulty"] = $rc->fields["recipe_difficulty"];
	$recipe["recipe_directions"] = $rc->fields["recipe_directions"];
	$recipe["recipe_comments"] = $rc->fields["recipe_comments"];
	$recipe["recipe_source"] = $rc->fields["recipe_source"];
	$recipe["recipe_source_desc"] = $rc->fields["recipe_source_desc"];
	$recipe["recipe_serving_size"] = $rc->fields["recipe_serving_size"];
	// For PostgreSQL we pass around the Object ID of the picture
	if ($g_rb_database_type == "postgres")
		$recipe["recipe_picture_oid"] = $rc->fields["recipe_picture"];

	$recipe["recipe_picture_type"] = $rc->fields["recipe_picture_type"];
	// Set the owner to be the current user, or the owner
	if ($recipe_id)	$recipe["recipe_owner"] = $rc->fields["recipe_owner"];
	else $recipe["recipe_owner"] = $SMObj->getUserLoginID();

	if ($rc->fields['recipe_private'] == $DB_LINK->true) $private = true;
	else $private = false;
}

?>

<?php include("./modules/recipes/addedit_js.php");?>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php
			if ($recipe_id) {
				echo $LangUI->_('Edit Recipe');
			} else {
				echo $LangUI->_('Add Recipe');
			}
		?>
	</td>
</tr>
<?php
if ($recipe_id) {
?>
<tr>
	<td class="nav" align="left">
		<a href="./index.php?m=recipes&a=view&recipe_id=<?php echo $recipe_id . '">' . $LangUI->_('View Recipe');?></A> |
		<a href="index.php?m=recipes&a=search&dosql=delete&recipe_id=<?php echo $recipe_id . "\">" . $LangUI->_('Delete Recipe');?></A>
	</td>
</tr>
<?php } ?>

</table>
<p>

<form name="recipe_form" enctype="multipart/form-data" action="./index.php?m=recipes&a=addedit&recipe_id=<?php echo $recipe_id;?>" method="post">
<input type="hidden" name="dosql" value="">

<table  cellspacing="1" cellpadding="2" border="0" class="data">
<tr>
	<td><?php echo $LangUI->_('Recipe Name');?>:<?php echo getHelpLink("dish_name");?></td>
	<td><input type="text" size="40" name="recipe_name" value="<?php echo $recipe["recipe_name"];?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Source');?>:<?php echo getHelpLink("source");?></td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_sources, 'source_title', 'source_id', 'source_title' );
	echo $rc->getMenu2('recipe_source', $recipe['recipe_source'], true);
?>
	</td></tr>
<tr>
	<td><?php echo $LangUI->_('Source Description');?>:<?php echo getHelpLink("source_desc");?></td>
	<td><input type="text" name="recipe_source_desc" size="40" value="<?php echo $recipe["recipe_source_desc"];?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Course');?>:<?php echo getHelpLink("course");?></td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_courses, 'course_desc', 'course_id', 'course_desc' );
	echo $rc->getMenu2('recipe_course',$recipe['recipe_course'], false);
?>
	</td>
</tr>

<tr>
	<td><?php echo $LangUI->_('Base');?>:<?php echo getHelpLink("base");?></td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_bases, 'base_desc', 'base_id', 'base_desc' );
	echo $rc->getMenu2('recipe_base', $recipe['recipe_base'], false);
?>
	</td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Ethnicity');?>:<?php echo getHelpLink("ethnicity");?></td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_ethnicity, 'ethnic_desc', 'ethnic_id', 'ethnic_desc' );
	echo $rc->getMenu2('recipe_ethnic', $recipe['recipe_ethnic'], false);
?>
	</td>
</tr>

<tr>
	<td><?php echo $LangUI->_('Preparation Time');?>:<?php echo getHelpLink("prep_time");?></td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_prep_time, 'time_desc', 'time_id', 'time_desc' );
	echo $rc->getMenu2('recipe_prep_time', $recipe['recipe_prep_time'], false);
?>
	</td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Difficulty');?>:<?php echo getHelpLink("difficulty");?></td>
	<td>
<?php
	$rc = DBUtils::fetchColumn( $db_table_difficulty, 'difficult_desc', 'difficult_id', '0' );
	echo $rc->getMenu2('recipe_difficulty', $recipe['recipe_difficulty'], false );
?>
	</td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Number of Servings');?>:<?php echo getHelpLink("servings");?></td>
	<td><input type="text" name="recipe_serving_size" size="3" value="<?php echo $recipe["recipe_serving_size"];?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Comments');?>:<?php echo getHelpLink("comments");?></td>
	<td><input type="text" name="recipe_comments" size="60" value="<?php echo $recipe["recipe_comments"];?>"></td>
</tr>
<tr>
	<td><?php echo $LangUI->_('Picture') . ":" . getHelpLink("picture");?></td>
	<td>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $g_rb_max_picture_size;?>">
		<input type="hidden" name="recipe_picture_oid" value="<?php echo $recipe['recipe_picture_oid'];?>">
		<input type="hidden" name="recipe_picture_type" value="<?php echo $recipe['recipe_picture_type'];?>">
		<input type="file" name="recipe_picture" value="<?php echo $_FILES['recipe_picture']['name'];?>">
		<input type="checkbox" name="remove_picture" value="yes"> <?php echo $LangUI->_('Remove Picture');?>
	</td>
</tr>
<tr>
	<td></td>
	<td>
		<?php if ($recipe['recipe_picture_type']!=NULL) {
			echo "<img src=\"./modules/recipes/view_picture.php?recipe_id=" . $recipe_id . "\"><br>";
		}?>
	</td>
</tr>

<?php if ($SMObj->checkAccessLevel("EDITOR")) { ?>
<tr>
	<td><?php echo $LangUI->_('Submitter');?>:<?php echo getHelpLink("submitter");?></td>
	<td>
	<?php
	$rc = DBUtils::fetchColumn( $db_table_users, 'user_name', 'user_login', 'user_name' );
	echo $rc->getMenu2('recipe_owner', $recipe['recipe_owner'], false);
	?>
	</td>
</tr>
<?php } ?>

</table>
<hr size=1 noshade>

<b><?php echo $LangUI->_('Ingredients');?>:</b><?php echo getHelpLink("ingredients");?>
<p>
<?php
// When this is an existing recipe list the ingredients
if ($recipe_id) {
	echo "<i>(". $LangUI->_('Check the ingredients you wish to delete') . ")</i><P>";
	$sql = "SELECT $db_table_ingredientmaps.*,
			unit_desc
			FROM $db_table_ingredientmaps
			LEFT JOIN $db_table_units ON unit_id = map_unit
			LEFT JOIN $db_table_ingredients ON ingredient_id = map_ingredient
			WHERE map_recipe = " . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc()) . " ORDER BY map_order";
	$ingredients = $DB_LINK->Execute($sql);
	// Error check
	if (!$ingredients) {
		echo $LangUI->_('There was an error') . "<br />";
		echo $sql . "<br>";
		echo $DB_LINK->ErrorMsg();
	}
	$n = $ingredients->RecordCount();
	// Select the related recipes as well.
	$sql = "SELECT related_child,related_required FROM $db_table_related_recipes WHERE related_parent=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());
	$related = $DB_LINK->Execute($sql);
	$p = $related->RecordCount();
	// If we are not refreshing set the count to something sane
	if (!isset($_REQUEST['refresh'])) {
		$total_related=$p;
		$total_ingredients=$n;
	}
}

// Set the number of Fields to display
if ($total_ingredients == 0) { // we have not refreshed
	if ($n == 0) $total_ingredients = 2;	//no current values set 2 as a default
	else $total_ingredients=$n;				//have values, use that number
}
if ($total_related == 0) { // we have not refreshed
	if ($p == 0) $total_related = 2;	//no current values set 2 as a default
	else $total_related=$p;				//have values, use that number
}

?>

<table cellspacing="1" cellpadding="2" border="0" class="data">
<tr>
<?php
	if ($recipe_id) {
		echo "<th>" . $LangUI->_('Delete') . "</th>\n";
	}
?>
	<th><?php echo $LangUI->_('Quantity');?></th>
	<th><?php echo $LangUI->_('Units');?></th>
	<th><?php echo $LangUI->_('Qualifier');?></th>
	<th><?php echo $LangUI->_('Ingredient') . " - ";?>
	<a href="javascript:newPopupWindow('./index.php?m=ingredients&a=addedit&print=yes');" style="color: #FFFFFF;">[<?php echo $LangUI->_('add new');?>]</a></th>
	<th><?php echo $LangUI->_('Optional');?></th>
	<?php if ($show_ingredient_ordering) {
		echo "<th>" . $LangUI->_('Order') . "</th>";
	}?>
</tr>
<?php
	// Load the local units
	$localUnits = Units::getLocalUnits();
	// Get a list of all of the ingredients (sorted by name)
	$ingList = DBUtils::createList($rc_ingredients, 'ingredient_id', 'ingredient_name');
	// Print out the ingredient fields
	for ($i=0; $i < $total_ingredients; $i++) {
		if (isset($_REQUEST['refresh'])) {
			$ingredient_id= isset($_POST['ingredient_id_'.$i]) ? $_POST['ingredient_id_'.$i] : "";
			$ingredient_qual= isset($_POST['ingredient_qualifier_'.$i]) ? $_POST['ingredient_qualifier_'.$i] : "";
			$ingredient_quant = isset($_POST['ingredient_quantity_'.$i]) ? $_POST['ingredient_quantity_'.$i] : "";
			$ingredient_unit = isset($_POST['ingredient_unit_'.$i]) ? $_POST['ingredient_unit_'.$i] : "";
			$ingredient_optional = isset($_POST['ingredient_optional_'.$i]) ? $_POST['ingredient_optional_'.$i] : "";
			$ingredient_delete = isset($_POST['ingredient_delete_'.$i]) ? $_POST['ingredient_delete_'.$i] : "";
		} else {
			if ($ingredients != null)
			{
				$ingredient_id= $ingredients->fields['map_ingredient'];
				$ingredient_qual = $ingredients->fields['map_qualifier'];
				$ingredient_quant = $ingredients->fields['map_quantity'];
				$ingredient_unit = $ingredients->fields['map_unit'];
				$ingredient_optional = ($ingredients->fields['map_optional']==$DB_LINK->true) ? 'checked' : '';
			}
			$ingredient_delete = ''; // default starting out value (nothing selected)

			if ($i >= $n) {
				$ingredient_id = 0;
				$ingredient_qual = "";
				$ingredient_quant = "";
				$ingredient_unit = "";
				$ingredient_optional = ""; //just to make sure
			}
		}

		echo "<tr>";
		// If it is an existing recipe, you can delete ingredients.
		if ($recipe_id)
			echo '<td><input type="checkbox" name="ingredient_delete_'.$i.'" value="checked"' . $ingredient_delete . '></td>';
		// Otherwise you are starting from scratch.
		echo '<td align=left><input type=text size=4 autocomplete="off" onchange="JavaScript:fractionConvert(this);" id ="ingredient_quantity_'.$i.'" name="ingredient_quantity_'.$i.'" value="'.$ingredient_quant.'"></td>';
		echo '<td align=left>';
		echo DBUtils::arrayselect( $localUnits, 'ingredient_unit_'.$i, 'size=1', $ingredient_unit);
		echo "</td>\n";
		echo '<td><input type="text" name="ingredient_qualifier_'.$i.'" value="'.$ingredient_qual.'" maxlength=32 size="20"></td>';
		echo '<td align=left>';
		// Show the Ingredient value, if AJAX support is enabled use the Auto-Complete Control
		if ($ajax_support)
		{
			echo "<input id=\"ingredientEntry$i\" acdropdown=\"true\" name=\"ingredient_text_$i\"" .
				"autocomplete_matchbegin=\"false\"" .
				"autocomplete_list=\"url:index.php?m=ingredients&a=get&format=no&search=[S]\" " .
				"autocomplete_matchsubstring=\"true\" autocomplete_assoc=\"true\" size=50" .
				" value=\"";

			if (isset($ingList[$ingredient_id])) echo $ingList[$ingredient_id];

			echo "\">\n";
			echo "<input type=\"hidden\" id=\"ACH_ingredientEntry$i\" name=\"ingredient_id_$i\" value=\"$ingredient_id\">\n";
		}
		else
		{
			// Otherwise use a normal dropdown list
			echo DBUtils::arrayselect( $ingList, 'ingredient_id_'.$i, 'size=1', $ingredient_id);
		}
		echo "</td>\n";
		echo '<td align="center"><input type="checkbox" name="ingredient_optional_'.$i.'" value="checked" ' . $ingredient_optional . '></td>';
		// If the option is set allow the user to specify the ordering of ingredients
		if ($show_ingredient_ordering)
			echo "<td><input type=text name='ingredient_order_$i' value='$i'></td>";
		echo "</tr>\n";
		if ($i < $n)
			$ingredients->MoveNext();
	}
?>
</table>
<?php
	$show_order_checked = ($show_ingredient_ordering != "") ? 'checked' : '';
?>
<script language='javascript' defer >var ingredient_type_count = <?php echo $i;?>-1;</script>
<br /><?php echo $LangUI->_('Number of fields to display')?>: <input type="text" autocomplete="off" name="total_ingredients" size="2" value="<?php echo $total_ingredients;?>">
<br /><?php echo $LangUI->_('Edit ingredient ordering')?>: <input type="checkbox" name="show_ingredient_order" value="yes" <?php echo $show_order_checked;?>>
<br><br>
<input type="submit" name="refresh" value="<?php echo $LangUI->_('Refresh');?>" class="button">
<hr size=1 noshade>
<?php
	echo "<b>" . $LangUI->_('Related Recipe(s)') . ":</b>" . getHelpLink("related_recipes") . "<br /><br />";

	echo '<table cellspacing="1" cellpadding="2" border="0" class="data">';
	if ($recipe_id) echo '<tr><th>' . $LangUI->_('Delete') . '</th>';
	echo '<th>' . $LangUI->_('Recipe') . '</th>';
	echo '<th>' . $LangUI->_('Required') . getHelpLink("related_recipes_required") . '</th>';
	echo '</tr>';
	// Read in the list of options now
	$rc_recipes = DBUtils::fetchColumn( $db_table_recipes, 'recipe_name', 'recipe_id', 'recipe_name' );
	$all_recipes = DBUtils::createList($rc_recipes, 'recipe_id', 'recipe_name');
	array_unshift_assoc($all_recipes, 0, "");

	// Loop/Section to add related recipes to this recipe
	for ($i=0; $i<$total_related; $i++) {
		$temp_rc = $rc;
		if ($i >= $p || isset($_REQUEST['refresh'])) {
			// Create a drop down for a new entry, cache the value even when refreshed/reloaded
			$related_id = isset($_POST['related_id_'.$i]) ? $_POST['related_id_' . $i] : "";
			$related_required = isset($_POST['related_req_'.$i]) ? $_POST['related_req_'.$i] : "";
			$related_delete = isset($_POST['related_delete_'.$i]) ? $_POST['related_delete_'.$i] : "";
		} else {
			// Fill in a drop down for a entry that already exists
			$related_id = $related->fields['related_child'];
			$related_required = ($related->fields['related_required']==$DB_LINK->true) ? 'checked' : '';
			$related_delete = ''; // nothing is to be deleted by default
			$related->MoveNext();
		}
		echo "<tr>";
		if ($recipe_id)
			echo '<td align="center"><input type="checkbox" name="related_delete_'.$i.'" value="checked" '. $related_delete . '></td>';
		echo "<td>\n";
		echo DBUtils::arrayselect( $all_recipes, 'related_id_' . $i, 'size=1', $related_id);
		echo '</td><td align="center">';
		echo '<input type="checkbox" name="related_req_'.$i.'" value="checked" ' . $related_required . '>';
		echo "</td></tr>\n";
	}
	echo "</TABLE>\n";
	echo "<br />" . $LangUI->_('Number of fields to display') . ': <input type="text" autocomplete="off" name="total_related" size="2" value="' . $total_related . '">  ';
	echo '<input type="submit" name="refresh" value="' . $LangUI->_('Refresh') . '" class="button">';
	echo '<hr size=1 noshade>';

// Print out the Directions
?>
<b><?php echo $LangUI->_('Directions');?>:</b><?php echo getHelpLink("directions");?><br /><br />
<textarea name="recipe_directions" rows="15" cols="50">
<?php echo $recipe['recipe_directions'];?>
</textarea>
<hr size="1" noshade>
<?php echo $LangUI->_('Make this recipe private') . getHelpLink("private");?>:
<input type="checkbox" name="private" value="yes" <?php if ($private) echo 'checked';?>>
<p><input type="button" value="<?php echo ($recipe_id ? $LangUI->_('Update Recipe') : $LangUI->_('Add Recipe'));?>" class="button" onclick="submitIt()">
<br />
<script type="text/javascript">
	this.document.forms.recipe_form.recipe_name.focus();
</script>
</form>
