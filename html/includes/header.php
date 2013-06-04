<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd"> 
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
</head>
<body>
<link rel="stylesheet" type="text/css" href="css/header.css" />
<script src="js/jquery.js"></script>

<script>
function login() {
    document.getElementById("login").style.display = "block";
    document.getElementById("loginback").style.display = "block";
    document.getElementById("outerfloatbar").setAttribute("class", "hidden");
    document.getElementById("username").focus();
}

function logincancel() {
    document.getElementById("login").style.display = "none";
    document.getElementById("loginback").style.display = "none";
    document.getElementById("outerfloatbar").setAttribute("class", "");
}
</script>

<?
	//Because we call this before ANYTHING else
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	$starttime = microtime_float();

	define("USER_STUDENT", 1);
	define("USER_TEACHER", 0);

	session_start();
	include_once("dbConnect.php");
	include_once("include.php");
	
	if (loggedIn()){
	
		dbQuery("UPDATE users SET lastactive=".time()." WHERE `id`={$_SESSION['userid']}");
		$teacherLinks = "";
		if ($_SESSION['usertype']==USER_TEACHER){
			$teacherLinks = "<li><a href='students.php' class='toolboxlink'>Students</a></li>";
		}
		addToolBox(myFullName(), "
		<ul class='toolboxlinklist'>
			<li><a href='userpage.php?id={$_SESSION['userid']}' class='toolboxlink'>My Account</a></li>
			$teacherLinks
			<li><a href='' class='toolboxlink'>Notifications (6)</a></li>
			<li><a href='' class='toolboxlink'>Messages (1)</a></li>
			<li><a href='' class='toolboxlink'>Settings</a></li>
			<li><a href='' class='toolboxlink'>Log out</a></li>
		</ul>
		");
	}
?>

<div id="wrapper" style="width:1200px;background-color:#EEEEEE;margin:auto;height:100%">
	<div id="header">
		<div id="floatbar">
		<aside id="innerfloatbar" style="">
			<a href="index.php" class="pagetitle"><?echo SITENAME;?></a>
			<? if (loggedIn()){
			echo '<span class="usergreeting">Welcome <a href="userpage.php?id='.$_SESSION['userid'].'" class="usergreetinglink">'.myFullName().'</a>! - <a href="logout.php?page=index.php" class="usergreetinglink">Logout</a></span>';
			}else{
			echo '<span class="usergreeting">Welcome guest! Please <a href="javascript:login();" class="usergreetinglink">login</a> or <a href="regiser.php" class="usergreetinglink">register</a></span>';
			}?>
		</aside>
		</div>
		<div id="menu"></div>
	</div>
	
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<div id="loginback" onclick="logincancel();"></div>
		<form method="post" action="login.php?page=index.php" id="login">
			<div id="loginbar">
			<aside id="inngerloginbar" style="">
				<span style="font-size:22px;font-weight:bold;text-shadow: 0px 0px 10px white;">Login</span>
				<span class="usergreeting"><a href="javascript:logincancel();" class="usergreetinglink">Close</a></span>
			</aside>
			</div>
			<div class="field">
				<label>Username:</label>
				<input class="input" type="text" name="username" id="username" />
			</div>
			<div class="field">
				<label>Password:</label>
				<input class="input" type="password" name="password" />
			</div>
				<span class="remember">Remember Me?</span>
				<input class="remembercheck" type="checkbox" name="remember" />
				<br/>
				<input type="submit" value="Login" id="loginButton"/>
			</form>
			
	<link rel="stylesheet" type="text/css" href="css/basecontent.css" />
	
	<div id="contentarea">
		<aside id="sideelement">