<?php
require_once("classes/DBUtils.class.php");

// Set the OS type, only Linux and Windows known to work for sure
$os_name = getOsName();
if ($os_name == "unix") { //matches most unixes
	$pathSeparator="/";
	$baseDir = ".\/";	// were we start searching for strings
} else if ($os_name == "windows") {
	$pathSeparator="\\";
	$baseDir = ".";	 	// were we start searching for strings
}

echo "<html><body>\n";

if (!isset($_REQUEST['total_keys'])) {
?>

<form action="index.php?m=admin&a=translation" method="POST">
<input type="hidden" name="mode" value="select">
<?php
	$user_lang = isset( $_POST['user_lang'] ) ? $_POST['user_lang'] : $g_rb_language;
	$lang = $g_browser_lang;					// get the browser language if set
	if ($user_lang != "") $lang = $user_lang; 	//read from the form
	$langFile = "lang/$lang.php"; 				// were to read the file from
	
	echo DBUtils::arraySelect( $g_sm_supported_languages, 'user_lang', 'size="1"', $lang );
	// Now we should load up the language file select by the user or by browser detection
	$fp = fopen ("." . $pathSeparator . $langFile, "r");
	require ("." . $pathSeparator . $langFile);
	$buffer = fgets($fp, 4096); // chop off the php start part
	$read = true;
	$file_text="";
	while ($read && !feof($fp)) {
		$buffer = fgets($fp, 4096);
		if (preg_match("/langArray/",$buffer,$matches))
			$read=false;
		else
			$file_text.=$buffer;
		
	}
	fclose ($fp);
?>
  <input type="submit" value="<?php echo $LangUI->_('Load File');?>" class="button"><p>
</form>
<form action="index.php?m=admin&a=translation" method=POST>
<input type="hidden" name="mode" value="save">
<input type="hidden" name="lang" value="<?php echo $lang;?>">
<input type="submit" value="<?php echo $LangUI->_('Save Translation');?>" class="button"><p>
<textarea rows=10 cols=80 name="toptext"><?php echo $file_text;?></textarea>
<p>
<table border=1 cellpadding=2 cellspacing=2>
<?php
if ($os_name=="unix") 
{
	// use Unix find and grep
	if ((bool)ini_get('safe_mode'))
	{
		// have to use PHP functions for find and grep, this is slower
		find($baseDir,$files, '/.*?\.php$/',$pathSeparator);
		$array = grep($files,"/\->_\(\'.*?\'\)/");
	}
	else
	{
		// We can use find and grep
		$return = shell_exec("find $baseDir -follow -name \"*.php\" -exec grep -o -P \"\->_\(\'.*?\'\)\" {} /dev/null \\;");
		$array = preg_split ("/$baseDir/", $return);
	}
}
else if ($os_name=="windows") 
{
	// use php functions for find and grep
	find($baseDir,$files, '/.*?\.php$/',$pathSeparator);
	$array = grep($files,"/\->_\(\'.*?\'\)/i");
}

$keys = array();

foreach ($array as $h) {
	preg_match_all("/\->_\('(.*?)'\)/",$h,$matches);
	for ($i=1; $i < count($matches); $i++) {
		if (count($matches[$i]) > 1) {
			for ($j=0; $j < count($matches[$i]); $j++) {
				$keys[$matches[$i][$j]] = "";
			}
		} else {
			if (isset($matches[$i][0]))
			{
				$keys[$matches[$i][0]] = "b";
			}
		}
	}
}

$keys = array_keys($keys);
sort ($keys);
$count=0;
foreach ($keys as $key) {
	if ($key != "") {
		echo "<tr><td>";
		if (!isset($langArray[$key])) 
			echo "<font color=red>*</font>";
		echo "<input type=text name=\"key_$count\" size=50 value=\"$key\"></td>";
		echo "<td><input type=text name=\"value_$count\" size=50 value=\"" . (isset($langArray[$key]) ? $langArray[$key] : "") . "\"></td></tr>\n";
		$count++;
	}
}
?>
</table>
<input type="hidden" name="total_keys" value="<?php echo $count;?>">
<input type="submit" value="Save Translation"><p>
</form>
<?php
} else { 
	/*****
		Save the Modified Translation file
	******/
	$lang = $_REQUEST['lang'];
	$langFile = "lang/$lang.php"; // were you want the new file written
	$header = "<?php\n";
	$header .= $_REQUEST['toptext'];
	$header .= "\n\$langArray = array(\n";
	$header = stripslashes($header);
	
	$total = $_REQUEST['total_keys'];
	$fp = fopen($langFile, "w");
	fputs($fp, $header);
	for ($i=0; $i <= $total; $i++) {
		$key = "key_$i";
		$val = "value_$i";
		$str = '"' . $_REQUEST[$key] . '"=>"' . $_REQUEST[$val] . "\",\n";
		$str = stripslashes($str);
		fputs($fp, $str);
	}
	fputs($fp, ");\n?>\n");
	fclose($fp);
	echo $LangUI->_('Translation saved as new file') . ": $langFile<br />";
}
?>
