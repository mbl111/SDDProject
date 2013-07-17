<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['name']) && $_POST['name'] != ""){
    $name = mysql_real_escape_string($_POST['name']);
	$exp = explode(" ", $name);
    $query = dbQuery("SELECT `id` FROM users WHERE `firstname`='{$exp[0]}' AND `lastname`='{$exp[1]}' LIMIT 1");
    if (mysql_num_rows($query) == 1){
		$a = mysql_fetch_assoc($query);
		echo "true {$a['id']}";
	}else{
		echo "No user found by this name";
	}
}else{
	echo "No name entered";
}
?>