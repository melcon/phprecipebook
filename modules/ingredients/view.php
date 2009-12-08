<?php
$ingredient_id = isValidID( $_GET['ingredient_id'] ) ? $_GET['ingredient_id'] : 0;
# fetch the ingredient and map the units
$sql = "
SELECT $db_table_ingredients.*,
        unit_desc,
        location_desc
FROM $db_table_ingredients
LEFT JOIN $db_table_units ON unit_id = ingredient_unit
LEFT JOIN $db_table_locations ON location_id = ingredient_location
WHERE ingredient_id = " . $DB_LINK->addq($ingredient_id, get_magic_quotes_gpc());

$ingredient = $DB_LINK->Execute($sql);
DBUtils::checkResult($ingredient, NULL, NULL, $sql);

// Now start printing out HTML now that we know what we are doing.
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function confirmDelete() {
  return confirm("<?php echo $LangUI->_('Are you sure you wish to delete this ingredient?');?>");
}

// -->
</SCRIPT>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title"><?php echo $LangUI->_('View Ingredient');?></td>
</tr>
<tr>
        <td class="nav" align="left">
        <?php if ($SMObj->checkAccessLevel("AUTHOR")) { ?>
                <a href="./index.php?m=ingredients&a=addedit&ingredient_id=<?php echo $ingredient_id . '">' . $LangUI->_('Edit Ingredient');?></a> |
				<a href="index.php?m=ingredients&dosql=delete&ingredient_id_0=<?php echo $ingredient_id . '&ingredient_selected_0=yes" onClick="return confirmDelete()">' . $LangUI->_('Delete Ingredient');?></a> |
        <?php } ?>
                <a href="index.php?m=ingredients&a=related&ingredient_id=<?php echo $ingredient_id . "\">" . $LangUI->_('Recipes using ingredient');?></a> |
                <a href="index.php?m=lists&a=current&mode=add&ingredient_selected_0=yes&ingredient_id_0=<?php echo $ingredient_id .
                        '&ingredient_unit_0='.$ingredient->fields['ingredient_unit']. '">' . $LangUI->_('Add to shopping list');?></a>
        </td>
</tr>
</table>
<P>

<table cellspacing="1" cellpadding="2" border="0" class="std" width="40%">
<tr>
        <td nowrap width=20%><?php echo $LangUI->_('Name');?>:</td>
        <td nowrap><b><?php echo $ingredient->fields['ingredient_name'];?></b></td>
</tr>
<tr>
        <td nowrap width=20%><?php echo $LangUI->_('Description');?>:</td>
        <td nowrap><b><?php echo $ingredient->fields['ingredient_desc'];?></b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Form');?>:</td>
        <td nowrap><b>
        <?php
        if ($ingredient->fields['ingredient_solid'] == $DB_LINK->true)
                echo $LangUI->_('Solid');
        else
                echo $LangUI->_('Liquid');

        ?></b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Unit Price');?>:</td>
        <td nowrap><b>
        <?php
                printf($LangUI->_('$%01.2f'), $ingredient->fields['ingredient_price']);
                echo ' / ' . $ingredient->fields['unit_desc'];

        ?>
        </b></td>
</tr>
<tr>
        <td nowrap><?php echo $LangUI->_('Location');?>:</td>
        <td nowrap><b><?php echo $ingredient->fields['location_desc'];?></b></td>
</tr>
</table>
