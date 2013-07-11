<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['id']) && ! empty($_POST['id'])){
    $id = $_POST['id'];
	if (isAdmin()){
		dbQuery("UPDATE content SET `class`=-1 WHERE `nid`=$id LIMIT 1");
		echo "true";
	}
}
?>