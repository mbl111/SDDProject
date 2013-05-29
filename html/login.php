<?php

include("includes/header.php");
$error = array();
$page = "index.php";


if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if (loggedIn() == false){
	if (empty($_POST['username'])){
		$error[] = "<p class='error'>Please enter a username.</p>";
	}else if(preg_match("/^([a-zA-Z0-9]+)([a-zA-Z0-9-])(\.){1}([a-zA-Z0-9]+)([a-zA-Z0-9-])/",$_POST['username'])){
		$username = $_POST['username'];
	}else{
		$error[] = "<p class='error'>Username was incorrect.</p>";
	}
	//password
	if(empty($_POST['password'])){
        $error[] = "<p class='error'>Please enter a password.</p>";
    }else{
		$password = $_POST['password'];
    }
	
	if (empty($error)){
		$_SESSION['userid'] = 1;
		header("Location:$page");
	}
	
}else{
	$error[] = "<p class='error'>You are already logged in!</p>";
}
	drawToolBoxes();
	beginMainContent();
	
	if (!empty($error)){
		foreach($error as $err){
			echo $err;
		}
	}
	
	endMainContent();
	footer();
	
?>