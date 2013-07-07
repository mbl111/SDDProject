<?php
define("HOST", "localhost");
define("DB_USERNAME", "website");
define("DB_PASS", "T7PeY2cvd7s4seCZ");
define("DB_NAME", "quizsite");
//opens MYSQL connection
mysql_connect(HOST,DB_USERNAME,DB_PASS)or die("Cannot connect to server");
//select database
mysql_select_db(DB_NAME)or die("Could not select DB");


function dbQuery($query){
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}

?>