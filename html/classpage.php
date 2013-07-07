<?
	$usesettings = true;
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
		
	$tlinks = "";
	if ($_SESSION['usertype'] == USER_TEACHER && $_SESSION['userid'] == $class['teacher']){
		$tlinks = "<li><a href='classpage.php?id=$id&cpt=4' class='toolboxlink'>Post News</a></li>
			<li><a href='classpage.php?id=$id&cpt=2' class='toolboxlink'>Class Setting</a></li>
			<li><a href='classpage.php?id=$id&cpt=3' class='toolboxlink'>Add Student to class</a></li>";
			
			if (isset($_POST['submitnews'])){
				$title = $_POST['title'];
				$body = $_POST['body'];
				$title = strip_tags(mysql_real_escape_string($title));
				$body = strip_tags(mysql_real_escape_string($body));
				$body = str_replace("\\r\\n", "<br/>", $body);
				$timestamp = time();
				$insert = dbQuery("INSERT INTO content (`title`,`class`,`type`, `timestamp`) VALUES ('$title', $id, 'news', ".$timestamp.")");
				if ($insert){
					$q = dbQuery("SELECT `nid` FROM content WHERE `timestamp`=$timestamp");
					$data = mysql_fetch_assoc($q);
					dbQuery("INSERT INTO content_news (`id`, `body`, `poster`) VALUES ({$data['nid']}, '$body', {$_SESSION['userid']})");
				}else{
					header("Location:message.php?id=6");
				}
				header("Location:classpage.php?id=$id");
			}
	}
	
	addToolBox($class['name'],"<ul class='toolboxlinklist'>
			<li><a href='classpage.php?id=$id' class='toolboxlink'>Overview</a></li>
			<li><a href='classpage.php?id=$id&cpt=1' class='toolboxlink'>Members</a></li>
			$tlinks
		</ul>");
		drawToolBoxes();
		beginMainContent();
	
	if ($classPageType == 0){
		echo "<div id='contentbox'>";
		echo "<p class='contentboxheader' style='font-size:26px;'>Class: {$class['name']}</p>";
		echo "<div class='contentboxbody'>{$class['description']}";
		echo "</div></div><br/><p style='text-align:left;font-size:24px;margin-bottom:4px;'>Latest News</p>";
		
		$news = getNewsForClass($id);
	}elseif ($classPageType == 1){
		echo '
		<script>
			function removeFromGroup(uid){
				$("#info_" + uid).html("Removing. Please wait");
				$.post("ajax/class/removefromclass.php", {id:'.$id.',uid:uid}, function(data) {
				$("#info_" + uid).html(data);
					deleteRow("user_" + uid);
				});
			}
			
			function deleteRow(rowid){   
				var row = document.getElementById(rowid);
				row.parentNode.removeChild(row);
			}
		</script>
		';
		echo '<link rel="stylesheet" type="text/css" href="css/student.css"/>';
		$sort = array("ln", "a");
		if (isset($_GET["srt"])){
			$sort[0] = $_GET["srt"];
		}
		if (isset($_GET['t'])){
			$sort[1] = $_GET['t'];
		}
		$activity = -1;
		if (isset($_GET["ao"])){
			if (is_numeric($_GET["ao"])){
				$activity = $_GET['ao'];
			}
		}
		
			$bonusString = "";
		
			if ($activity == 0){
				$bonusString .= " AND `active`=0";
			}elseif ($activity == 1){
				$bonusString .= " AND `active`=1";
			}
		
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
		
			$query = dbQuery("SELECT * FROM users WHERE `class` LIKE '%$id%' AND `usertype`=1".$bonusString);
			$amt = mysql_num_rows($query);
			echo "<a href='classpage.php?id=$id&cpt=3' class='toolboxlink' style='font-weight:bold;font-size:16px;'>Add students to your class</a>";
			echo "<form method='GET'>
				Sort By
				<select name='srt' id='' class='input' style='width:115px;'>
					<option value='fn' "; if ($sort[0] == 'fn'){echo "selected ";} echo ">First Name</option>
					<option value='ln' "; if ($sort[0] == 'ln'){echo "selected ";} echo ">Last Name</option>
					<option value='jn' "; if ($sort[0] == 'jn'){echo "selected ";} echo ">Joined</option>
					<option value='ls' "; if ($sort[0] == 'ls'){echo "selected ";} echo ">Last seen</option>
				</select>
				Direction
				<select name='t' id='' class='input' style='width:160px;'>
					<option value='a' "; if ($sort[1] == 'a'){echo "selected ";} echo ">Ascending</option>
					<option value='d' "; if ($sort[1] == 'd'){echo "selected ";} echo ">Descending</option>
				</select>
				Show Users
				<select name='ao' id='' class='input' style='width:120px;'>
					<option value='-1' "; if ($activity == -1){echo "selected ";} echo ">All</option>
					<option value='1' "; if ($activity == 1){echo "selected ";} echo ">Activated</option>
					<option value='0' "; if ($activity == 0){echo "selected ";} echo ">Disactivated</option>
				</select>
				<input type='submit' value='Filter' id='loginButton'/>
				</form>";
			
			if ($amt == 0){
				echo "You have no students matching the filters :(";
			}else{
				echo "<div id='table'>
				<table id='contentbox'><tr class='contentboxheader' style='font-size:16px;'><th>Last Name</th><th>First Name</th><th>Joined</th><th>Last Seen</th><th>Options</th></tr>";
				$i = 0;
				while (($row = mysql_fetch_assoc($query)) != false){
					if (in_array($id, explode(",", $row['class']))){
						$col = "#BBFFBB";
						if ($row['active'] == 0){
							$col = "#FFBBBB";
						}
						echo "<tr id='user_{$row['id']}' class='contentboxbody' style='background-color:$col'>
						<td>".$row['lastname']."</td>
						<td>".$row['firstname']."</td>
						<td>".date($dateFormat, getTimeWithZone($row['joined'], $_SESSION['timezone']))."</td>
						<td>".date($dateFormat, getTimeWithZone($row['lastactive'], $_SESSION['timezone']))."</td>
						<td id='info_{$row['id']}'><a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='userpage.php?id={$row['id']}'>View Profile</a>";
						if ($_SESSION['usertype']==USER_TEACHER){
						echo " | <a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='javascript:removeFromGroup({$row['id']})'>Remove</a>";
						}
						echo "</td></tr>";
						$i++;
					}
				}
				echo "</table></div>";
			}
	}elseif ($classPageType == 2){
		if ($_SESSION['usertype']==USER_TEACHER  && $_SESSION['userid'] == $class['teacher']){
			echo '
			<link rel="stylesheet" type="text/css" href="css/form.css" />
			<form id="contentbox">
				<div class="contentboxbody">';
				$groupdesc = new TextSetting("Class Description", "cdesc");
				$groupdesc->setType(1);
				$groupdesc->setLength(800);
				$groupdesc->setDefault($class['description']);
				
				$descSettingGroup = new SettingGroup('changegroupdesc', 'Change Description');
				$descSettingGroup->addSetting($groupdesc);
				$descSettingGroup->setButtonWidth(200);
				$descSettingGroup->render();
			echo '</div></form>';
		}
	}elseif ($classPageType == 3){
		if ($_SESSION['usertype']==USER_TEACHER  && $_SESSION['userid'] == $class['teacher']){
			echo '<script type="text/javascript">
				$(document).ready(function() {
					$("#query.input").keyup(function() {
						var search_term = $(this).val();
						$.post("ajax/studentsearch.php", {search_term:search_term}, function(data) {
							$(".result").html(data);
						});
					});
					
					$("#seluser").click(function() {
						$("#feedback").html("Validating User");
						$(this).attr("disabled", true);
						$(this).css("background-color", "#A9A9A9");
						var inp = $("#query.input").val();
						$.post("ajax/validateuser.php", {name:inp}, function(data) {
							if (data=="true"){
								$.post("ajax/class/putinclass.php", {name:inp,id:'.$id.'}, function(data) {
									if (data=="true"){
										$("#feedback").html(inp + " added to class");
										$("#query.input").val("");
									}else{
										$("#feedback").html(data);
										$("#query.input").val("");
									}
								});
							}else{
								$("#feedback").html("User Not Found");
							}
						});
						
						$(this).removeAttr("disabled");
						$(this).css("background-color", "#F9F9F9");
					});
				});
				
				function autoFill(e){
					$("#query.input").val(e.innerHTML);
					$(".result").html("");
				}
        </script>';
			echo '<link rel="stylesheet" type="text/css" href="css/form.css"/>';
			echo "<div id='contentbox'>";
			echo "<form style='padding:10px 0px;'><field><label>Users Name</label>";
			echo "<input type='text' class='input' id='query' name='query' maxlength='20' autocomplete='off' style='width:200px;'/>";
			echo "</field><input type='button' id='seluser' class='input' style='width:150px; margin: 0px 10px;' value='Add User'/><span id='feedback'></span></form>";
			echo "<div class='dropdown' style='font-family:Verdana;margin-left:110px;margin-top:-10px;position:absolute;'>
                    <ul class='result'></ul>
                </div></div>";
		}
	}elseif ($classPageType == 4){
	//Submit news! :D
		$template = new Template;
		$template->render("submitnews");
	
	}
	
?>
	
<?
	endMainContent();
	footer();
?>