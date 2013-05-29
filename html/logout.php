<?
$page = "index.php";

if (isset($_GET['page'])){
	$page = $_GET['page'];
}

session_start();
unset($_SESSION);
session_destroy();

header("Location:$page");

?>