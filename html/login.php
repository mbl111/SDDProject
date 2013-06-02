<?php

include("includes/header.php");
$error = array();
$page = "index.php";


if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if (loggedIn() == false){
	if (empty($_POST['username'])){
		$error[] = "Please enter a username.";
	}else if(preg_match("/^([a-zA-Z0-9]+)([a-zA-Z0-9-])(\.){1}([a-zA-Z0-9]+)([a-zA-Z0-9-])/",$_POST['username'])){
		$username = $_POST['username'];
	}else{
		$error[] = "Username was incorrect.";
	}
	//password
	if(empty($_POST['password'])){
        $error[] = "Please enter a password.";
    }else{
		$password = $_POST['password'];
    }
	
	if (empty($error)){
	
		$query = dbQuery("SELECT * FROM users WHERE `username`='{$username}' LIMIT 1");
		
		if ($query){
			$user = mysql_fetch_assoc($query);
			
			if ($user['password'] != $password){
				$error[] = "Username or password was incorrect";
			}
			
			if (empty($error)){
			
				if ($user['active'] == 0){
					if ($user['usertype'] == USER_STUDENT){
						$error[] = "Your account has yet to be enabled by a teacher";
					}elseif ($user['usertype'] == USER_TEACHER){
						
					}else{
						//File error because usertype is wrong
					}
				}elseif ($user['active'] == 2){
					$error[] = "Your account has been disabled";
				}
			
				if (empty($error)){
					$_SESSION['userid'] = $user['id'];
					$_SESSION['firstname'] = $user['firstname'];
					$_SESSION['lastname'] = $user['lastname'];
					$_SESSION['usertype'] = $user['usertype'];
					session_write_close();
					header("Location:$page");
				}
			}
		}else{
			$error[] = "Username or password was incorrect";
		}
	}
	
}else{
	$error[] = "You are already logged in!";
}
	drawToolBoxes();
	beginMainContent();
	
	if (!empty($error)){
		foreach($error as $err){
			echo "<p class='error'>".$err."</p>";
		}
	}
	
	endMainContent();
	footer();
	
?>