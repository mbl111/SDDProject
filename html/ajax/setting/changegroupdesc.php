<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['cdesc']) and isset($_POST['id'])){
	$id = $_POST['id'];
	$q = dbQuery("SELECT * FROM classes WHERE `id`=$id LIMIT 1");
	if (mysql_num_rows($q) == 1){
		$class = mysql_fetch_assoc($q);
		if ($_SESSION['userid'] == $class['teacher']){
			$body = strip_tags(mysql_real_escape_string($_POST['cdesc']));
			$body = str_replace("\\r\\n", "<br/>", $body);
			$query = dbQuery("UPDATE classes SET `description`='$body' WHERE id={$id}");
			echo "true";
		}else{
			echo "Not your class!";
		}
	}else{
		echo "Class not found";
	}
}else{
	echo "Cant change the description to be blank!";
}

?>