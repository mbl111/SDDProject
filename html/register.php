<?
	include("includes/header.php");
	
	if (isset($_POST['register'])){
	
		$error = array();
	
		if (!isset($_POST['fname']) or $_POST['fname'] == ""){
			$error[] = "Please enter a firstname";
		}
		
		if (!isset($_POST['lname']) or $_POST['lname'] == ""){
			$error[] = "Please enter a lastname";
		}
		
		if (!isset($_POST['email']) or $_POST['email'] == ""){
			$error[] = "Please enter an email";
		}
		
		if (!isset($_POST['pass1']) or $_POST['pass1'] == ""){
			$error[] = "Please enter a password";
		}
		
		if (!isset($_POST['pass2']) or $_POST['pass2'] == ""){
			$error[] = "Please confirm your password";
		}
		
		if (empty($error)){
			$fname = makeSafe($_POST['fname']);
			$lname = makeSafe($_POST['lname']);
			$email = $_POST['email'];
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['pass2'];
		
			if ($pass1 != $pass2){
				$error[] = "Passwords dont match!";
			}
			
			$parts2 = explode("@", $email);
			$partsgt2 = explode(".", $email);
			
//			if (array_count_values($parts2) != 2 or array_count_values($partsgt2) < 2){
//				$error[] = "Invalid E-mail!";
//			}
			if (empty($error)){
				
				$q = dbQuery("SELECT username FROM users WHERE `firstname`='$fname' AND `lastname`='$lname'");
				$number = mysql_num_rows($q);
				if ($number == 0){
				$number = "";
				}
			
				$username = strtolower($_POST['fname']).".".strtolower($_POST['lname']).$number;
				$password = md5($pass1);
				$now = time();
				
				dbQuery("INSERT INTO users (`firstname`, `lastname`, `username`, `password`, `usertype`, `admin`, `active`, `class`, `joined`, `lastactive`)
					VALUES ('$fname', '$lname', '$username', '$password', 0, 0, 1, '', $now, $now)");
				drawToolBoxes();
				beginMainContent();
				echo "<div id='contentbox'>Account created successfully!<br/><br/>
				Username: <span style='font-weight:600;font-style: italic;'>$username</span><br/>
				</div>";
				endMainContent();
				footer();
				die();
			}			
		}
		
	}
	
	drawToolBoxes();
	beginMainContent();
	if (!empty($error)){
		foreach($error as $err){
			echo "<p class='error'>$err</p>";
		}		
	}
?>
	<script>
	$(document).ready(function() {
		
			$("#changepassb").attr("disabled", true);
			$("#changepassb").css("background-color", "#A9A9A9");
			
			
			$("#pass1.input").keyup(function(e){
				validate();
			});
			
			$("#pass2.input").keyup(function(e){
				validate();
			});
			
			
			function validate(){
				var pass1 = $("#pass1.input").val();
				var pass2 = $("#pass2.input").val();
				if (pass1.length < 5 || pass1 != pass2){
					$("#pass1.input").css("border-color", "#FF0000");
					$("#pass2.input").css("border-color", "#FF0000");
					disableButton();
				return;
				}
				$("#pass1.input").css("border-color", "#00FF00");
				$("#pass2.input").css("border-color", "#00FF00");
				enableButton();
			}
			
			
			function disableButton(){
				$("#changepassb").attr("disabled", true);
				$("#changepassb").css("background-color", "#A9A9A9");
			}
			
			function enableButton(){
				$("#changepassb").removeAttr("disabled");
				$("#changepassb").css("background-color", "#F9F9F9");
			}
	});
	</script>
	<link rel="stylesheet" type="text/css" href="css/settingsform.css" />
	<div id='contentbox'>
		<div class="contentboxheader">Register as a teacher</div>
		<div class="contentboxbody">
		<form method="post" action="register.php" id="create">
			<div class="field">
				<label>First Name:</label>
				<input class="input" maxlength="20" type="text" name="fname" id="fname" />
			</div>
			
			<div class="field">
				<label>Last Name:</label>
				<input class="input" maxlength="20" type="text" name="lname" id="lname" />
			</div>
			
			<div class="field">
				<label>E-mail:</label>
				<input class="input" type="text" name="email" id="email" />
				<span class='hint'>Must be valid so that you can activate your account</span>
			</div>	
			
			<div class="field">
				<label>Password:</label>
				<input class="input" maxlength="20" type="password" name="pass1" id="pass1" />
				<span class='hint'>Password greater than 5 characters long.</span>
			</div>
			
			<div class="field">
				<label>Confirm Password:</label>
				<input class="input" maxlength="20" type="password" name="pass2" id="pass2" />
				<span class='hint'>Must match your password</span>
			</div>
			
			<div class="field">
				<label style="visibility:hidden;">.</label>
				<input class='input' type="submit" value="Register as a teacher!" name='register' id="register"/>
			</div>
			
		</form>
		</div>
	</div>
<?	
	endMainContent();
	footer();
?>