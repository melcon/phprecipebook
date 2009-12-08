<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $LangUI->getEncoding();?>"/>
<title><?php echo $g_rb_project_name; ?></title>
<link rel="stylesheet" href="themes/<?php echo $g_rb_theme;?>/style.css" type="text/css">
</head>

<?php
require_once("classes/DBUtils.class.php");

// If we are print mode, then do not do most of the header
if ($print == "no") {
	include("includes/menu_items.php");
?>

<body bgcolor="#FFFFFF">

<table width="100%">
<tr>
	<td align="left" class="nav" width="100%" nowrap>
		<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>
				<?php printMenu($menu_items); ?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
	// Print out a submenu for the user to navigate
	printSubMenu($menu_items);
}
?>

