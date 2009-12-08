<?php
	require("../core_API.php");
	$UMObj = newSecurityModel("database");
	$UMObj->setDataSource("postgres","localhost:5432","postgres","","testdb");
	$UMObj->openDataSource(); // open the connection
?>
<html>
<head>
<title>Test Add/Edit Page</title>
</head>
<body>
<?php
	$UMObj->getUserAddEditFormSubmit();
	if ($_REQUEST['um_submit_form'] != "yes") // only show the form if they have not submitted
		$UMObj->getUserAddEditForm();
	saveSecurityModel($UMObj);	
?>
</body>
</html>