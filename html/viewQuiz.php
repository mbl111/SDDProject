<?
	include("includes/header.php");
	
	if (isset($_POST['submit'])){
		$questioncount = $_POST['questioncount'];
		$quizid = $_POST['quizid'];
		if (!userHasDoneQuiz($quizid, $_SESSION['userid'])){
			$questionids = array();
			$answers = array();
			for ($i = 1; $i <= $questioncount; $i++){
				//For Mysql
				$ids[] = $_POST["questionid$i"];
				//For PHP
				$answers[] = array(
					'id' => $_POST["questionid$i"],
					'answer' => $_POST["answer$i"]
				);
			}
			$idArray = implode(",", $ids);
			$setQuestionsQuery = dbQuery("SELECT * from content_quiz_questions WHERE `id` IN ($idArray)");
			if ($setQuestionsQuery){
				$setQuestions = array();
				while (($setQuestion = mysql_fetch_assoc($setQuestionsQuery))){
					$setQuestions[$setQuestion['id']] = array(
						$setQuestion['1'] => 1,
						$setQuestion['2'] => 2,
						$setQuestion['3'] => 3,
						$setQuestion['4'] => 4
					);
				}
				
				$query = "INSERT INTO user_quiz_answers (`user_id`, `quiz_id`, `question_id`, `answer`, `timestamp`) VALUES";
				foreach ($answers as $a){
					$question = $setQuestions[$a['id']];
					$answer = $a['answer'];
					
					$query .= " (".$_SESSION['userid']." ,$quizid, ".$a['id'].", ".$question[$answer].", ".time()."),";
				}
				if (!dbQuery(rtrim($query, ","))){
					header("Location:message.php?id=7");
				}
			}else{
				header("Location:message.php?id=7");
			}
		}else{
			header("Location:message.php?id=8");
		}
	}
	
	$quizId = -1;
	if (isset($_GET['id'])){
		$quizId = $_GET['id'];
	}else{
		header("Location:messages.php?id=5");
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
	
	drawToolBoxes();
	beginMainContent();
?>
	
	<script src='js/ui/jquery.ui.effect.js'></script>
	
<?
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
	$descTemplate->assign("QUIZ_PAGE", "true");
	$descTemplate->assign("GLOBAL_STORY", $quizContent['class'] == -1 ? 'true' : 'false');
	$descTemplate->assign("CLASS_NAME", getClassName($quizContent['class']));
	$descTemplate->assign("QUIZ_DUE", date($dateFormat, getTimeWithZone($quizDesc['due'], $_SESSION['timezone'])));
	$descTemplate->assign("QUIZ_OVERDUE", $quizOverdue ? 'true' : 'false');
	$descTemplate->render('quiz');
	
	$questionQuery = dbQuery("SELECT * FROM content_quiz_questions WHERE quiz_id=$quizId");
	
	if (mysql_num_rows($questionQuery) > 0){
		if (!$doneQuiz){
			if ($quizDesc['canDoAfterDue'] == 1 or !$quizOverdue){
				echo '<link rel="stylesheet" type="text/css" href="css/form.css" />';
				echo "<form method='post' id='quiz' action=''>";
				echo "<input type='hidden' name='quizid' value='$quizId'>";
				$incid = 1;
				while (($row = mysql_fetch_assoc($questionQuery))){
				
					$answers = array();
					$answers[0] = $row['1'];
					$answers[1] = $row['2'];
					$answers[2] = $row['3'];
					$answers[3] = $row['4'];
					shuffle($answers);
				
					$question = new Template;
					$question->assign('QUESTION_TEXT', $row['q']);
					$question->assign('QUESTION_ID', $row['id']);
					$question->assign('INCREMENTAL_ID', $incid);
					$question->assign('ANSWER_RANDOM_1', $answers[0]);
					$question->assign('ANSWER_RANDOM_2', $answers[1]);
					$question->assign('ANSWER_RANDOM_3', $answers[2]);
					$question->assign('ANSWER_RANDOM_4', $answers[3]);
					
					$question->assign('ANSWER_1', $answers[0] == "" ? "false" : "true");
					$question->assign('ANSWER_2', $answers[1] == "" ? "false" : "true");
					$question->assign('ANSWER_3', $answers[2] == "" ? "false" : "true");
					$question->assign('ANSWER_4', $answers[3] == "" ? "false" : "true");
					
					$question->assign('HIGHLIGHT_1', "");
					$question->assign('HIGHLIGHT_2', "");
					$question->assign('HIGHLIGHT_3', "");
					$question->assign('HIGHLIGHT_4', "");
					
					$question->render('quiz_question_xchoice');
					$incid = $incid + 1;
				}
				echo "<input type='hidden' name='questioncount' value='".($incid - 1)."'>";
				echo '<input class="input" id="submit" style="width:180px;font-weight:bold;" type="submit" name="submit" value="Submit Quiz"/>';
				echo "</form>";
			}else{
				echo "<span style='font-size:16px;'>This quiz can not be completed after the due date!";
			}
		}else{
			$resultById = array();
			$resultQuery = dbQuery("SELECT * FROM user_quiz_answers WHERE `user_id`={$_SESSION['userid']} AND `quiz_id`=$quizId");
			while (($row = mysql_fetch_assoc($resultQuery))){
				$resultById[$row['question_id']] = $row['answer'];
			}
			
			$incid = 1;
				while (($row = mysql_fetch_assoc($questionQuery))){
					$answers = array();
					$highlight = array();
					
					$answers[0] = $row['1'];
					$answers[1] = $row['2'];
					$answers[2] = $row['3'];
					$answers[3] = $row['4'];
					
					$highlight[0] = "highlightGreen";
					$highlight[1] = "";
					$highlight[2] = "";
					$highlight[3] = "";
					
					if (1 != $resultById[$row['id']]){
						if (2 == $resultById[$row['id']]){
							$highlight[1] = "highlightRed";
						}
						
						if (3 == $resultById[$row['id']]){
							$highlight[2] = "highlightRed";
						}
						
						if (4 == $resultById[$row['id']]){
							$highlight[3] = "highlightRed";
						}
					}
					
				
					$question = new Template;
					$question->assign('QUESTION_TEXT', $row['q']);
					$question->assign('QUESTION_ID', $row['id']);
					$question->assign('INCREMENTAL_ID', $incid);
					$question->assign('ANSWER_RANDOM_1', $answers[0]);
					$question->assign('ANSWER_RANDOM_2', $answers[1]);
					$question->assign('ANSWER_RANDOM_3', $answers[2]);
					$question->assign('ANSWER_RANDOM_4', $answers[3]);
					
					$question->assign('ANSWER_1', $answers[0] == "" ? "false" : "true");
					$question->assign('ANSWER_2', $answers[1] == "" ? "false" : "true");
					$question->assign('ANSWER_3', $answers[2] == "" ? "false" : "true");
					$question->assign('ANSWER_4', $answers[3] == "" ? "false" : "true");
					
					$question->assign('HIGHLIGHT_1', $highlight[0]);
					$question->assign('HIGHLIGHT_2', $highlight[1]);
					$question->assign('HIGHLIGHT_3', $highlight[2]);
					$question->assign('HIGHLIGHT_4', $highlight[3]);
					
					$question->render('quiz_question_xchoice');
					$incid = $incid + 1;
				}
		}
	}else{
		echo "No questions for this quiz!";
	}
	
	?>
	<script>
	
			$('#quiz').submit(function() {
				var val = $("input[type=radio][name^=answer]:checked").length;
				var questions = $("input[name='questioncount']").val();
				if (val < questions){
					alert('You have not answered ' + (questions - val) + ' question(s)!');
					for (var i = 1; i <= questions; i++){
						var answered = $("input[type=radio][name=answer" + i + "]:checked").length;
						if (answered < 1){
							$("#question" + i).addClass('highlightYellow', 2000);//.animate({backgroundColor:"#FFFF00",}, 1000 );
							$(".question" + i).addClass('highlightYellow', 2000);//.animate({backgroundColor:"#FFFF00",}, 1000 );
						}
					}
					return false;
				}
			});
	</script>
	<?
	
	endMainContent();
	footer();
?>