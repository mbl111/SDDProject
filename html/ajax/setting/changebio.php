<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['bio'])){
	$first = strip_tags(mysql_real_escape_string($_POST['bio']));
	$query = dbQuery("UPDATE user_details SET `bio`='$first' WHERE id={$_SESSION['userid']}");
	echo "true";
}else{
	echo "Cant change your bio to be blank!";
}

?>