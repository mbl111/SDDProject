<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['firstname']) and isset($_POST['lastname']) and isset($_POST['id'])){
	$id = $_POST['id'];
	if ($_SESSION['namechanged'] == 1 and $_SESSION['userid'] == $id){
		echo "Name already changed!";
		die();
	}	
	
	if ($id == $_SESSION['userid'] || studentBelongsTo($_SESSION['userid'], $id)){
		$first = strip_tags(mysql_real_escape_string($_POST['firstname']));
		$last = strip_tags(mysql_real_escape_string($_POST['lastname']));
		$query = dbQuery("UPDATE users SET `firstname`='$first',`lastname`='$last',`namechanged`=1 WHERE id={$id}");
		echo "true";
	}else{
		echo "Not your account or student!";
	}
}else{
	echo "Cant change your name to be blank!";
}

?>