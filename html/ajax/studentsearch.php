<?
session_start();
include("../includes/include.php");
include_once("../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['search_term']) && ! empty($_POST['search_term'])){
    $search_term = mysql_real_escape_string($_POST['search_term']);
    $query = dbQuery("SELECT `firstname`,`lastname`,`id` FROM users WHERE `firstname` LIKE '$search_term%' OR `lastname` LIKE '$search_term%' AND `usertype`=1 LIMIT 8");
    while(($row = mysql_fetch_assoc($query))==true){
        $id = $row['id'];
        $fname = $row['firstname'];
		$lname = $row['lastname'];
        echo "<li id='dropdownname' class='$id' onclick='autoFill(this)'>$fname $lname</li>";
    }
}
?>