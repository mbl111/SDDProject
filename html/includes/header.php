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

function hide($id) {
    document.getElementById($id).style.display = "none";
    document.getElementById($id).style.display = "none";
}

function show($id) {
    document.getElementById($id).style.display = "none";
    document.getElementById($id).style.display = "none";
}
</script>

<div id="wrapper" style="width:1200px;background-color:#EEEEEE;margin:auto;height:100%">
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
		<aside id="sideelement">
			<div id="toolbox">
				<div class="toolboxheader">[SpA]mbl111</div>
				<div class="toolboxcontent">
					<ul class="toolboxlinklist">
						<li><a href="" class="toolboxlink">My Account</a></li>
						<li><a href="" class="toolboxlink">Messages</a></li>
						<li><a href="" class="toolboxlink">Settings</a></li>
						<li><a href="" class="toolboxlink">Log out</a></li>
					</ul>
				</div>
			</div>
		</aside>
		<div id="centerelement">
			<div id="contentbox">
				<div class="contentboxheader">Content Title Section!</div>
				<div class="contentboxbody">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eu nunc in felis mollis semper ac in turpis.
				Suspendisse risus ante, dapibus id auctor in, venenatis eu leo. Morbi gravida arcu sed nisi hendrerit non blandit lorem pharetra.
				Curabitur non nibh quam. Cras adipiscing rhoncus risus nec volutpat. Proin sodales nulla nec nisi pellentesque vel ultrices leo luctus.
				Aenean in felis risus.<br/><br/>
				Maecenas quam magna, tincidunt in aliquet nec, aliquam vitae nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra,
				per inceptos himenaeos. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse sit
				amet elit mollis mi gravida aliquet et vitae urna. Quisque nec neque mauris, non imperdiet erat. Aliquam lobortis porttitor quam et dictum.
				Etiam at est ut ligula semper convallis at et risus. Sed tincidunt commodo scelerisque. </div>
				<div class="contentboxfooter">Posted 24/05/2013 by [SpA]mbl111</div>
			</div>
		</div>
	</div>
	<div id="footer" style="height:50px; margin-top:10px;width:100%;">
	<span style="font-size:12px;font-style:italic;">© Matt and Justin</span>
	</div>
</div>
</body>
</html>