<?
	include("../includes/header.php");
	include("includes/buginclude.php");
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
	
	$query = dbQuery("SELECT * FROM bugreports ORDER BY `time` DESC LIMIT $offset, $limit");
	if ($query){
		echo "<table><tr class='issues'><th style='width:120px;'>Status</th><th style='width:80px;'>Key</th><th style='width:400px;'>Name</th><th style='width:30px;'>Comments</th></tr>";
		$second = false;
		while (($row = mysql_fetch_assoc($query))){
			$commentQ = dbQuery("SELECT `id` FROM bugcomments WHERE `bug_id`={$row['id']}");
			$comments = mysql_num_rows($commentQ);
			$status = getStatus($row['status']);
			if ($row['status'] == 3){
				$status .= " of <a class='styledLink' href='bug.php?id={$row['dupeof']}'>#B{$row['dupeof']}</a>";
			}
		
			echo "<tr class='issues report".($second ? " second" : "")."'>";
				echo "<td class='".strtolower(getStatus($row['status']))."'>".$status."</td>";
				echo "<td><a class='styledLink' style='font-size:12px;' href='bug.php?id={$row['id']}'>#B{$row['id']}</a></td>";
				echo "<td><a class='styledLink' style='font-size:12px;' href='bug.php?id={$row['id']}'>{$row['title']}</a></td>";
				echo "<td>$comments</td>";
			echo "</tr>";
			$second = !$second;
		}
		echo "</table>";
	}
	$query = dbQuery("SELECT COUNT(*) FROM bugreports");
	$items = mysql_fetch_array($query);
	$existingItems = $items[0];
	
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