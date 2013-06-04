<?
$page = "index.php";

if (isset($_GET['page'])){
	$page = $_GET['page'];
}

session_start();
mustBeLoggedin();
unset($_SESSION);
session_destroy();

header("Location:$page");

?>