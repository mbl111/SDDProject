<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['pass'])){
	$first = md5($_POST['pass']);
	$query = dbQuery("UPDATE users SET `password`='$first' WHERE id={$_SESSION['userid']}");
	echo "true";
}else{
	echo "Cant change your password to be blank!";
}

?>