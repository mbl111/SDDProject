<?
	include("../includes/header.php");
	include("includes/buginclude.php");
	drawToolBoxes();
	beginMainContent();
	$id = -1;
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		header("Location:index.php");
	}
	
	$query = dbQuery("SELECT * FROM bugreports WHERE `id`=$id LIMIT 1");
	if (!$query){
		header("Location:index.php");
	}
	
	$querycomment = dbQuery("SELECT `id` FROM bugcomments WHERE `bug_id`=$id");
	$querydupedby = dbQuery("SELECT `id`, `title` FROM bugreports WHERE `dupeof`=$id");
	$bug = mysql_fetch_assoc($query);
	$hasComments = mysql_num_rows($querycomment) > 0;
	$hasDupes = mysql_num_rows($querydupedby) > 0;
	
	$status = getStatus($bug['status']);
	if ($bug['status'] == 3){
		$status .= " of <a class='styledLink' style='font-size:16px;' href='bug.php?id={$bug['dupeof']}'>#B{$bug['dupeof']}</a>";
	}
	
	$duped = "";
	if ($hasDupes){
		$dupes = array();
		$count = 0;
		while (($row = mysql_fetch_assoc($querydupedby)) and $count < 10){
			$dupes[] .= "<li class='dupe'><a href='bug?id={$row['id']}' class='styledLink'>#B{$row['id']} - {$row['title']}</a></li>";
			$count++;
		}
		$duped = implode(",", $dupes);
		$numDupes =  mysql_num_rows($querydupedby);
		if (($numDupes - $count) > 0){
			$duped .= "<li class='dupe'>and ".($numDupes - $count)." more.</li>";
		}
	}else{
		$duped = "No duplicates found";
	}
	
	$comments = "";
	if (!$hasComments){
		$comments = "<div class='contentboxbody'>No comments found. Why not be the first to post.</div>";
	}else{
		include("includes/loadcomments.php");
	}
	
?>
	
	<script>

		var currentChunk = -1;

		function loadComments(){
			currentChunk++;
			var id = $("#bugid").val();
			$.post("ajax/loadcomments.php", {chunk:currentChunk, id:id}, function(data) {
				if (data != "error"){
					$("#loadmore").html("");
					var oldsec = $("#comments").html();
					$("#comments").html(data + oldsec);
				}else{
					var oldsec = $("#comments").html();
					$("#comments").html("<tr id='error'><td></td><td>Failed to load comments</td><td></td></tr>" + oldsec);
					currentChunk--;
				}
			});
		}

		$(document).ready(function() {
			$("#postcomment").click(function(e) {
				var comment = $("#commentbox").val();
				var id = $("#bugid").val();
				$.post("ajax/postcomment.php", {comment:comment, id:id}, function(data) {
					if (data != "error"){
						var old = $("#comments").html();
						$("#comments").html(old + data);
						$("#commentbox").val("");
					}else{
						$("#error").show(0);
						$("#error").html("Failed to post comment");
						$("#error").fadeOut(10000);
					}
				});
			});
		});

	</script>
	
	<div id="contentbox"><table class='bug'>
		<tr>
			<td class='bug label'>Key</td><td class='bug value'><a class='styledLink' style='font-size:16px;' href='bug.php?id=<?{echo $bug['id'];}?>'>#B<?echo $bug['id']; ?></a></td>
			<td class='bug label'>Status</td><td class='bug value'><?echo $status;?></td>
		</tr>
		<tr>
			<td class='bug label'>Report By</td><td class='bug value'><a class='styledLink' style='font-size:16px;' href='userpage.php?id=<?{echo $bug['user'];}?>'><?echo resolveFullnameFromID($bug['user']); ?></a></td>
			<td class='bug label'>Time</td><td class='bug value'><?echo date($dateFormat, getTimeWithZone($bug['time'], $_SESSION['timezone']));?></td>
		</tr>
	</table>
	<table>
		<tr>
			<td class='bug label'>Title</td><td class='bug extvalue'><?echo $bug['title'];?></td>
		</tr>
		<tr>
			<td class='bug label'>Description</td><td class='bug extvalue'><?echo $bug['desc'];?></td>
		</tr>
		<tr>
			<td class='bug label'>Steps to recreate</td><td class='bug extvalue'><?echo $bug['steps'];?></td>
		</tr>
		<tr>
			<td class='bug label'>Duped By</td><td class='bug extvalue'><ul><?echo $duped;?></ul></td>
		</tr>
	</table>
	</div>
	
	<div id="contentbox">
		<table><tbody  id='comments'>
			<?echo $comments;?>
		</tbody></table>
		<div class='contentboxbody'>
			<link rel="stylesheet" type="text/css" href="css/login.css" />
			<input type='text' class='input' id='commentbox' style='width:400px;' maxlength='200'/>
			<input type='hidden' class='input' id='bugid' value='<?echo $id;?>'/>
			<input type='button' class='input' name='postcomment' id='postcomment' value='Post Comment' style='width:160px;' onclick='javascript:postComment()'/>
			<span id='error'></span>
		</div>
	</div>
	
<?	
	endMainContent();
	footer();
?>