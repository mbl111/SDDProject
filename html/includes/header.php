<?
	//Because we call this before ANYTHING else
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	$starttime = microtime_float();

	session_start();
	include_once("dbConnect.php");
	include_once("include.php");
	include_once("engine.php");
	
	if (loggedIn()){
		$q = dbQuery("SELECT active FROM users WHERE `id`={$_SESSION['userid']}");
		if ($q){
			$a = mysql_fetch_assoc($q);
			if ($a['active'] == 0){
				unset($_SESSION);
				session_destroy();
				header("Location:message.php?id=4");
			}
		}
		dbQuery("UPDATE users SET `lastactive`=".time()." WHERE `id`={$_SESSION['userid']}");
		$profile = dbQuery("SELECT firstname, lastname, timezone, admin FROM users WHERE `id`={$_SESSION['userid']} LIMIT 1");
		$profile = mysql_fetch_assoc($profile);
		$_SESSION['firstname'] = $profile['firstname'];
		$_SESSION['lastname'] = $profile['lastname'];
		$_SESSION['timezone'] = $profile['timezone'];
		$_SESSION['admin'] = $profile['admin'];
		$teacherLinks = "";
		if ($_SESSION['usertype']==USER_TEACHER){
			$teacherLinks = "<li><a href='students.php' class='toolboxlink'>Students</a></li>
							<li><a href='submitnews.php' class='toolboxlink'>Post News</a></li>";
		}
		addToolBox(myFullName(), "
		<ul class='toolboxlinklist'>
			<li><a href='userpage.php?id={$_SESSION['userid']}' class='toolboxlink'>My Account</a></li>
			$teacherLinks
			<li><a href='' class='toolboxlink'>Notifications (6)</a></li>
			<li><a href='' class='toolboxlink'>Messages (1)</a></li>
			<li><a href='settings.php' class='toolboxlink'>Settings</a></li>
			<li><a href='logout.php?page=index.php' class='toolboxlink'>Logout</a></li>
		</ul>
		");
	}
	
	$headerTemplate = new Template;
	$headerTemplate->assign("LOGGED_IN", loggedIn() ? "true" : "false");
	$headerTemplate->render('header');
?>