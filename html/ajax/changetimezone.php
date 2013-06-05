<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['tz'])){
	$first = strip_tags(mysql_real_escape_string($_POST['tz']));
	$query = dbQuery("UPDATE users SET `timezone`='$first' WHERE id={$_SESSION['userid']}");
	$_SESSION['timezone'] = $first;
	echo "true";
}else{
	echo "Cant change your timezone to be blank!";
}

?>