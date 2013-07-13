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
		$classes = trim(rtrim($user['class'], ","), ",");
		if ($classes != ""){
			$classes .= ",";
		}
		$hidden = isAdmin() ? "" : "AND `class` IN ($classes-1) "; 
		$query = dbQuery("SELECT * FROM content WHERE `visible`=1 ".$hidden."ORDER BY `timestamp` DESC LIMIT $offset, $limit");
		$items = dbQuery("SELECT COUNT(*) FROM content WHERE `visible`=1 ".$hidden."ORDER BY `timestamp` DESC");
	}else{
		$query = dbQuery("SELECT * FROM content WHERE `type`='news' AND `class`=-1 AND `visible`=1 ORDER BY `timestamp` DESC LIMIT $offset, $limit");
		$items = dbQuery("SELECT COUNT(*) FROM content WHERE `type`='news' AND `class`=-1 AND `visible`=1 ORDER BY `timestamp` DESC");
	}
	
	$existingItems = mysql_fetch_array($items);
	$existingItems = $existingItems[0];
	
	
	$count = 1;
	while(($contentDetails = mysql_fetch_assoc($query)) and ($count <= $limit + $offset)){
		
		buildContent($contentDetails['nid']);
		$count++;
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