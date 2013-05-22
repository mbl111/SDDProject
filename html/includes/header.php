<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd"> 
<html>
<body>
<link rel="stylesheet" type="text/css" href="../css/header.css" />
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

<div id="wrapper" style="width:1000px;background-color:#EEEEEE;margin:auto;padding:10px;">
	<div id="header">
		<div id="floatbar">
		<aside id="innerfloatbar" style="">
			<span style="font-size:22px;font-weight:bold;">Quizman</span>
			<span class="usergreeting">Welcome guest! Please <a href="javascript:login();" class="usergreetinglink">login</a> or <a href="regiser.php" class="usergreetinglink">register</a></span>
		</aside>
		</div>
		<div id="menu"></div>
	</div>
	
	<link rel="stylesheet" type="text/css" href="../css/login.css" />
	<div id="loginback" onclick="logincancel();"></div>
		<form method="post" action="login.php?page=/index.php" id="login">
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
	<link rel="stylesheet" type="text/css" href="../css/basecontent.css" />
	<div id="contentarea">
		<div class="sideelements">
		Side
		</div>
		<div class="centerelement">
		Hey
		</div>
		<div class="sideelements">
		Side2
		</div>
	</div>
	<div id="footer" style="height:100px;">
	Footman
	</div>
</div>
</body>
</html>