<?
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();
?>
	
<?
	$page_no = 1;
	if (isset($_GET['page'])){
		$page_no = $_GET['page'];
	}
	if ($page_no < 1){
		$page_no = 1;
	}
	$limit = 8;
	$offset = ($page_no - 1) * $limit;
	if (loggedIn()){
		$query = dbQuery("SELECT class FROM users WHERE `id`={$_SESSION['userid']} LIMIT 1");
		$user = mysql_fetch_assoc($query);
		$query = dbQuery("SELECT * FROM content WHERE `class` IN ({$user['class']}, -1)  ORDER BY `timestamp` DESC LIMIT $offset, $limit");
		$items = dbQuery("SELECT COUNT(*) FROM content WHERE `class` IN ({$user['class']}, -1)");
	}else{
		$query = dbQuery("SELECT * FROM content WHERE `type`='news' AND `class`=-1  ORDER BY `timestamp` DESC LIMIT $offset, $limit");
		$items = dbQuery("SELECT COUNT(*) FROM content WHERE `type`='news' AND `class`=-1");
	}
	
	$result  = mysql_fetch_array($items);
	$existingItems  = $result[0];
	
	
	$overallPage = "";
	while(($contentDetails = mysql_fetch_assoc($query))==true){
		$template = new Template;
		$content = getContentSpecifics("content_".$contentDetails['type'], $contentDetails['nid']);
		
		$template->assign("CONTENT_TITLE", $contentDetails['title']);
		$template->assign("CONTENT_ID", $contentDetails['nid']);
		$template->assign("CONTENT_TIME", date($dateFormat, getTimeWithZone($contentDetails['timestamp'], +10)));
		$template->assign("CONTENT_USER", resolveFullnameFromID($content['poster']));
		$template->assign("CONTENT_BODY", $content['body']);
		$template->assign("GLOBAL_STORY", $contentDetails['class'] == -1 ? "true" : "false");
		if ($contentDetails['class'] != -1){
			$template->assign("CLASS_NAME", getClassName($contentDetails['class']));
			$template->assign("CLASS_ID", $contentDetails['class']);
		}
		
		if ($contentDetails['type'] == 'news'){
			$template->assign("CONTENT_EDITED", ($content['lasteditor'] > 0) ? "true" : "false");
			$template->assign("CONTENT_EDITOR", resolveFullnameFromID($content['lasteditor']));
			$template->assign("CONTENT_EDIT_TIME", date($dateFormat, getTimeWithZone($content['edittime'], +10)));
		}elseif ($contentDetails['type'] == 'quiz'){
			$template->assign("QUIZ_OVERDUE", $content['due'] < time() ? 'true' : 'false');
			$template->assign("QUIZ_DUE", date($dateFormat, getTimeWithZone($content['due'], +10)));
			$template->assign("QUIZ_STATUS", userHasDoneQuiz($contentDetails['nid'], $_SESSION['userid']) ? "Quiz Completed" : "Not Complete");
		}
		$template->render($contentDetails['type']);
	}
	
	$adjacents = 2;
	$numPages = ceil($existingItems / $limit);
	$pagination = "";
	if ($numPages <= $adjacents + 1){
		for ($i = 1; $i <= min($adjacents + 1, $numPages); $i++){
			if ($i == $page_no){
				$pagination .= "<span class='pageinationLink'>$i</span>";
			}else{
				$pagination .= "<a href='index.php?page=$i' class='paginationLink'>$i</a>";
			}
		}
	}else{
		//Lower end
		if ($page_no - $adjacents - 1 <= 0){
		for ($i = 1; $i <= min($adjacents + $adjacents + 1, $numPages); $i++){
			if ($i == $page_no){
				$pagination .= "<span class='pageinationLink'>$i</span>";
			}else{
				$pagination .= "<a href='index.php?page=$i' class='paginationLink'>$i</a>";
			}
		}	
		//Upper end
		}elseif ($page_no + $adjacents + 1){
		
		for ($i = $numPages - 4; $i <= $numPages; $i++){
			if ($i == $page_no){
				$pagination .= "<span class='pageinationLink'>$i</span>";
			}else{
				$pagination .= "<a href='index.php?page=$i' class='paginationLink'>$i</a>";
			}
		}
		//In the middle
		}else{
			for ($i = $page_no - $adjacents; $i <= $page_no + $adjacents; $i++){
			if ($i == $page_no){
				$pagination .= "<span class='pageinationLink'>$i</span>";
			}else{
				$pagination .= "<a href='index.php?page=$i' class='paginationLink'>$i</a>";
			}
		}
		}
	}
	echo "<p class='pagination' style='float: right;'>";
	if ($page_no != 1){
		echo "<a href='index.php?page=".($page_no - 1)."' class='paginationLink'><<</a>";
	}
	
	echo $pagination;
	
	if ($page_no < $numPages){
		echo "<a href='index.php?page=".($page_no + 1)."' class='paginationLink'>>></a>";
	}
	echo "</p>";
	endMainContent();
	footer();
?>