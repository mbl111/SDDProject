<?
	include("includes/header.php");
	$error = array();
	mustBeLoggedIn();
	if (isset($_POST['createclass'])){
		
		if (!isset($_POST['title']) or $_POST['title'] == ""){
			$error[] = "Please enter a class name";
		}
		
		if ($_SESSION['usertype'] == USER_STUDENT){
			$error[] = "Only teachers can do this!";
		}
		
		if (empty($error)){
			$body = "";
			if (isset($_POST['body']) and $_POST['body'] == ""){
				$body = $_POST['body'];
			}
			$name = $_POST['title'];
			$body = makeSafe($body);
			$name = makeSafe($name);
			$time = time();
			if (dbQuery("INSERT INTO classes (`teacher`, `name`, `description`, `joined`) VALUES ({$_SESSION['userid']}, '$name', '$body', $time)")){
				$query = dbQuery("SELECT `id` FROM classes WHERE `joined`=$time AND `teacher`={$_SESSION['userid']}");
				$class = mysql_fetch_assoc($query);
				
				$query = dbQuery("SELECT `class` FROM users WHERE `id`={$_SESSION['userid']}");
				$user = mysql_fetch_assoc($query);
				
				$classesForUser = explode(",", $user['class']);
				$classesForUser[] = $class['id'];
				$classes = implode(",", $classesForUser);
				dbQuery("UPDATE users SET `class`='$classes' WHERE `id`={$_SESSION['userid']}");
				header("Location:classpage.php?id={$class['id']}");
			}else{
				$error[] = "Failed to create class! Please try again";
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
	<link rel="stylesheet" type="text/css" href="css/form.css"/>
	<div id="contentbox">
		<div class="contentboxheader">Create a new class</div>
		<div class="contentboxbody">
		<form method="post" action="createclass.php" id="create">
			<div class="field">
				<label>Class Name:</label>
				<input class="input" type="text" name="title" id="title" />
			</div>
			<div class="field">
				<label>Description:</label>
				<textarea class="input" type="text" name="body" rows="6" maxlength='600'></textarea>
			</div>
			<div class="field">
				<label style="visibility:hidden;">.</label>
				<input class='input' type="submit" value="Create class" name='createclass' id="createclass"/>
			</div>
			
		</form>
		</div>
	</div>
<?	
	endMainContent();
	footer();
?>