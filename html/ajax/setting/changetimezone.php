<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['tz']) and isset($_POST['id'])){
	$id = $_POST['id'];
	if ($id == $_SESSION['userid'] || studentBelongsTo($_SESSION['userid'], $id)){
		$first = strip_tags(mysql_real_escape_string($_POST['tz']));
		$query = dbQuery("UPDATE users SET `timezone`='$first' WHERE id={$id}");
		echo "true";
	}
}else{
	echo "Cant change your timezone to be blank!";
}

?>