PHPSecurity Manager 0.1
	PHPSecurity Manager is php project to provide authentication mechanisms to php files.  In order to use the security manager you need to 
	just include on php file and create the object.  The settings for are globally set in one location to handle multiple projects authentication.
	
Example:

require_once("phpSecurityManager/core_API.php");

$SMObj = newSecurityModel("database"); // is one option
$SMObj->setDataSource($g_rb_database_type,$g_rb_database_host,$g_rb_database_user,$g_rb_database_password,$g_rb_database_name);
$SMObj->openDataSource(); // open the connection

$DB_LINK = $SMObj->getDataSource(); // get the database connection object (adodb)

// Get a form
$SMObj->getLoginFormSubmit(); // handles the login form when it is submitted
$SMObj->getLoginForm();

saveSecurityModel($SMObj); // save any changes made

For more example pages of how to use the forms with PHPSecurityManager look in the 'pages' directory.

Expanding PHPSecurityManager:
	You can create a new authentication source besides the currently supported database method.  You just need to create an extending class of
	SecurityManager. There is a template to follow in the classes/drivers directory, or you can copy SecurityManager_database.class.php and alter it
	work with a new source (file, LDAP....).
