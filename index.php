<?php
global $SMObj, $DB_LINK, $LangUI;
// Include the required files
require_once("includes/config_inc.php");
require_once("custom_inc.php"); 			// override the default settings here
require_once("libs/phpsm/core_API.php");
require_once("classes/LangUI.class.php");
require_once("classes/ShoppingList.class.php");
require_once("classes/Recipe.class.php");
require_once("classes/Ingredient.class.php");
require_once("includes/functions.php");

// Create the security object and retrieve the database object
$SMObj = newSecurityModel("database"); // is one option
$SMObj->setDataSource($g_rb_database_type,$g_rb_database_host,$g_rb_database_user,$g_rb_database_password,$g_rb_database_name);
$SMObj->setDebug($g_rb_debug);
$SMObj->openDataSource(); // open the connection

$DB_LINK = $SMObj->getDataSource(); // get the database connection object (adodb)

// Global function, isValidID()
function isValidID($id) {
    if (empty($id)) return false;
    elseif ($id < 1) return false;
    elseif (phpversion() >= '5.2.0' && !filter_var($id, FILTER_VALIDATE_INT)) 
	return false;
    elseif (!is_numeric($id)) return false;
    else return true;
}

// Global Function, isValidLetter()
function isValidLetter($letter, $exception)
{
    if (empty($letter)) return false;
    elseif (strlen($letter) > 1) return false;
    elseif ($letter == $exception) return true;
    elseif (!preg_match("/[A-Z\s_]/i", $letter) > 0) return false;
    else return true;
}

$LangUI = new LangUI; // handles translations
global $g_browser_lang; // give global access to this information
$g_browser_lang = getBrowserLanguage();
// preg_match for letters, numbers, underscore, and hyphen. Reject anything else
$g_browser_lang = (preg_match('/^(\w|\-)+$/', $g_browser_lang)) ? $g_browser_lang : null;

// Load the language file based on config or logged in user
if ($SMObj->getUserLoginID() != NULL) {
	$userID = $SMObj->getUserLoginID();
	$details = $SMObj->getUserDetails($userID);
	include "lang/".$details['language'].".php";
} else if ($g_browser_lang != NULL) {
	//we found a browser match load it if it exists
	if (file_exists('lang/'.$g_browser_lang.'.php'))
		include "lang/".$g_browser_lang.".php";
	else
		include "lang/".$g_rb_language.".php";
} else {
	//nothing matched, load the default
	include "lang/".$g_rb_language.".php";
}

// Convience debugging option, shows what version the database thinks it is.
if ($g_rb_debug) {
	require_once("classes/DBUtils.class.php");
	$msg = DBUtils::checkDBSchemaVersion();
}

// langArray is set in en.php, it.php...etc..
$LangUI->setLangArray( $langArray );
$LangUI->setEncoding($langEncoding);
$SMObj->setTranslationObject($LangUI); // pass that info on to the security manager
if ($SMObj->isSecureLogin())
	$SMObj->getLoginFormSubmit();
else {
	// auto login
	if ($SMObj->getUserLoginID() == "")
		$SMObj->login();
}

// End of Login/Session stuff, now on to displaying the page //
// m = the module, cf modules directory, eg 'search'
// preg_match for letters, numbers, underscore, and hyphen. Reject anything else
$m = (isset($_GET['m']) && preg_match('/^(\w|\-)+$/', $_GET['m'])) ? $_GET['m'] : $g_rb_default_module;

// a = action, default is the index page of the module
// preg_match for letters, numbers, underscore, and hyphen. Reject anything else
$a = (isset($_GET['a']) && preg_match('/^(\w|\-)+$/', $_GET['a'])) ? $_GET['a'] : $g_rb_default_page;

// print = format for printing (minimal formating)
$print = isset( $_REQUEST['print'] ) ? $_REQUEST['print'] : 'no';
$format = isset( $_REQUEST['format'] ) ? $_REQUEST['format'] : 'yes';

// Load the header stuff
require "themes/$g_rb_theme/header.php";

// preg_match for letters, numbers, underscore, and hyphen. Reject anything else
if (!empty($_REQUEST['dosql']) && preg_match('/^(\w|\-)+$/', $_REQUEST['dosql'])) {
	include "modules/$m/".$_REQUEST['dosql'].".php";
}

$msg = $SMObj->getErrorMsg();

if ($msg != '') {
	echo $msg . "<p>";
	$SMObj->setErrorMsg(''); //reset it, we have read it.
}

// Load the module that is requested
if (file_exists("modules/$m/$a.php")) {
	include "modules/$m/$a.php";
} else {
	include "modules/$g_rb_default_module/$g_rb_default_page.php";
}

if ($format == "yes")
{
	// And the default footer to close things
	require "themes/$g_rb_theme/footer.php";
}

// Save the session infor and clean things up
saveSecurityModel($SMObj);
$LangUI->cleanUp();
?>
