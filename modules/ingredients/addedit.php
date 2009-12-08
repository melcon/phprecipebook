<?php
require_once("classes/Units.class.php");
require_once("classes/DBUtils.class.php");

$ingredient_id = isValidID( $_GET['ingredient_id'] ) ? $_GET['ingredient_id'] : 0;

// Determine if the user has access to add new Ingredients
if (!$SMObj->checkAccessLevel("AUTHOR")) {
	die($LangUI->_('You do not have sufficient privileges to add/edit recipes'));
}

// get the information about the Ingredient (empty query if new Ingredient)
if ($ingredient_id) {
	$sql = "SELECT *
			FROM $db_table_ingredients
			LEFT JOIN $db_table_units ON ingredient_unit = unit_id
			WHERE ingredient_id = " . $DB_LINK->addq($ingredient_id, get_magic_quotes_gpc());
	$ingredients = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($ingredients, NULL, NULL, $sql);
}

// Load the local units
$localUnits = Units::getLocalUnits();

?>

<SCRIPT language="javascript">
<!--
function submitIt() {
	var form = document.ingredient_form;

	var teststring = form.ingredient_price.value;
	var a=teststring.indexOf(",");    // change "," to "." (in all languages)
	if ( a != -1 ) {
		teststring=teststring.substring(0,a)+"."+teststring.substring(a+1,teststring.length);
		form.ingredient_price.value=teststring;
    }

	if (form.ingredient_name.value.length == 0) {
		alert( "<?php echo $LangUI->_('Please enter an ingredient name');?>" );
		form.ingredient_name.focus();
	} else {
		// submit
		form.dosql.value = "update";
		form.submit();
		return true;
	}
}
// -->
</script>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title">
		<?php
			if ($ingredient_id) {
				echo $LangUI->_('Edit Ingredient');
			} else {
				echo $LangUI->_('Add Ingredient');
			}
		?>
	</td>
</tr>
<?php

// Print out navigation links (if editing existing ingredient)
if ($ingredient_id) {
?>
<tr>
	<td class="nav" align="left">
		<a href="./index.php?m=ingredients&a=view&ingredient_id=<?php echo $ingredient_id . '">' . $LangUI->_('View Ingredient');?></a> |
		<a href="index.php?m=ingredients&a=search&dosql=delete&ingredient_id=<?php echo $ingredient_id . "\">" . $LangUI->_('Delete Ingredient');?></A>
	</td>
</tr>

<?php } ?>

</table>
<P>
<table  cellspacing="1" cellpadding="2" border="0" class="data">
<form name="ingredient_form" action="./index.php?m=ingredients&a=addedit&ingredient_id=<?php echo $ingredient_id;?>&print=<?php echo $print;?>" method="post">
<input type="hidden" name="dosql" value="">
<table cellspacing="1" cellpadding="2" border="0" class="data">
<tr>
	<th><?php echo $LangUI->_('Name');?></th>
	<th><?php echo $LangUI->_('Description');?></th>
	<th><?php echo $LangUI->_('Price per Unit');?></th>
	<th><?php echo $LangUI->_('Liquid/Solid');?></th>
	<th><?php echo $LangUI->_('Location in Store');?></th>
</tr>
<?php
	$units = Units::getLocalUnits(); // Load the local units

	$rc_locations = DBUtils::fetchColumn( $db_table_locations, 'location_desc', 'location_id', 'location_desc' );
	$locations = DBUtils::createList($rc_locations, 'location_id', 'location_desc');

	$liqsol = array(
			 "FALSE" => $LangUI->_('Liquid'),
			 "TRUE" => $LangUI->_('Solid')
	);

	$ingredient_name = "";
	$ingredient_desc = "";
	$ingredient_price = "";
	$ingredient_unit = "";
	$ingredient_loc = "";
	$ingredient_solid = "";

	if ($ingredient_id)
	{
		$ingredient_name = $ingredients->fields['ingredient_name'];
		$ingredient_desc = $ingredients->fields['ingredient_desc'];
		$ingredient_price = $ingredients->fields['ingredient_price'];
		$ingredient_unit = $ingredients->fields['ingredient_unit'];
		$ingredient_loc = $ingredients->fields['ingredient_location'];
		$ingredient_solid = $ingredients->fields['ingredient_solid'];
		if ($ingredient_solid == $DB_LINK->false)
		{
			$ingredient_solid="FALSE";
		}
		else
		{
			$ingredient_solid="TRUE";
		}
	}

	echo "<tr>";
	echo '<td align=left><input type="text" name="ingredient_name" autocomplete="off" value="'.$ingredient_name.'" maxlength=60 size="20"></td>';
	echo '<td align=left><input type="text" name="ingredient_desc" autocomplete="off" value="' . $ingredient_desc . '" size="30"></td>';
	echo '<td align=left><input type="text" name="ingredient_price" autocomplete="off" value="'.$ingredient_price.'" size="4">';
	echo DBUtils::arrayselect( $units, 'ingredient_unit', 'size=1', $ingredient_unit);
	echo "</td>\n";
	echo "<td align=left>\n";
	echo DBUtils::arrayselect( $liqsol, 'ingredient_solid', 'size=1', $ingredient_solid );
	echo "</td>\n";
	echo "<td align=left>\n";
	echo DBUtils::arrayselect( $locations, 'ingredient_loc', 'size=1', $ingredient_loc);
	echo "</td></tr>\n";


?>


</table>
<p>
<input type="button" value="<?php echo ($ingredient_id ? $LangUI->_('Update Ingredient') : $LangUI->_('Add Ingredient'));?>" class="button" onclick="submitIt()">
<br />
</form>
