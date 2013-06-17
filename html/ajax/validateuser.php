<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['name']) && ! empty($_POST['name'])){
    $name = mysql_real_escape_string($_POST['name']);
	$exp = explode(" ", $name);
    $query = dbQuery("SELECT `id` FROM users WHERE `firstname`='{$exp[0]}' AND `lastname`='{$exp[1]}' LIMIT 1");
    if (mysql_num_rows($query) == 1){
		echo "true";
	}else{
		echo "No user found by this name";
	}
}
?>