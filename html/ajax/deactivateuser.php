<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
if (isset($_POST['id'])){
	$id = $_POST['id'];
	if (loggedIn()){
		if ($_SESSION['usertype'] == 0){
			$query = dbQuery("UPDATE users SET active=0 WHERE id=$id");
			echo "true";
		}else{
			echo "false";
		}
	}else{
		echo "false";
	}
}else{
	echo "false";
}

?>