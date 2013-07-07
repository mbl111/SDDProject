<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['bio']) and isset($_POST['id'])){
	$id = $_POST['id'];
	if ($id == $_SESSION['userid'] or studentBelongsTo($_SESSION['userid'], $id)){
		$first = strip_tags(mysql_real_escape_string($_POST['bio']));
		$query = dbQuery("UPDATE user_details SET `bio`='$first' WHERE id={$id}");
		echo "true";
	}else{
		echo "Not your account or student!";
	}
}else{
	echo "Cant change your bio to be blank!";
}

?>