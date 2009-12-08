<?php
require_once("classes/Fraction.class.php");
require_once("classes/DBUtils.class.php");

$recipe_id = isValidID( $_GET['recipe_id'] ) ? $_GET['recipe_id'] : 0;
$show_ratings = isset ($_GET['show_ratings'] ) ? true : false;
$show_ratings = isset($_GET['show_ratings']) ? isset($_GET['show_ratings']) : $g_rb_show_ratings;

#Construct the Query and do most of the setup first, the print html
$sql = "SELECT $db_table_recipes.*,
        ethnic_desc,
        base_desc,
        course_desc,
        time_desc,
        difficult_desc,
        user_name,
	source_title
FROM $db_table_recipes
LEFT JOIN $db_table_ethnicity ON ethnic_id = recipe_ethnic
LEFT JOIN $db_table_bases ON base_id = recipe_base
LEFT JOIN $db_table_courses ON course_id = recipe_course
LEFT JOIN $db_table_prep_time ON time_id = recipe_prep_time
LEFT JOIN $db_table_difficulty ON difficult_id = recipe_difficulty
LEFT JOIN $db_table_users ON user_login = recipe_owner
LEFT JOIN $db_table_sources ON source_id = recipe_source
WHERE recipe_id = " . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());

$recipe = $DB_LINK->Execute($sql);
// Error check
DBUtils::checkResult($recipe, NULL, NULL, $sql);
// Make sure we have some results before continuing
if ($recipe->RecordCount() == 0)
{
	die($LangUI->_('Recipe Does not Exist!'));
}

/*
        If this is a private recipe and the user does not have access to it, then do not show it
*/
if (($recipe->fields['recipe_private'] == $DB_LINK->true) &&
        (!$SMObj->getUserLoginID() ||
         (!$SMObj->checkAccessLevel("EDITOR") &&
         $SMObj->getUserLoginID() != $recipe->fields['recipe_owner'] &&
         !$SMObj->hasGroupsWith($recipe->fields['recipe_owner'])))) {
                 die($LangUI->_('This recipe is private and you do not have permission to view it!'));
}

# fetch the ingredients for the recipe
$sql = "SELECT $db_table_ingredientmaps.*,
        unit_desc,
        ingredient_name
FROM $db_table_ingredientmaps
LEFT JOIN $db_table_units ON unit_id = map_unit
LEFT JOIN $db_table_ingredients ON ingredient_id = map_ingredient
WHERE map_recipe = ".$DB_LINK->addq($recipe_id, get_magic_quotes_gpc())." ORDER BY map_order";

$ingredients = $DB_LINK->Execute($sql);
// Error check
DBUtils::checkResult($ingredients, NULL, NULL, $sql);

# fetch the related ingredients
$sql = "
SELECT related_child, recipe_name, recipe_directions, related_required
FROM $db_table_related_recipes
LEFT JOIN $db_table_recipes ON recipe_id = related_child
WHERE related_parent=" . $DB_LINK->addq($recipe_id, get_magic_quotes_gpc());

$related = $DB_LINK->Execute($sql);
// Error check
DBUtils::checkResult($related, NULL, NULL, $sql);

// if no scale is set the read from the database
if (isset($_GET['recipe_scale'])  && $recipe->fields['recipe_serving_size'] != NULL) {
        $recipe_scale=$_GET['recipe_scale'];
        $scale_by = ($recipe_scale/$recipe->fields['recipe_serving_size']);
} else if ($recipe->fields['recipe_serving_size'] != NULL) {
        $recipe_scale=$recipe->fields['recipe_serving_size'];
        $scale_by=1;
} else {
        $recipe_scale="";
        $recipe->fields['recipe_serving_size']="";
        $scale_by=1;
}

$related_ings = array();
$related_names = array();

while (!$related->EOF) {
        $id = $related->fields['related_child'];
        $related_names[$id] = $related->fields;

        $sql = "SELECT $db_table_ingredientmaps.*,
                unit_desc,
                ingredient_name
        FROM $db_table_ingredientmaps
        LEFT JOIN $db_table_units ON unit_id = map_unit
        LEFT JOIN $db_table_ingredients ON ingredient_id = map_ingredient
        WHERE map_recipe = $id ORDER BY map_order";
        $rc = $DB_LINK->Execute($sql);
        // Error check
        DBUtils::checkResult($rc, NULL, NULL, $sql);
        // If there are no records returned then blank it out.
        if ($rc->RecordCount() == 0)
                $related_ings[$related->fields['related_child']] = NULL;

        while (!$rc->EOF) {
                $related_ings[$related->fields['related_child']][] = $rc->fields;
                $rc->MoveNext();
        }
        $related->MoveNext();
}
// Now start printing out HTML now that we know what we are doing.
?>
<SCRIPT LANGUAGE="JavaScript">
<!--

function confirmDelete() {
  return confirm("<?php echo $LangUI->_('Are you sure you wish to delete this recipe?');?>");
}

// -->
</SCRIPT>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title"><?php echo $LangUI->_('View Recipe');?></td>
</tr>
</table>
<table cellspacing="0" cellpadding="1" border="0" width="100%" class="nav">
<tr>
        <td>
        <?php
		if (!isset($_REQUEST['print']) || $_REQUEST['print'] != "yes")
		{
			echo '<b>' . $LangUI->_('Recipe') . '</b>: ( ';
			if ($SMObj->checkAccessLevel("AUTHOR")) {
				// Display 'Edit' and 'Delete' button according to the user level.
				switch ($SMObj->getAccessLevel($SMObj->getUserAccessLevel())) {
					case 'ADMINISTRATOR' :
					case 'EDITOR':
						echo '<a href="index.php?m=recipes&a=addedit&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Edit') . '</a> | ';
						echo '<a href="index.php?m=recipes&a=search&dosql=delete&recipe_id_0=' . $_REQUEST['recipe_id'] . '&recipe_selected_0=yes" onClick="return confirmDelete()">' . $LangUI->_('Delete') . '</a> | ';
						break;
					case 'AUTHOR':
						if ($recipe->fields['recipe_owner'] != "" && $recipe->fields['recipe_owner'] == $SMObj->getUserLoginID())
						{
							echo '<a href="index.php?m=recipes&a=addedit&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Edit') . '</a> | ';
							echo '<a href="index.php?m=recipes&a=search&dosql=delete&recipe_id_0=' . $_REQUEST['recipe_id'] . '&recipe_selected_0=yes" onClick="return confirmDelete()">' . $LangUI->_('Delete') . '</a> | ';
							break;
						}
				}
			}

			echo '<a href="index.php?m=recipes&a=related&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Parent recipes') . '</a> | ';
			echo '<a href="index.php?m=recipes&a=view&print=yes&recipe_scale=' . $recipe_scale . '&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Print') . '</a> ) ';
			if ($SMObj->getUserLoginID()!="") {
					echo '<b>  ' . $LangUI->_('Add to') . '</b>: ( ';
					echo '<a href="index.php?m=lists&a=current&mode=add&recipe_selected_0=yes&recipe_id_0=' . $_REQUEST['recipe_id'] . '&recipe_scale_0=' . $recipe_scale .
							'">' . $LangUI->_('Shopping List') . '</a> | ';
					echo '<a href="index.php?m=recipes&a=favorites&mode=add&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Favorites') . '</a> ) ';
			}
			if ($g_rb_enable_ratings) {
					echo '<b>  ' . $LangUI->_('Reviews/Ratings') . '</b>: ( ';
					if ($SMObj->getUserLoginID()!="") {
							echo '<a href="index.php?m=reviews&a=review&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Add') . '</a> | ';
					}
					if ($show_ratings)
							echo '<a href="index.php?m=recipes&a=view&recipe_scale=' . $recipe_scale . '&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Hide') . '</a> ) ';
					else
							echo '<a href="index.php?m=recipes&a=view&show_ratings=yes&recipe_scale=' . $recipe_scale . '&recipe_id=' . $_REQUEST['recipe_id'] . '">' . $LangUI->_('Show') . '</a> )';
			}
		}
        ?>
        </td>
</tr>
</table>
<P>
<table cellspacing="1" cellpadding="2" border="0" class="std" width="100%">
<tr>
        <td nowrap><?php echo $LangUI->_('Recipe Name');?>:</td>
        <td nowrap><b><?php echo $recipe->fields['recipe_name'];?></b></td>
        <td nowrap width=10%><?php echo $LangUI->_('Submitted by');?>:</td>
        <td nowrap><b><?php echo $recipe->fields['user_name'];?></b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Source');?>:</td>
        <td nowrap><b><a href="#" onClick=window.open('index.php?m=recipes&a=sources&print=yes&source_id=<?php echo $recipe->fields['recipe_source'];?>','recipe_sources','height=400,width=600,toolbar=1,menubar=0,status=0,scrollbars=1');><?php echo $recipe->fields['source_title'];?></a></td></b></td>
	<td nowrap><?php echo $LangUI->_('Source Description');?>:</td>
	<td nowrap><b><?php echo $recipe->fields['recipe_source_desc'];?></b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Ethnicity');?>:</td>
        <td nowrap width="25%"><b><?php echo $recipe->fields['ethnic_desc'];?></b></td>
	<td nowrap><?php echo $LangUI->_('Last Modified');?>:</td>
        <td nowrap><b><?php  $date = DBUtils::formatDate($recipe->fields['recipe_modified']);
                             echo ($date[2]+0) . '/' . ($date[3]+0) . '/' . $date[1];
                       ?>
                   </b></td>

</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Base');?>:</td>
        <td nowrap><b><?php echo $recipe->fields['base_desc'];?></b></td>
        <td nowrap><?php echo $LangUI->_('Approximate Cost');?>:</td>
        <td nowrap><b><?php
		if ($recipe->fields['recipe_cost'] > 0) printf($LangUI->_('$%01.2f'),($recipe->fields['recipe_cost']*$scale_by));
                else echo '?';
         ?>
         </b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Course');?>:</td>
        <td nowrap><b><?php echo $recipe->fields['course_desc'];?></b></td>
        <td colspan=2 align="left"><?php echo $LangUI->_('Comments');?>:</td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Difficulty');?>:</td>
        <td nowrap><b><?php echo $recipe->fields['difficult_desc'];?></b></td>
	<td colspan=2 rowspan="3" width="100%"><?php echo $recipe->fields['recipe_comments'];?>&nbsp;</td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Preparation Time');?>:</td>
        <td nowrap><b><?php echo $recipe->fields['time_desc'];?></b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Number of Servings');?>:</td>
        <td nowrap><b><?php echo ($recipe->fields['recipe_serving_size']*$scale_by);?></b></td>
</tr>
</table>
<br />
<table cellspacing="5" cellpadding="2" border="0" class="ing" width="100%">
<tr>
<?php
if ($recipe->fields['recipe_picture_type']!=NULL) {
        echo "<td width=\"20%\" valign=\"top\"><img src=\"./modules/recipes/view_picture.php?recipe_id=" . $recipe_id . "\"></td>";
        echo "<td width=\"30%\" valign=\"top\">";
} else {
        echo "<td width=\"50%\" valign=\"top\">";
}


echo "<b>" . $LangUI->_('Ingredients') . ":</b>";

// Save the optional ingredients for later display
$optIng = array();

// Iterate through the ingredients
while (!$ingredients->EOF) {
        $fraction = new Fraction($ingredients->fields['map_quantity']*$scale_by);
        $quant = $fraction->toString();
        if ($ingredients->fields['map_optional']!=$DB_LINK->true) {
                // print out the ingredient
                echo "<br />";
                echo $quant;
                if ($ingredients->fields['unit_desc'] != $LangUI->_('Unit'))
                        echo ' '.$ingredients->fields['unit_desc'].$LangUI->_('(s)');
                echo ' '.$ingredients->fields['map_qualifier'];
                echo ' '.$ingredients->fields['ingredient_name'];
        } else {
                // save this one for later
                $optIng[] = array('id'=>$ingredients->fields['map_ingredient'],
                                                  'name'=>$ingredients->fields['ingredient_name'],
                                                  'unit'=>$ingredients->fields['unit_desc'],
                                                  'unit_id'=>$ingredients->fields['map_unit'],
                                                  'qualifier'=>$ingredients->fields['map_qualifier'],
                                                  'quantity_dec'=>$ingredients->fields['map_quantity'],
                                                  'quantity'=>$quant,
                                                  'int_quant'=>($ingredients->fields['map_quantity']*$scale_by));
        }
        $ingredients->MoveNext();
}
// Print out the optional ingredients if there are any
if (count($optIng))
        echo "<p><b>" . $LangUI->_('Optional Ingredients') . ":</b>";

foreach ($optIng as $ing) {
        echo "<br />\n";
        echo $ing['quantity'];
        if ($ing['unit'] != $LangUI->_('Unit'))
                echo ' '.$ing['unit'].$LangUI->_('(s)');
        echo ' '.$ing['qualifier'];
        echo ' '.$ing['name'];
        echo ' <a href="index.php?m=lists&a=current&mode=add' .
                        '&ingredient_selected_0=yes' .
                        '&ingredient_id_0='.$ing['id'] .
                        '&ingredient_quantity_0=' . $ing['quantity_dec'] .
                        '&ingredient_unit_0=' . $ing['unit_id'] . '">' .
                        $LangUI->_('Add to shopping list').'</a>';
}
?>
        </td>
        <td width="50%" valign="top">
                <b><?php echo $LangUI->_('Directions');?>:</b>
                <br /><?php
                if ($recipe->fields['recipe_directions'] != "")
                {
					echo str_replace( "\n", '<br />', $recipe->fields['recipe_directions'] );
                }
                ?>
        </td>
</tr>
</table>
<br />

<?php
$total_recipes=1;
foreach($related_names as $k=>$v) {
// $v contains the array of fields return from the sql query for this related recipe
?>
<table cellspacing="5" cellpadding="2" border="0" class="ing" width="100%">
<tr>
        <td width="50%" valign="top">
        <a href="./index.php?m=recipes&a=view&recipe_id=<?php echo $k;?>">
                <b><?php echo $v['recipe_name'];?></b></a> (
                <?php
                if ($v['related_required']==$DB_LINK->false) echo '<a href="./index.php?m=lists&a=current&mode=add&recipe_selected_0=yes&recipe_id_0='. $k .
                        '&recipe_scale_0=' .
                        $recipe_scale . '">' . $LangUI->_('Add to shopping list') . '</a>';
                else echo $LangUI->_('Required');?>
                 ) :
<?php
        if ($related_ings[$k] != NULL) {
                foreach($related_ings[$k] as $i) {
                        // prints out a decimal if that is what we want
                        echo "<br />";
                        $fraction = new Fraction($i['map_quantity']*$scale_by);
                        echo $fraction->toString();
                        if ($i['unit_desc'] != $LangUI->_('Unit')) {
                                echo ' '.$i['unit_desc'].$LangUI->_('(s)');
                        }
                        echo ' '.$i['map_qualifier'];
                        echo ' '.$i['ingredient_name'];
                }
        }
?>
        </td>
        <td width="50%" valign="top">
                <b><?php echo $LangUI->_('Directions');?>:</b><br />
                <?php echo str_replace( "\n", '<br />', $v['recipe_directions'] );?>
        </td>
</tr>
</table>
<br />
<?php }
if ($recipe_scale) { ?>
<form action="./index.php" ACTION="GET">
<input type=hidden name=m value=recipes>
<input type=hidden name=a value=view>
<input type=hidden name=recipe_id value=<?php echo $recipe_id;?>>
<?php echo $LangUI->_('Scale this recipe to');?> <input type=text name=recipe_scale size=2 value=<?php echo $recipe_scale . ">" .
        $LangUI->_('Servings') . " " . getHelpLink("scale_recipe");?>
<input type=submit value="<?php echo $LangUI->_('Refresh');?>" class="button">
</form>
<?php } else {
        echo $LangUI->_('Scale this recipe') . getHelpLink("scale_recipe");
}
?>
<br />
<table cellspacing="0" cellpadding="1" border="0" width="80%" class="nav">
<tr>
        <td align="center">
        <?php /*
                echo '<b> ' . $LangUI->_('Convert to') . '</b> : ( ';
                echo '<a href="">' . $LangUI->_('Metric') . '</a> | ';
                echo '<a href="">' . $LangUI->_('U.S Standard') . '</a> | ';
                echo '<a href="">' . $LangUI->_('Imperial') . '</a> ) ';*/
        ?>
        </td>
</tr>
</table>
<p>
<?php
if ($show_ratings) {
        include("modules/recipes/view_review.php");
}
?>

