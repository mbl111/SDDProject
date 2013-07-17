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
	
	$bugManager = preg_match("/bug/", $_SERVER['PHP_SELF']);
	
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
			$teacherLinks .= "<li><a href='students.php' class='toolboxlink'>Students</a></li>";
			$teacherLinks .= "<li><a href='createclass.php' class='toolboxlink'>Create Class</a></li>";
		}
		if ($_SESSION['usertype']==USER_TEACHER or isAdmin()){
			$teacherLinks .= "<li><a href='submitnews.php' class='toolboxlink'>Post News</a></li>";
		}
		if (!$bugManager){
			addToolBox(myFullName(), "
			<ul class='toolboxlinklist'>
				<li><a href='index.php' class='toolboxlink'>Home</a></li>
				<li><a href='userpage.php?id={$_SESSION['userid']}' class='toolboxlink'>My Account</a></li>
				$teacherLinks
				<li><a href='settings.php' class='toolboxlink'>Settings</a></li>
				<li><a href='logout.php?page=index.php' class='toolboxlink'>Logout</a></li>
			</ul>
			");
		}else{
			addToolBox(myFullName(), "
			<ul class='toolboxlinklist'>
				<li><a href='../index.php' class='toolboxlink'>Quiz JaM Home</a></li>
				<li><a href='index.php' class='toolboxlink'>Bug List</a></li>
				<li><a href='reportbug.php' class='toolboxlink'>Report a bug</a></li>
				<li><a href='../logout.php?page=but/index.php' class='toolboxlink'>Logout</a></li>
			</ul>
			");
		}
	}else{
		if (!$bugManager){
			addToolBox("Welcome!", "Have an account? Why not click 'Login' in the top right corner to get started.<br/><br/>Students - If you don't have an account talk to your teacher about this website.<br/><br/>Teachers - You can register an account which can be used to manage your students.");
		}else{
			addToolBox("Bug Manager", "
			<ul class='toolboxlinklist'>
				<li><a href='../index.php' class='toolboxlink'>Quiz JaM Home</a></li>
			</ul>");
		}
		if (basename($_SERVER['PHP_SELF']) != "index.php" and basename($_SERVER['PHP_SELF']) != "message.php"  and basename($_SERVER['PHP_SELF']) != "register.php"){
			header("Location:index.php");
		}
	}
	
	$headerTemplate = new Template;
	$headerTemplate->assign("LOGGED_IN", loggedIn() ? "true" : "false");
	$headerTemplate->assign("PAGE_TITLE", getTitle(basename($_SERVER['PHP_SELF'])));
	$headerTemplate->render('header');
?>