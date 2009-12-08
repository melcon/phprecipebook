<tr>
	<th width="10">-</th>
	<th width="100"><?php echo $LangUI->_('Servings');?></th>
	<th colspan=3><?php echo $LangUI->_('Recipe'); ?></th>
	<th align=center><?php echo $LangUI->_('Edit'); ?></th>
</tr>
<?php
$iterator = 0;
foreach ($listObj->recipes as $item) {
	$recipeObj = $item['recipe'];
	$scale = $item['scale'];
?>
		<tr>
			<td width="10" align="center">
				<input type="checkbox" name="delete_recipe_<?php echo $iterator;?>" value="yes">
				<input type="hidden" name="recipe_id_<?php echo $iterator;?>" value="<?php echo $recipeObj->getID(); ?>">
			</td>
			<td width="100" align="center">
				<input type="text" size="4" autocomplete="off" name=recipe_scale_<?php echo $iterator;?> value="<?php echo $scale;?>">
			</td>
			<td width="560" align="center" colspan="3">
				<a href="./index.php?m=recipes&amp;a=view&recipe_id=<?php echo $recipeObj->getID() . "&amp;recipe_scale=" . $scale;?>">
					<?php echo $recipeObj->name;?>
				</a>
			</td>
			<td width="10%" align="center">
				<a href="./index.php?m=recipes&amp;a=addedit&recipe_id=<?php echo $recipeObj->getID();?>"><?php echo $LangUI->_('Edit'); ?></a>
			</td>
		</tr>
<?php
	$iterator++;
}
?>
