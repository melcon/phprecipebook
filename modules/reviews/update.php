<?php
require_once("classes/DBUtils.class.php");

if (!$SMObj->getUserLoginID())
	die($LangUI->_('You must be logged in to rate/review recipes!'));

$recipe_id = (isValidID($_REQUEST['recipe_id'])) ? $_REQUEST['recipe_id'] : 0;
$review = (isset($_REQUEST['review'])) ? htmlentities($_REQUEST['review'], ENT_QUOTES, $LangUI->getEncoding()) : '';
$rating = (isset($_REQUEST['rating']) && is_numeric($_REQUEST['rating'])) ? $_REQUEST['rating'] : 0;
$owner = $SMObj->getUserLoginID();
$ip = $_SERVER['REMOTE_ADDR'];

if ($review != '') {
	$sql = "INSERT INTO $db_table_reviews (review_recipe, review_comments, review_owner) VALUES (".$DB_LINK->addq($recipe_id, get_magic_quotes_gpc()).", '".$DB_LINK->addq($review, get_magic_quotes_gpc())."', '".$DB_LINK->addq($owner, get_magic_quotes_gpc())."')";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, $LangUI->_('Review submitted successfully'), $LangUI->_('Failed to save review!'), $sql);
}
if ($rating && $ip != '') {
	$sql = "INSERT INTO $db_table_ratings (rating_recipe, rating_score, rating_ip) VALUES (".$DB_LINK->addq($recipe_id, get_magic_quotes_gpc()).", ".$DB_LINK->addq($rating, get_magic_quotes_gpc()).", '".$DB_LINK->addq($ip, get_magic_quotes_gpc())."')";
	$rc = $DB_LINK->Execute( $sql );
	DBUtils::checkResult($rc, $LangUI->_('Rating submitted successfully'), $LangUI->_('You have already rated this recipe!'), NULL);
}
?>