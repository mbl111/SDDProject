<?php
$host="localhost";
$username="website";
$password="T7PeY2cvd7s4seCZ";
$db_name="quizsite";
//opens MYSQL connection
mysql_connect("$host","$username","$password")or die("Cannot connect to server");
//select database
mysql_select_db("$db_name")or die("Could not select DB");

function dbQuery($query){
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}

?>