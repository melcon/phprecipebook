<?php
require_once("classes/DBUtils.class.php");
require_once("classes/Fraction.class.php");

// Read in the globals
$mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode'] : 'print';
$store_id = (isValidID($_REQUEST['store_id'])) ? stripslashes( $_REQUEST['store_id'] ) : 0; // the selected store layout
$show_sections = (isset($_REQUEST['show_sections'])) ? $_REQUEST['show_sections'] : '';

$listObj = $_SESSION['shoppinglist'];

// The layout contains the sections
$useLayout = array();

// Load the store names from the database
$sql = "SELECT store_id, store_name FROM $db_table_stores WHERE store_owner = '" . $SMObj->getUserLoginID() . "'";
$rc = $DB_LINK->Execute($sql);
DBUtils::checkResult($rc, NULL, NULL, $sql);
$stores = DBUtils::createList($rc, 'store_id', 'store_name');
$stores = array("0"=>$LangUI->_("(Show All)")) + $stores;

// Load the section names so we can print them for headers later (only if requested)
$sql = "SELECT location_id, location_desc FROM $db_table_locations ORDER BY location_desc ASC";
$rc = $DB_LINK->Execute($sql);
DBUtils::checkResult($rc, NULL, NULL, $sql);
$locations = DBUtils::createList($rc, 'location_id', 'location_desc');

if ($store_id > 0)
{
	// We need to do the query again (MySQL odd behavior)
	$sql = "SELECT store_layout FROM $db_table_stores WHERE store_owner = '" . $SMObj->getUserLoginID() . "' AND store_id = " . $DB_LINK->addq($store_id, get_magic_quotes_gpc());
	$rc = $DB_LINK->Execute($sql);
	DBUtils::checkResult($rc, NULL, NULL, $sql);

	// get the list of sections to display, default is 'default'
	$useLayout = split(',', $rc->fields[0]);
	if (!count($useLayout)) $useLayout[] = ''; //if it does not exist, set it to empty
}
else
{
	// No store is selected, use the default all sections store
	foreach ($locations as $key=>$value)
	{
		$useLayout[] = $key; // add the section
	}
}


// Load the units we are to deal with
$units = Units::getUnits();
// Get the shopping list
$items = array();
if (is_object($listObj)) $items = $listObj->getShoppingList();
$printedItems=0; //keep track of how many items we show at once

if ($mode=="print") {
/*************************************************
			Print the prelim List
*************************************************/
?>
<SCRIPT language="javascript">
<!--
function submitIt(refresh) {
	var form = document.listForm;
	if (refresh == 1) {
		form.mode.value="print";
		form.print.value="no";
	} else {
		form.mode.value="print_confirm";
		form.print.value="yes";
	}
	form.submit();
	return true;
}
//-->
</script>
<table cellspacing="0" cellpadding="1" border="0" width="100%">
<tr>
        <td align="left" class="title">
            <?php echo $LangUI->_('Shopping List'); ?>
        </td>
</tr>
<tr>
        <td class="nav" align="left">
                <a href="./index.php?m=lists&amp;a=current"><?php echo $LangUI->_('Edit Shopping List'); ?></a>
        </td>
</tr>
</table>
<P>
<!-- End of nav header, now on to printing the list -->
<form name="listForm" action="index.php?m=lists&amp;a=print" method="post">
<input type="hidden" name="mode" value="print_confirm">
<input type="hidden" name="print" value="yes">
<i><?php echo $LangUI->_('(Select the ingredients you wish to remove)');?></i>
<table cellspacing="1" cellpadding="3" border="0" width="95%" class="data">
<tr valign="top">
	<td width="5%" align=left>
		<input type="button" value="<?php echo $LangUI->_('Print Final List'); ?>" class="button" onclick="submitIt(0)">
	</td>
	<td>
		<?php
			echo $LangUI->_('Use Store Layout') . ': ';
			echo DBUtils::arraySelect($stores, 'store_id', '', $store_id);
		?>
		<input type="checkbox" name="show_sections" value="yes" <?php echo ($show_sections) ? 'checked' : '';
			echo "> ". $LangUI->_('Show Section headings');?>
		<input type="button" value="<?php echo $LangUI->_('Refresh'); ?>" class="button" onclick="submitIt(1)">
	</td>
</tr>
<tr>
	<th><?php echo $LangUI->_('Remove');?></th>
	<th><?php echo $LangUI->_('Item');?></th>
</tr>
<?php
foreach ($useLayout as $section) {
	// Print the header here if requested
	$html = makeHtmlList($items, $section);
	if (count($html)) {
		// print the header
		printHeader($section);
	}
	foreach ($html as $line) echo $line;
}
// Show the lost ingredients (no section matched)
if (count($items) > 0)
{
	echo '<tr><th colspan=3 align="left">' . $LangUI->_("Ingredients Without A Matching Section") . "</th></tr>";
	$html = showLostIngredients($items);
	foreach ($html as $line) echo $line;
}
?>
<tr>
	<th colspan="2"></th>
</tr>

</table>
</form>
<?php
/*************************************************
			Print the final List
*************************************************/
} else if ($mode=='print_confirm') { ?>
<table cellspacing="0" cellpadding="0" border="0" width="95%">
<?php
foreach ($useLayout as $section) {
	// Print the header here if requested
	$html = makeHtmlList($items, $section, "remove_item_");
	if (count($html)) {
		// print the header
		printHeader($section);
	}
	foreach ($html as $line) echo $line;
}
// Show the lost ingredients (no section matched)
$html = showLostIngredients($items, "remove_item_");
if (count($html) > 0)
{
	echo '<tr><th colspan=3 align="left">' . $LangUI->_("Ingredients Without A Matching Section") . "</th></tr>";
	foreach ($html as $line) echo $line;
}
?>
</table>
<?
}

/*
	Puts the List in a string so that displaying it can be delayed
*/
function makeHtmlList(&$items, $section, $remove='') {
	global $units, $LangUI, $printedItems;
	$html = array();
	$removeItem = array();
	// loop through the sections
	for ($i=0; $i < count($items); $i++)
	{
		$ingObj = $items[$i];
		$removeKey = $remove . $ingObj->id;
		if ($section == '' || $ingObj->location == $section)
		{
			if ($_REQUEST[$removeKey] != "yes")
			{
				$quantity = $ingObj->quantity; // do not convert it to a fraction in this case
				$html[] = '<tr>';
				if (!$remove)
					$html[] = '<td align="center"><input type="checkbox" name="remove_item_' . $ingObj->id . '" value="yes"></td>';
				$html[] = '<td>' . round($quantity,3) . " ";
				if ($units[$ingObj->unit][0] != $LangUI->_('Unit'))
					$html[] = " " . $units[$ingObj->unit][0] . $LangUI->_('(s)');
				$html[] = " " . $ingObj->name;
				// Add the qualifier if it is set
				if ($ingObj->description != "")
				{
					$html[] = " (" . $ingObj->description . ")";
				}
				$html[] = "</td></tr>\n";
			}
			$removeItem[] = $i;
		}
		else
		{
			// We should not save the item if they don't want it.
                        if ($_REQUEST[$removeKey] == "yes")
                        {
                                $removeItem[] = $i;
                        }
		}
	}
	foreach ($removeItem as $remove)
	{
		unset($items[$remove]); // Remove this one
	}
	$items = array_values($items); // clean up the array
	return $html;
}

function showLostIngredients($items, $remove='')
{
	global $units, $LangUI;
	$html = array();
	// loop through the sections
	for ($i=0; $i < count($items); $i++) {
		$removeKey = $remove . $i;
		$ingObj = $items[$i];
		if (!isset($_REQUEST[$removeKey]))  {
			$quantity = $ingObj->quantity; // do not convert it to a fraction in this case
			$html[] = '<tr>';
			if (!$remove)
				$html[] = '<td align="center"><input type="checkbox" name="remove_item_' . $i . '" value="yes"></td>';
			$html[] = '<td>' . round($quantity,3) . " ";
			if ($units[$ingObj->unit][0] != $LangUI->_('Unit'))
				$html[] = " " . $units[$ingObj->unit][0] . $LangUI->_('(s)');
			$html[] = " " . $ingObj->name;
			// Add the qualifier if it is set
			if ($ingObj->description != "")
			{
				$html[] = " (" . $ingObj->description . ")";
			}
			$html[] = "</td></tr>\n";
		}
	}
	return $html;
}

/*
	Print a section header if requested by the user
*/
function printHeader($id) {
	global $locations, $show_sections;
	if ($id && $show_sections) {
		echo '<tr><th colspan=3 align="left">' . $locations[$id] . " ($id)</th></tr>";
	}
}
?>
