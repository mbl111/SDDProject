<?
$limit = 10;
session_start();
if (isset($_POST['chunk']) and isset($_POST['id'])){
	include("../../includes/include.php");
	include_once("../../includes/dbConnect.php");
	$chunk = $_POST['chunk'];
	$id = $_POST['id'];
	$offs = $chunk * $limit;
	$query = dbQuery("SELECT `id` FROM bugcomments WHERE `bug_id`=$id");
	$totalcomments = mysql_num_rows($query);
	
	$query = dbQuery("SELECT * FROM bugcomments WHERE `bug_id`=$id AND `visible`=1 ORDER BY `timestamp` DESC LIMIT $offs, $limit");
	if ($query){
		if ($totalcomments > $offs + $limit){
			$end = $totalcomments - ($offs + $limit);
			$start = $end - $limit;
			if ($start < 1){
				$start = 1;
			}
			echo "<tr id='loadmore' class='issues report second' style='font-size:12px;'><td></td><td><a class='styledLink' style='font-size:12px;' href='javascript:loadComments();'>Load more comments.</a></td><td style='font-size:12px;color:#A0A0A0;width:120px;text-align:right;'>($start to $end of $totalcomments)</td></tr>";
		}
		
		$second = false;
		$rows = array();
		while (($row = mysql_fetch_assoc($query))){
			$rows[] = array(
				'id' => $row['id'],
				'bug_id' => $row['bug_id'],
				'poster' => $row['poster'],
				'timestamp' => $row['timestamp'],
				'comment' => $row['comment']
			);
		}
		
		$rows = array_reverse($rows);
		
		foreach ($rows as $row){
			$comment = $row['comment'];
			$matches = array();
			preg_match_all("/#B[0-9]+/i", $comment, $matches);
			
			foreach ($matches as $match){
				if (!empty($match)){
					$match = $match[0];
					$matchUpper = strtoupper($match);
					$rawid = str_replace("#B", "", $matchUpper);
					$comment = str_replace($match, "<a class='styledLink' href='bug.php?id=$rawid'>$matchUpper</a>", $comment);
					}
			}
				
			echo "<tr class='issues report".($second ? " second" : "")."'>";
			echo "<td style='width:200px;'><a class='styledLink' style='font-size:14px;' href='../userpage.php?id={$row['poster']}'>".resolveFullnameFromID($row['poster'])."</td>";
			echo "<td style='font-size:14px;width:395px;'>$comment</td>";
			echo "<td style='font-size:12px;color:#A0A0A0;width:120px;text-align:right;'>".date($dateFormat, getTimeWithZone($row['timestamp'], $_SESSION['timezone']))."</td>";
			echo "</tr>";
			$second = !$second;
		}
	}else{
		echo "error";
	}
}else{
	echo "error";
}
?>