<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['pass']) and isset($_POST['pass2']) and isset($_POST['id'])){
	$id = $_POST['id'];
	if ($id == $_SESSION['userid'] || studentBelongsTo($_SESSION['userid'], $id)){
		$first = md5($_POST['pass']);
		$second = md5($_POST['pass2']);
		if ($first == $second){
			$query = dbQuery("UPDATE users SET `password`='$first' WHERE id={$id}");
			echo "true";
		}else{
			echo "Passwords did not match";
		}
	}else{
		echo "Not your account or student!";
	}
}else{
	echo "Cant change your password to be blank!";
}

?>