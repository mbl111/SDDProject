<?
	include("includes/header.php");
	
	if (isset($_POST['submitnews'])){
		$id = $_POST['classtopost'];
		if ($id > -1){
			$query = dbQuery("SELECT * FROM classes WHERE id=$id LIMIT 1");
			if (mysql_num_rows($query) == 0){
				header("Location:message.php?id=3");
			}
			$class = mysql_fetch_assoc($query);
		}
		if (($id == -1 and isAdmin()) or ($_SESSION['usertype'] == USER_TEACHER && $_SESSION['userid'] == $class['teacher'])){
			$title = $_POST['title'];
			$body = $_POST['body'];
			$title = strip_tags(mysql_real_escape_string($title));
			$body = strip_tags(mysql_real_escape_string($body));
			$body = str_replace("\\r\\n", "<br/>", $body);
			$timestamp = time();
			$insert = dbQuery("INSERT INTO content (`title`,`class`,`type`, `timestamp`) VALUES ('$title', $id, 'news', ".$timestamp.")");
			if ($insert){
				$q = dbQuery("SELECT `nid` FROM content WHERE `timestamp`=$timestamp");
				$data = mysql_fetch_assoc($q);
				dbQuery("INSERT INTO content_news (`id`, `body`, `poster`) VALUES ({$data['nid']}, '$body', {$_SESSION['userid']})");
			}else{
				header("Location:message.php?id=6");
			}
			header("Location:classpage.php?id=$id");
		}
	}
	
	drawToolBoxes();
	beginMainContent();
?>
	
<?
	$template = new Template;
	
	$defaultSelectedClass = -1;
	if (isset($_GET['class'])){
		$defaultSelectedClass = $_GET['class'];
	}
	
	$query = dbQuery("SELECT `name`, `id` FROM classes WHERE `teacher`={$_SESSION['userid']}");
	$classesDropDown = "";
	//<option value="-12.0" '; if ($selected == -12){echo "selected";} echo'>(GMT -12:00) Eniwetok, Kwajalein</option>
	
	if (isAdmin()){
		$classesDropDown .= "<option value='-1'>Global News (Admin Only)</option>";
	}
	
	while (($row = mysql_fetch_assoc($query))){
		$selected = $defaultSelectedClass == $row['id'] ? "selected" : "";
		$classesDropDown .= "<option value='{$row['id']}' $selected>{$row['name']}</option>";
	}
	
	
	$template->assign('CLASS_DROP_DOWN_OPTIONS', $classesDropDown);
	$template->render("submitnews");
	
	endMainContent();
	footer();
?>