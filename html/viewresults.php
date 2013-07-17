<?
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();
	
	$quizId = 0;
	if (isset($_GET['id'])){
		$quizId = $_GET['id'];
	}
	
	$query = dbQuery("SELECT * FROM content WHERE `nid`=$quizId");
	if (!$query){
		header("Location:message.php?id=5");
	}else{
		$quizContent = mysql_fetch_assoc($query);
		if ($quizContent['type'] != 'quiz'){
			header("Location:message.php?id=5");
		}else{
			
			$doneQuiz = userHasDoneQuiz($quizId, $_SESSION['userid']);
			
			$quizDescQ = dbQuery("SELECT * FROM content_quiz WHERE `id`=$quizId");
			$quizDesc = mysql_fetch_assoc($quizDescQ);
			
			$quizOverdue = $quizDesc['due'] < time();
			
			
			
		}
	}
	
	$descTemplate = new Template;
	$descTemplate->assign("CONTENT_ID", $quizId);
	$descTemplate->assign("CONTENT_TITLE", $quizContent['title']);
	$descTemplate->assign("CONTENT_BODY", $quizDesc['body']);
	$descTemplate->assign("CONTENT_USER", resolveFullnameFromID($quizDesc['poster']));
	$descTemplate->assign("CONTENT_TIME", date($dateFormat, $quizContent['timestamp']));
	$descTemplate->assign("QUIZ_STATUS", $doneQuiz ? "Quiz Completed" : "Not Complete");
	$descTemplate->assign("QUIZ_DONE", $doneQuiz ? "true" : "false");
	
	$descTemplate->assign("ADMIN", isAdmin() ? "true" : "false");
	$descTemplate->assign("POSTER", (loggedIn() and $quizDesc['poster'] == $_SESSION['userid']) ? "true" : "false");
	if ($doneQuiz){
		$descTemplate->assign("QUIZ_MARKS", getUserMarksForQuiz($quizId, $_SESSION['userid']));
	}
	$descTemplate->assign("QUIZ_QUESTION_COUNT", getNumberOfQuestionsForQuiz($quizId));
	$descTemplate->assign("QUIZ_PAGE", "false");
	$descTemplate->assign("GLOBAL_STORY", $quizContent['class'] == -1 ? 'true' : 'false');
	$descTemplate->assign("CLASS_NAME", getClassName($quizContent['class']));
	$descTemplate->assign("QUIZ_DUE", date($dateFormat, getTimeWithZone($quizDesc['due'], $_SESSION['timezone'])));
	$descTemplate->assign("QUIZ_OVERDUE", $quizOverdue ? 'true' : 'false');
	$descTemplate->render('quiz');
	
	echo '<link rel="stylesheet" type="text/css" href="css/student.css"/>';
		$sort = array("ln", "a");
		if (isset($_GET["srt"])){
			$sort[0] = $_GET["srt"];
		}
		if (isset($_GET['t'])){
			$sort[1] = $_GET['t'];
		}
		
			$bonusString = "";
		
			if (count($sort) == 2){
				if ($sort[0] == "ln"){
					if ($sort[1] == "d"){
						$bonusString .= " ORDER BY lastname DESC";
					}else{
						$bonusString .= " ORDER BY lastname ASC";
					}
				}elseif ($sort[0] == "fn"){
					if ($sort[1] == "d"){
							$bonusString .= " ORDER BY firstname DESC";
						}else{
							$bonusString .= " ORDER BY firstname ASC";
					}
				}elseif ($sort[0] == "jn"){
					if ($sort[1] == "d"){
							$bonusString .= " ORDER BY joined DESC";
						}else{
							$bonusString .= " ORDER BY joined ASC";
					}
				}elseif ($sort[0] == "ls"){
					if ($sort[1] == "d"){
							$bonusString .= " ORDER BY lastactive DESC";
						}else{
							$bonusString .= " ORDER BY lastactive ASC";
					}
				}
			}
		
			$query = dbQuery("SELECT * FROM users  WHERE `class` LIKE '%{$quizContent['class']}%' AND `usertype`=1".$bonusString);
			$amt = mysql_num_rows($query);
			echo "<form method='GET'>
				Sort By
				<select name='srt' id='' class='input' style='width:115px;'>
					<option value='fn' "; if ($sort[0] == 'fn'){echo "selected ";} echo ">First Name</option>
					<option value='ln' "; if ($sort[0] == 'ln'){echo "selected ";} echo ">Last Name</option>
					<option value='mk' "; if ($sort[0] == 'mk'){echo "selected ";} echo ">Marks</option>
					<option value='st' "; if ($sort[0] == 'st'){echo "selected ";} echo ">Submit Time</option>
				</select>
				Direction
				<select name='t' id='' class='input' style='width:160px;'>
					<option value='a' "; if ($sort[1] == 'a'){echo "selected ";} echo ">Ascending</option>
					<option value='d' "; if ($sort[1] == 'd'){echo "selected ";} echo ">Descending</option>
				</select>
				<input type='submit' value='Filter' id='loginButton'/>
				</form>";
			echo "<br/><span>Green: Student has done quiz. Red: Student has not done quiz</span><br/>";
			if ($amt == 0){
				echo "You have no students that have done the quiz";
			}else{
				echo "<div id='table'>
				<table id='contentbox'><tr class='contentboxheader' style='font-size:16px;'><th>Last Name</th><th>First Name</th><th>Submitted</th><th>Marks</th></tr>";
				$i = 0;
				$overallMarks = 0;
				$totalMarks = getNumberOfQuestionsForQuiz($quizId);
				while (($row = mysql_fetch_assoc($query)) != false){
					if (in_array($quizContent['class'], explode(",", $row['class']))){
						
						$col = "#BBFFBB";
						if (!userHasDoneQuiz($quizId, $row['id'])){
							$col = "#FFBBBB";
						}
						$marks = getUserMarksForQuiz($quizId, $row['id']);
						$date = getTimeUserSubmittedQuiz($quizId, $row['id']);
						$overallMarks += $marks;
						echo "<tr id='user_{$row['id']}' class='contentboxbody' style='background-color:$col'>
						<td>".$row['lastname']."</td>
						<td>".$row['firstname']."</td>
						<td>".(($date == -1) ? "Not Submitted" : date($dateFormat, getTimeWithZone($row['joined'], $_SESSION['timezone'])))."</td>
						<td>".$marks." / $totalMarks</td>
						</tr>";
						$i++;
					}
				}
				echo "</table></div>";
				echo "<div id='contentbox'>
					<div class='contentboxbody'>
						Total possible quiz marks: $totalMarks<br/>
						Average Marks: ".($overallMarks / $i)."
					</div>
				</div>";
			}
	
?>
	
<?	
	endMainContent();
	footer();
?>