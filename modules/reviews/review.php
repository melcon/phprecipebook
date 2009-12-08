<?php
require_once ("classes/Recipe.class.php");
$recipe_id = (isValidID($_REQUEST['recipe_id'])) ? $_REQUEST['recipe_id'] : 0;

$recipeObj = new Recipe($recipe_id);
$recipeObj->loadRecipe();

?>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title">
            <?php echo $LangUI->_('Review Recipe');?>
        </td>
</tr>
</table>
<p>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="center" class="title">
			<?php echo $recipeObj->name;?>
        </td>
</tr>
</table>
<P>
<b><?php echo $LangUI->_('Rating');?>:</b><br />
<form name="reviewForm" action="index.php?m=reviews&a=update&recipe_id=<?php echo $recipe_id;?>" method="post">
<input type="hidden" name="recipe_id" value="<?php echo $recipe_id;?>">
<table border=0>
<tr>
	<td align="center">1</td>
	<td align="center">2</td>
	<td align="center">3</td>
	<td align="center">4</td>
	<td align="center">5</td>
</tr>
<tr>
	<td><input type="radio" name="rating" value="1"></td>
	<td><input type="radio" name="rating" value="2"></td>
	<td><input type="radio" name="rating" value="3"></td>
	<td><input type="radio" name="rating" value="4"></td>
	<td><input type="radio" name="rating" value="5"></td>
</tr>
<tr>
	<td colspan="3" align="left"><i><?php echo $LangUI->_('Lowest');?></i></td>
	<td colspan="2" align="right"><i><?php echo $LangUI->_('Highest');?></i></td>
</tr>
</table>
<p>
<?php if ($SMObj->getUserLoginID()) { ?>
	<b><?php echo $LangUI->_('Review');?>:</b><p>
	<textarea name="review" cols="50" rows="8"></textarea><p>
<?php } ?>
<input type="submit" value="<?php echo $LangUI->_('Submit');?>" class="button">
<br/>
