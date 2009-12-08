<?php if ($format == "yes") { ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $LangUI->getEncoding();?>"/>
	<title><?php echo $g_rb_project_name; ?></title>
	<link rel="stylesheet" href="themes/<?php echo $g_rb_theme;?>/style.css" type="text/css" />
	<?php if ($ajax_support) { ?>
		<style>
			@import url( libs/autocomplete/css/page.css );
			@import url( libs/autocomplete/css/tabsexamples.css );
			@import url( libs/autocomplete/css/SyntaxHighlighter.css );
			@import url( libs/autocomplete/css/dropdown.css );
		</style>
		
		<script src="libs/autocomplete/js/modomevent3.js"></script>
		<script src="libs/autocomplete/js/modomt.js"></script>
		<script src="libs/autocomplete/js/modomext.js"></script>
		<script src="libs/autocomplete/js/tabs2.js"></script>
		<script src="libs/autocomplete/js/getobject2.js"></script>
		<script src="libs/autocomplete/js/xmlextras.js"></script>
		<script src="libs/autocomplete/js/acdropdown.js"></script>
		<!-- syntax highlight -->
		<script language="javascript" src="libs/autocomplete/js/shCore.js" ></script>
		<script language="javascript" src="libs/autocomplete/js/shBrushXml.js" ></script>
	<?php } ?>
</head>

<?php
require_once("classes/DBUtils.class.php");

// If we are print mode, then do not do most of the header
if ($print == "no") {
	include("includes/menu_items.php");
?>

<body bgcolor=#FFFFFF topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="./themes/<?php echo $g_rb_theme;?>/images/bground.png">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td bgcolor="#006699" colspan="2"><img src="themes/<?php echo $g_rb_theme;?>/images/logo.png" alt="" />&nbsp;</td>
	<td rowspan="2" bgcolor="#006699"><img src="themes/<?php echo $g_rb_theme;?>/images/pixel.png" width="20" height="1" border="0" alt="" align="top" /></td>
</tr>
<tr>
	<td align="left" class="nav" width="100%" nowrap>
		<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>
				<img src="./themes/<?php echo $g_rb_theme;?>/images/pixel.png" width="160" height="1" border="0" alt="" align="top" />
			</td>
			<td>
			<?php
				printMenu($menu_items);
			?>
			</td>
			</tr>
		</table>
	</td>
	<td valign="top" align="right" width="14">
		<img src="./themes/<?php echo $g_rb_theme;?>/images/outercorner.png" width="14" height="14" border="0" alt="" align="top" />
	</td>
</tr>
</table>

<table cellspacing="0" cellpadding="3" border="0" width="100%">
<tr valign="top">
	<td valign="top" class="nav" width="115">
		<img src="./themes/<?php echo $g_rb_theme;?>/images/pixel.png" width="115" height="1" border="0" alt="" align="top" />
		<b><?php echo $LangUI->_('Course');?>:</b><?php
	$sql = "SELECT course_id,course_desc FROM $db_table_courses ORDER BY course_desc";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	
	while (!$rc->EOF) {
		echo '<br /><a href="index.php?m=recipes&amp;a=search&amp;search=yes&amp;course_id='.$rc->fields['course_id'].'">'.$rc->fields['course_desc'].'</a>';
		$rc->MoveNext();
	}
?>
	<p><b><?php echo $LangUI->_('Base');?>:</b>
	
	<?php
	$sql = "SELECT base_id,base_desc FROM $db_table_bases ORDER BY base_desc";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, NULL, NULL, $sql);
	
	while (!$rc->EOF) {
		echo '<br /><a href="index.php?m=recipes&amp;a=search&amp;search=yes&amp;base_id='.$rc->fields['base_id'].'">'.$rc->fields['base_desc'].'</a>';
		$rc->MoveNext();
	}
	if ($SMObj->isSecureLogin())
		$SMObj->getLoginForm(NULL,"./index.php?m=admin&a=account");
?>
	</td>
	<td width="6">&nbsp;</td>
	<td><br />
<?php
	// Print out a submenu for the user to navigate
	printSubMenu($menu_items);
	}
} // end no format
?>

