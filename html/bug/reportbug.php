<?

	include("../includes/header.php");
	drawToolBoxes();
	beginMainContent();

	$errors = array();
	mustBeLoggedin();
	if (loggedIn()){
		if (isset($_POST['submit'])){
			if (isset($_POST['title']) and isset($_POST['desc']) and isset($_POST['reproduce'])){
				if ($_POST['title'] != "" and $_POST['desc'] != "" and $_POST['reproduce'] != ""){
					$time = time();
					$title = makeSafe($_POST['title']);
					$desc = makeSafe($_POST['desc']);
					$rep = makeSafe($_POST['reproduce']);
					dbQuery("INSERT INTO bugreports (`title`, `desc`, `steps`, `time`, `user`) VALUES ('$title', '$desc', '$rep', $time, {$_SESSION['userid']})");
					echo "<b style='margin-bottom:5px;'>Your report was submitted!</b>";
				}else{
					$errors[] = "Not all fields were filled out!";
				}
			}else{
				$errors[] = "Not all fields were filled out!";
			}
		}
	}else{
		$errors[] = "You need to be logged in to report a bug";
	}
	
	if (!empty($errors)){
		foreach ($errors as $error){
			echo "<p class='error'>$error</p>";
		}
	}
	if ((isset($_POST['submit']) == false) or !empty($errors)){
		?><link rel="stylesheet" type="text/css" href="css/form.css" />

		<form id="contentbox" method="post" action="#">
			<div class="contentboxbody">
				<div class="field">
					<label>Title:</label>
					<input class="input" type="text" name="title" id="title" value="<?if (isset($_POST["title"])){echo $_POST["title"];}?>"/>
					<span class="hint">A brief one line description of the bug</span>
				</div>
				
				<div class="field">
					<label>Description:</label>
					<textarea class="textarea" type="text" name="desc" id="desc" value="<?if (isset($_POST['desc'])){echo $_POST["desc"];}?>"></textarea>
					<span class='hint'>Detailed description of the bug or error</span>
				</div>
				
				<div class="field">
					<label>Steps to reproduce:</label>
					<textarea class="textarea" type="text" name="reproduce" id="reproduce" value="<?if (isset($_POST['reproduce'])){echo $_POST['reproduce'];}?>"></textarea>
					<span class='hint'>How can the bug or error be fixed?</span>
				</div>
				
				<div class="field">
						<label>Done?</label>
						<input class="input" style="width:412px;font-weight:bold;" type="submit" name="submit" value="Submit Bug!"/>
				</div>
				
			</div>
		</form><?
	}
?>


	
<?
	endMainContent();
	footer();
?>