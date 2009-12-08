<?php
require_once("classes/DBUtils.class.php");
?>
<script language="JavaScript">
<!--
	function checkAll(val) {
		var al=document.searchForm;
		var len = al.elements.length;
		var j=0
		var i=0;
		for( i=0 ; i<len ; i++) {
			var id = 'ingredient_selected_' + j;
			if (al.elements[i].name == id) {
				al.elements[i].checked = val;
				j++;
			}
		}
	}

	function confirmDelete() {
		return confirm("<?php echo $LangUI->_('Are you sure you wish to delete this ingredient?');?>");
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
				document.searchForm.action="index.php?dosql=delete&m=ingredients&a=search";
				document.searchForm.submit();
			}
		}
    }
//-->
</script>

<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
	<td align="left" class="title"><?php echo $LangUI->_('Search Ingredients');?></td>
</tr>
</table>

<p><table cellspacing="1" cellpadding="2" border="0" class="data" width="50%">
<form name="searchInfo" action="index.php?m=ingredients&a=search" method="post">
<input type="hidden" name="search" value="yes">
<tr>
	<th>
		<?php echo $LangUI->_('Keywords');?>
	</th>
	<th>
		<?php echo $LangUI->_('Location');?>
	</th>
</tr>
<tr>
	<td>
		<input type="text" name="name" size=40 class="field_textbox" value="<?php echo isset($_REQUEST['name']) ? $_REQUEST['name'] : "";?>">
	</td>
	<td>
		<?php
			$rc = DBUtils::fetchColumn( $db_table_locations, 'location_desc', 'location_id', 'location_desc' );
			echo $rc->getMenu2('location_id', isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : "", true);
		?>
	</td>
</tr>
<tr>
	<td align="center" colspan="9">
		<input type="submit" value="<?php echo $LangUI->_('Search');?>" class="button" style="width:75px">&nbsp;
		<input type="reset" value="<?php echo $LangUI->_('Clear');?>" class="button" style="width:75px">
	</td>
</tr>
</form>
</table>
<script language="JavaScript">
<!--
	document.searchInfo.name.focus();
//-->
</script>
<hr size=1 noshade>
<?php

	$query="";
	$query_order = " ORDER BY ingredient_name";
	$query_all="SELECT * FROM $db_table_ingredients";
	// Do not display anything if no search has been requested
	if (!isset($_REQUEST['search'])) {
		$query = "";
	} else {
		// Construct the query
		$query = $query_all;
		if ($_REQUEST['name'] != "" || $_REQUEST['location_id']) $query .= " WHERE ";
		if ($_REQUEST['name'] != "") {
			$query .= " (ingredient_name LIKE '%" . $DB_LINK->addq($_REQUEST['name'], get_magic_quotes_gpc()) . "%'";
			$query .= " OR ingredient_desc LIKE '%" . $DB_LINK->addq($_REQUEST['name'], get_magic_quotes_gpc()) . "%') AND";
		}
		if (isValidID($_REQUEST['location_id']))
			$query .= " ingredient_location=".$DB_LINK->addq($_REQUEST['location_id'], get_magic_quotes_gpc());
		$query = preg_replace("/AND$/", "", $query);
		// Put the order on the end
		$query .= $query_order;
	}

	/* ----------------------
		The Query has been made, format and output the values returned from the database
	----------------------*/
	if ($query != "") {
		$counter=0;
		$rc = $DB_LINK->Execute($query);
		DBUtils::checkResult($rc, NULL, NULL, $query); // Error check
		# exit if we did not find any matches
		if ($rc->RecordCount() == 0) {
			echo $LangUI->_('No values returned from search');
		} else {
?>
		<table cellspacing="1" cellpadding="2" border=0 width="80%" class="data">
		<form name="searchForm" action="" method="post">
		<input type="hidden" name="mode" value="add">
		<tr valign="top">
			<td colspan=6 align=left>
				<input type="button" value="<?php echo $LangUI->_('Add to shopping list');?>" class="button" onClick='submitForm("list")'>&nbsp;
				<?php if ($SMObj->checkAccessLevel("EDITOR")) { ?>
				<input type="button" value="<?php echo $LangUI->_('Delete Selected');?>" class="button" onClick='submitForm("delete")'>&nbsp;&nbsp;
				<?php } ?>
				<a href="javascript:checkAll(1)"><?php echo $LangUI->_('Check All');?></a> -
				<a href="javascript:checkAll(0)"><?php echo $LangUI->_('Clear All');?></a>
			</td>
		</tr>
		<tr>
			<th width="10">+</th>
			<th><?php echo $LangUI->_('Ingredient Name');?></th>
			<th colspan=2><?php echo $LangUI->_('Options');?></th>
		</tr>
<?php
		while (!$rc->EOF) {
?>

		<tr>
			<td width="10">
				<input type="checkbox" name="ingredient_selected_<?php echo $counter;?>" value="yes" class="field_checkbox">
				<input type="hidden" name="ingredient_id_<?php echo $counter;?>" value="<?php echo $rc->fields['ingredient_id']; ?>">
				<input type="hidden" name="ingredient_unit_<?php echo $counter . '" value="' . $rc->fields['ingredient_unit'] . '">';?>
			</td>
			<td>
				<a href="./index.php?m=ingredients&a=view&ingredient_id=<?php echo $rc->fields['ingredient_id'];?>">
					<?php echo $rc->fields['ingredient_name'];?>
				</a>
			</td>
			<td>
				<a href="./index.php?m=ingredients&a=addedit&ingredient_id=<?php echo $rc->fields['ingredient_id'] . "\">" . $LangUI->_('Edit');?></a>
			</td>
			<td>
				<a href="index.php?m=ingredients&a=related&ingredient_id=<?php echo $rc->fields['ingredient_id'] . "\">" . $LangUI->_('Recipes using this ingredient');?></a>
			</td>
		</tr>
<?php
			$rc->MoveNext();
			$counter++;
		}
?>
		<tr>
			<td colspan="6" align="left">
				<input type="button" value="<?php echo $LangUI->_('Add to shopping list');?>" class="button" onClick='submitForm("list")'>&nbsp;
				<?php if ($SMObj->checkAccessLevel("EDITOR")) { ?>
				<input type="button" value="<?php echo $LangUI->_('Delete Selected');?>" class="button" onClick='submitForm("delete")'>&nbsp;&nbsp;
				<?php } ?>
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
