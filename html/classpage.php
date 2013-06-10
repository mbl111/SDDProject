<?
	$id = 0;
	$classPageType = 0;
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
	if (isset($_GET['cpt'])){
		$classPageType = $_GET['cpt'];
	}
	if ($id == 0) {
		header("Location:message.php?id=3");
	}
	
	include("includes/header.php");
	
	$query = dbQuery("SELECT * FROM classes WHERE id=$id LIMIT 1");
	if (mysql_num_rows($query) == 0){
		header("Location:message.php?id=3");
	}
	$class = mysql_fetch_assoc($query);
	
	if ($classPageType == 0){
		addToolBox($class['name']);
		drawToolBoxes();
		beginMainContent();
		echo "<div id='contentbox'>";
		echo "<p class='contentboxheader' style='font-size:26px;'>Class: {$class['name']}</p>";
		echo "<div class='contentboxbody'>{$class['description']}";
		echo "</div></div><br/><p style='text-align:left;font-size:24px;margin-bottom:4px;'>Latest News</p>";
		
		$news = getNewsForClass($id);
		foreach ($news as $story){
			echo $story;
		}
	}elseif ($classPageType == 1){
		$members = generateMemberList($class['students']);
	}
	
?>
	
<?
	endMainContent();
	footer();
?>