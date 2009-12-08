<?php
global $menu_items;

// Ingredients
if ($SMObj->getUserLoginID()!="") { 
	$menu_items['ingredients'][0] = array( $LangUI->_('Ingredients'), "index.php?m=ingredients");
	$menu_items['ingredients'][1] = array( $LangUI->_('A-Z'), "index.php?m=ingredients&amp;where=%25" );
	$menu_items['ingredients'][2] = array( $LangUI->_('Search'), "index.php?m=ingredients&amp;a=search" );
	if ($SMObj->checkAccessLevel("AUTHOR")) { 
		$menu_items['ingredients'][3] = array($LangUI->_('Add Ingredient'), "index.php?m=ingredients&amp;a=addedit");
	}
}

// Recipes
$menu_items['recipes'][0] = array( $LangUI->_('Recipes'), "index.php?m=recipes");
$menu_items['recipes'][1] = array( $LangUI->_('A-Z'), "index.php?m=recipes&amp;where=%25" );
$menu_items['recipes'][2] = array( $LangUI->_('Search'), "index.php?m=recipes&amp;a=search" );
if ($SMObj->getUserLoginID() != "") { 
	$menu_items['recipes'][3] = array( $LangUI->_('Favorites'),  "index.php?m=recipes&amp;a=favorites" );
}
if ($SMObj->checkAccessLevel("AUTHOR")) {
	$menu_items['recipes'][4] = array( $LangUI->_('Add Recipe'), "index.php?m=recipes&amp;a=addedit" );
	if ($SMObj->checkAccessLevel("EDITOR")) {
		$menu_items['recipes'][5] = array( $LangUI->_('Sources'), "index.php?m=recipes&amp;a=sources" );
	}
	$menu_items['recipes'][6] = array( $LangUI->_('Import'), "index.php?m=recipes&amp;a=import" );
	$menu_items['recipes'][7] = array( $LangUI->_('Export'), "index.php?m=recipes&amp;a=export" );
}

// Meal Planner
if ($SMObj->getUserLoginID()!="")
	$menu_items['meals'][0] = array( $LangUI->_('Meal Planner'), "index.php?m=meals");

// Shopping Lists
$menu_items['lists'][0] = array( $LangUI->_('Shopping Lists'), "index.php?m=lists&amp;a=current");
$menu_items['lists'][1] = array( $LangUI->_('Current List'), "index.php?m=lists&amp;a=current");
if ($SMObj->getUserLoginID()!="") {
	$menu_items['lists'][2] = array( $LangUI->_('Saved Lists'), "index.php?m=lists&amp;a=saved" );
}

// Restaurant Listing
$menu_items['restaurants'][0] = array( $LangUI->_('Restaurants'), "index.php?m=restaurants");
$menu_items['restaurants'][1] = array( $LangUI->_('A-Z'), "index.php?m=restaurants");
if ($SMObj->checkAccessLevel("EDITOR")) {
	$menu_items['restaurants'][2] = array( $LangUI->_('Add Restaurant'), "index.php?m=restaurants&amp;a=addedit");
}

// Administration
if ($SMObj->getUserLoginID()!="") {
	$menu_items['admin'][0] = array( $LangUI->_('Administration'), "index.php?m=admin");
	$menu_items['admin'][1] = array( $LangUI->_('Account Settings'), "index.php?m=admin&amp;a=account&amp;sm_mode=edit&amp;sm_login=" . $SMObj->getUserLoginID());
	$menu_items['admin'][2] = array( $LangUI->_('Store Layouts'), "index.php?m=admin&amp;a=stores");
	// Administrator only operations
	if ($SMObj->checkAccessLevel("ADMINISTRATOR")) {
		$menu_items['admin'][3] = array( $LangUI->_('Users'), "index.php?m=admin&amp;a=user_admin");
		$menu_items['admin'][4] = array( $LangUI->_('Groups'), "index.php?m=admin&amp;a=group_admin");
		$menu_items['admin'][5] = array( $LangUI->_('Meal Planner'), "index.php?m=admin&amp;a=mealplan");
		$menu_items['admin'][6] = array( $LangUI->_('Customize'), "index.php?m=admin&amp;a=customize");
		$menu_items['admin'][7] = array( $LangUI->_('Translation'), "index.php?m=admin&amp;a=translation");
	}
}

// Utils
$menu_items['utils'][0] = array( $LangUI->_('Utilities'), "index.php?m=utils");
$menu_items['utils'][1] = array( $LangUI->_('Converter'), "index.php?m=utils&amp;a=converter");
$menu_items['utils'][2] = array( $LangUI->_('Resources'), "index.php?m=utils&amp;a=resources");

function printMenu($menu_items) {
	global $g_rb_theme;
	// Print out the menu items
	foreach ($menu_items as $item) {
		echo '<a href="' . $item[0][1] . '">' . $item[0][0];
		echo '   <img src="themes/'. $g_rb_theme . '/images/down_arrow.png" border="0" alt="" /></a>  ';
	}
}

function printSubMenu($menu_items) {
	$key = isset( $_GET['m'] ) ? $_GET['m'] : 'recipes';
	$submenu = isset($menu_items[$key]) ? $menu_items[$key] : '';
	$output = "";
	if (is_array($submenu)) {
		array_shift($submenu); //get rid of the top element
	
		echo '<table cellspacing="0" cellpadding="1" border="0" width="100%">';
		echo '<tr><td class=nav align=left>';
		foreach ($submenu as $item) {
			$output .= ' <a href="'.$item[1].'">'.$item[0].'</a> |';
		}
		echo substr("$output", 0, -1);;
		echo '</td></tr></table><br />';
	}
}
?>
