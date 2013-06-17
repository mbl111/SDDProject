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
	if ($_SESSION['usertype'] == USER_TEACHER){
		$tlinks = "<li><a href='classpage.php?id=$id&cpt=2' class='toolboxlink'>Class Setting</a></li>
			<li><a href='classpage.php?id=$id&cpt=3' class='toolboxlink'>Add Student to class</a></li>";
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
		foreach ($news as $story){
			echo $story;
		}
	}elseif ($classPageType == 1){
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
		if ($_SESSION['usertype']==USER_TEACHER){
		
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
		
			$query = dbQuery("SELECT * FROM users WHERE `teacher`={$_SESSION['userid']}".$bonusString);
			$amt = mysql_num_rows($query);
			echo "You have <b>$amt</b> students in your class- <a href='classpage.php?id=$id&cpt=3' class='toolboxlink' style='font-weight:bold;font-size:16px;'>Add students to your class</a>";
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
				
				while (($row = mysql_fetch_assoc($query)) != false){
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
				}
				echo "</table></div>";
			}
			
		}else{
			echo "<p class='error'>You are not a teacher!</p>";
		}
	}elseif ($classPageType == 2){
		echo '<form id="contentbox">
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
	}elseif ($classPageType == 3){
		if ($_SESSION['usertype']==USER_TEACHER){
			echo '<script type="text/javascript">
				$(document).ready(function() {
            $("#query.input").keyup(function() {
                var search_term = $(this).val();
                $.post("ajax/studentsearch.php", {search_term:search_term}, function(data) {
                    $(".result").html(data);
                });
            });
        });
        </script>';
			echo '<link rel="stylesheet" type="text/css" href="css/form.css"/>';
			echo "<div id='contentbox'>";
			echo "<form style='padding:10px 0px;'><field><label>User Search</label>";
			echo "<input type='text' class='input' id='query' name='query' maxlength='20' autocomplete='off' style='width:200px;'/>";
			echo "</field></form>";
			echo "<div class='dropdown' style='font-family:Verdana;margin-left:110px;margin-top:-10px;position:absolute;'>
                    <ul class='result'></ul>
                </div></div>";
		}
	}
	
?>
	
<?
	endMainContent();
	footer();
?>