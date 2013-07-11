<?
session_start();
include("../../includes/include.php");
include_once("../../includes/dbConnect.php");
mustBeLoggedIn();
if (isset($_POST['comment']) and !empty($_POST['comment']) and isset($_POST['id'])){
    $comment = makeSafe($_POST['comment']);
	$bugId = $_POST['id'];
	$time = time();
	if (dbQuery("INSERT INTO bugcomments (`bug_id`, `comment`, `poster`, `timestamp`) VALUES ($bugId, '$comment', {$_SESSION['userid']}, ".$time.")")){
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
				
			echo "<tr class='issues report mycomment'>";
			echo "<td style='width:200px;'><a class='styledLink' style='font-size:14px;' href='../userpage.php?id={$_SESSION['userid']}'>".myFullName()."</td>";
			echo "<td style='font-size:14px;width:395px;'>$comment</td>";
			echo "<td style='font-size:12px;color:#A0A0A0;width:120px;text-align:right;'>".date($dateFormat, getTimeWithZone($time, $_SESSION['timezone']))."</td>";
			echo "</tr>";
	}else{
		echo "error";
	}
}
?>