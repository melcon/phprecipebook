<?php
	require("../core_API.php");
	$UMObj = newSecurityModel("database");
	$UMObj->setDataSource("postgres","localhost:5432","postgres","","testdb");
	$UMObj->openDataSource(); // open the connection
?>
<html>
<head>
<title>Test Group Admin Page</title>
</head>
<body>
<?php
	$UMObj->getGroupAdminForm();
	saveSecurityModel($UMObj);	
?>
</body>
</html>