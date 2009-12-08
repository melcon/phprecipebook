<?
require_once("classes/DBUtils.class.php");

$searchText = isset ($_GET['search'] ) ? $_GET['search'] : ".*";
$searchLimit = isset ($_GET['limit'] ) ? $_GET['limit'] : 100;

$count = 0;
$sResult = "";

$sql = "SELECT * FROM $db_table_ingredients";
$ingredients = $DB_LINK->Execute($sql);


while (!$ingredients->EOF) 
{
	if ($searchText != "" && eregi($searchText, $ingredients->fields['ingredient_name']))
	{
		$sResult .= "|".$ingredients->fields['ingredient_id']."#".$ingredients->fields['ingredient_name'];
	}
    $ingredients->MoveNext();
}

// return a friendly no-found message
if ($sResult == "")
{
	$sResult = "|#No Results for '$searchText' Found";
}

// remove the leading to delim
if ($sResult[0] == "|")
{
	$sResult = substr( $sResult, 1, strlen( $sResult ) - 1 );
}
echo $sResult;
?>

