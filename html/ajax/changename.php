<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if ($_SESSION['namechanged'] == 1){
echo "Name already changed!";
die();
}
if (isset($_POST['firstname']) and isset($_POST['lastname'])){
	$first = strip_tags(mysql_real_escape_string($_POST['firstname']));
	$last = strip_tags(mysql_real_escape_string($_POST['lastname']));
		$query = dbQuery("UPDATE users SET `firstname`='$first',`lastname`='$last',`namechanged`=1 WHERE id={$_SESSION['userid']}");
		$_SESSION['namechanged'] = 1;
		$_SESSION['firstname'] = $first;
		$_SESSION['lastname'] = $last;
		echo "true";
}else{
	echo "Cant change your name to be blank!";
}

?>