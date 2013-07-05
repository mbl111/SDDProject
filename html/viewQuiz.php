<?
	include("includes/header.php");
	
	$quizId = -1;
	if (isset($_GET['id'])){
		$quizId = $_GET['id'];
	}else{
		header("Location:messages.php?id=5");
	}
	
	$query = dbQuery("SELECT * FROM content WHERE id=$quizId");
	if (!$query){
		header("Location:messages.php?id=5");
	}else{
		$quizContent = mysql_fetch_assoc($query);
		if ($quizContent['type'] != 'quiz'){
			header("Location:messages.php?id=5");
		}else{
			
			$doneQuiz = userDoneQuiz($quizId, $_SESSION['userid']))
			
			$quizDescQ = dbQuery("SELECT * FROM content_quiz WHERE id=$quizId");
			$quizDesc = mysql_fetch_assoc($quizDescQ);
			
			$quizOverdue = $quizDesc['due'] < time();
			
			
			
		}
	}
	
	drawToolBoxes();
	beginMainContent();
?>
	
<?
	$descTemplate = new Template;
	$descTemplate->assign("CONTENT_ID", );
	$descTemplate->assign("CONTENT_TITLE", );
	$descTemplate->assign("CONTENT_BODY", );
	$descTemplate->assign("CONTENT_USER", );
	$descTemplate->assign("CONTENT_TIME", );
	$descTemplate->assign("QUIZ_STATUS", $doneQuiz ? "Quiz Completed" : "Not Complete");
	endMainContent();
	footer();
?>