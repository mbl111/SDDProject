<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['id']) && ! empty($_POST['id'])){
    $id = $_POST['id'];
    $query = dbQuery("SELECT `type` FROM content WHERE `nid`=$id LIMIT 1");
    if ($query){
		$a = mysql_fetch_assoc($query);
		$query = dbQuery("SELECT `poster` FROM content_{$a['type']} WHERE `id`=$id LIMIT 1");
		if ($query){
			$b = mysql_fetch_assoc($query);
			if ($b['poster'] == $_SESSION['userid'] or isAdmin()){
				dbQuery("UPDATE content SET `visible`=0 WHERE `nid`=$id LIMIT 1");
				echo "true";
			}
		}
	}else{
		echo "No content found.";
	}
}
?>