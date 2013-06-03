<?
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();
?>
	<link rel="stylesheet" type="text/css" href="css/student.css"/>
	<script>
	
		function activateStudent(id){
			$("#info_" + id).html("Please wait...");
			doActivate(id);
		}
		
		function doActivate(id){
			$.post('ajax/activateuser.php', {id:id}, function(data) {
				if (data=="true"){
					$("#user_" + id).css("background-color", "#BBFFBB");
					$("#info_" + id).html("<a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='userpage.php?id=" + id + "'>View Profile</a> | <a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='javascript:deactivateStudent(" + id + ")'>Deactivate</a>");
				}
			}).done(function() {})
			.fail(function() { 
				$("#isactive").html("Request time out");
			});
		}
		
		function deactivateStudent(id){
			$("#info_" + id).html("Please wait...");
			dodeactivate(id);
		}
		
		function dodeactivate(id){
			$.post('ajax/deactivateuser.php', {id:id}, function(data) {
				if (data=="true"){
					$("#user_" + id).css("background-color", "#FFBBBB");
					$("#info_" + id).html("<a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='userpage.php?id=" + id + "'>View Profile</a> | <a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='javascript:activateStudent(" + id + ")'>Activate</a>");
				}
			}).done(function() {})
			.fail(function() { 
				$("#isactive").html("Request time out");
			});
		}
	</script>
<?
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
		
		echo "<form method='GET'>
			Sort By
			<select name='srt' id='''>
				<option value='fn' "; if ($sort[0] == 'fn'){echo "selected ";} echo ">First Name</option>
				<option value='ln' "; if ($sort[0] == 'ln'){echo "selected ";} echo ">Last Name</option>
				<option value='jn' "; if ($sort[0] == 'jn'){echo "selected ";} echo ">Joined</option>
				<option value='ls' "; if ($sort[0] == 'ls'){echo "selected ";} echo ">Last seen</option>
			</select>
			Direction
			<select name='t' id=''>
				<option value='a' "; if ($sort[1] == 'a'){echo "selected ";} echo ">Ascending</option>
				<option value='d' "; if ($sort[1] == 'd'){echo "selected ";} echo ">Descending</option>
			</select>
			Show Users
			<select name='ao' id=''>
				<option value='-1' "; if ($activity == -1){echo "selected ";} echo ">All</option>
				<option value='1' "; if ($activity == 1){echo "selected ";} echo ">Activated</option>
				<option value='0' "; if ($activity == 0){echo "selected ";} echo ">Disactivated</option>
			</select>
			<input type='submit' value='Filter' id='loginButton'/>
			</form>";
		
		if (mysql_num_rows($query) == 0){
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
				<td id='info_{$row['id']}'><a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='userpage.php?id={$row['id']}'>View Profile</a> | ";
				if ($row['active'] == 0){
					echo "<a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='javascript:activateStudent({$row['id']})'>Activate</a>";
				}else{
					echo "<a class='toolboxlink' style='font-weight:bold;font-size:12px;' href='javascript:deactivateStudent({$row['id']})'>Deactivate</a>";
				}
				
				echo "</td></tr>";
			}
			echo "</table></div>";
		}
	}else{
		echo "<p class='error'>You are not a teacher!</p>";
	}
	endMainContent();
	footer();
?>