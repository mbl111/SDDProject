<?
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();
	mustBeLoggedin();
	mustBeTeacher();
	
	$errors = array();
	$symbols = array("\"", "/", "'", "`","~"," ",".",",");
	if (isset($_POST['submit'])){
	
		if ($_POST['lastname'] != "" and $_POST['firstname'] != ""){
			$firstname = strip_tags(mysql_real_escape_string($_POST['firstname']));
			$lastname = strip_tags(mysql_real_escape_string($_POST['lastname']));
			$q = dbQuery("SELECT username FROM users WHERE `firstname`='$firstname' AND `lastname`='$lastname'");
			$number = mysql_num_rows($q);
			if ($number == 0){
				$number = "";
			}
			
			$username = strtolower($_POST['firstname']).".".strtolower($_POST['lastname']).$number;
			
			$useranme = str_replace($symbols,"", $username);
			
			$password = generateRandomString();
			$pass = md5($password);
			
			$username = strip_tags(mysql_real_escape_string($username));
			
			
			dbQuery("INSERT INTO users (`firstname`, `lastname`, `username`, `password`, `usertype`, `admin`, `active`, `class`, `teacher`, `joined`, `lastactive`) VALUES ('$firstname', '$lastname','$username','$pass',1,0,1,NULL,{$_SESSION['userid']}, ".time().", ".time().")");
			$result = mysql_fetch_assoc(dbQuery("SELECT id FROM users WHERE username='$username'"));
			dbQuery("INSERT INTO user_details (`id`, `bio`) VALUES ({$result['id']},'')");
			
			echo "<div id='contentbox'>Student added successfully! Please retain the following information and pass it on to the student.<br/><br/>
				Username: <span style='font-weight:600;font-style: italic;'>$username</span><br/>
				Password: <span style='font-weight:600;font-style: italic;'>$password</span>
				</div>";
			
		}else{
			$error[] = "Please fill in all fields.";
		}
	}
	
	if ((isset($_POST['submit']) == false) or !empty($errors)){
?>
	<link rel="stylesheet" type="text/css" href="css/form.css" />

		<form id="contentbox" method="post" action="#">
			<div class="contentboxbody">
					
				<div class="field">
					<label>Firstname:</label>
					<input class="input" type="text" name="firstname" id="firstname" value="<?if (isset($_POST["firstname"])){echo $_POST["firstname"];}?>"/>
					<span class="hint">Students First name.</span>
				</div>
				
				<div class="field">
					<label>Lastname:</label>
					<input class="input" type="text" name="lastname" id="lastname" value="<?if (isset($_POST["lastname"])){echo $_POST["lastname"];}?>"/>
					<span class="hint">Students Last name.</span>
				</div>
				
				<div class="field">
						<label>Done?</label>
						<input class="input" style="width:412px;font-weight:bold;" type="submit" name="submit" value="Add student!"/>
				</div>
				
			</div>
		</form>
<?
	}
	endMainContent();
	footer();
?>