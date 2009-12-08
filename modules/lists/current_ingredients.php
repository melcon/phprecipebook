<tr>
	<th width="10">-</th>
	<th><?php echo $LangUI->_('Quantity');?></th>
	<th><?php echo $LangUI->_('Units');?></th>
	<th><?php echo $LangUI->_('Qualifier');?></th>
	<th width="50%"><?php echo $LangUI->_('Ingredient'); ?></th>
	<th width="10%" align="center"><?php echo $LangUI->_('Edit'); ?></th>
</tr>
<?php
$iterator=0; //reset the counter
// Load the local units
$localUnits = Units::getLocalUnits();
// Cycle through the ingredients now (if any)		
foreach ($listObj->ingredients as $ingObj) {
?>
		<tr>
			<td width="10">
				<input type="checkbox" name="delete_ingredient_<?php echo $iterator;?>" value="yes">
				<input type="hidden" name="ingredient_id_<?php echo $iterator;?>" value="<?php echo $ingObj->getID(); ?>">
			</td>
			<td align="left">
				<input type="text" size="4" autocomplete="off" name="ingredient_quantity_<?php echo $iterator;?>" value="<?php echo $ingObj->quantity;?>">
			</td>
			<td align="left">
			
			<?php
				echo DBUtils::arrayselect( $localUnits, 'ingredient_units_'.$iterator, 'size=1', $ingObj->unitMap);?>
			</td>
			<td align="left">
				<input type="text" size="20" name="ingredient_qualifier_<?php echo $iterator;?>" value="<?php echo $ingObj->qualifier;?>">
			</td>
			<td width="50%" align="center">
				<a href="./index.php?m=ingredients&a=view&ingredient_id=<?php echo $ingObj->getID() . '">' . $ingObj->name;?></a>
			</td>
			<td width="10%" align="center">
				<a href="./index.php?m=ingredients&a=addedit&ingredient_id=<?php echo $ingObj->getID();?>"><?php echo $LangUI->_('Edit'); ?></a>
			</td>
		</tr>
<?php 
	$iterator++;
} ?>
