<?
	include("includes/header.php");
?>
	<script>
	
		function activateStudent(id){
			$("#isactive").html("Please wait...");
			doActivate(id);
		}
		
		function doActivate(id){
			$.post('ajax/activateuser.php', {id:id}, function(data) {
				if (data=="true"){
					$("#isactive").html("User has been activated. Refresh to see changes");
				}else{
					$("#isactive").html("Failed to activate user");
				}
			}).done(function() {})
			.fail(function() { 
				$("#isactive").html("Request time out");
			});
		}
		
		function deactivateStudent(id){
			$("#isactive").html("Please wait...");
			dodeactivate(id);
		}
		
		function dodeactivate(id){
			$.post('ajax/deactivateuser.php', {id:id}, function(data) {
				if (data=="true"){
					$("#isactive").html("User has been deactivated. Refresh to see changes");
				}else{
					$("#isactive").html("Failed to activate user");
				}
			}).done(function() {})
			.fail(function() { 
				$("#isactive").html("Request time out");
			});
		}
	</script>
	<link rel="stylesheet" type="text/css" href="css/userpage.css" />
<?
	$id = -1;
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
	
	if ($id > 0){
		$columns = "firstname, lastname, usertype, joined, lastactive";
		if ($_SESSION['usertype'] == USER_TEACHER){
			$columns .= ", teacher, class, active";
		}elseif ($id == $_SESSION['userid']){
			$columns .= ", class, teacher";
		}
		$query = dbQuery("SELECT $columns from users WHERE id=$id");
		
		if (mysql_num_rows($query) == 0){
				drawToolBoxes();
				beginMainContent();
			echo "<p class='error'>This user could not be found</p>";
		}else{
			$user = mysql_fetch_assoc($query);
			$typestring = "null";
			if ($user['usertype']==0){
				$typestring = "Teacher";
			}else{
				$typestring = "Student";
			}
			$classes = array();
			if (isset($user['class']) and $user['class']!=""){
				$classes = explode(",", $user['classes']);
			}
			
			
			$bonuslink = "";
			if ($_SESSION['usertype']==USER_TEACHER and $_SESSION['userid'] == $user['teacher']){
				if ($user['active']==0){
					$bonuslink = "<li><a href='javascript:activateStudent($id)' class='toolboxlink'>Activate Student</a></li>";
				}else{
					$bonuslink = "<li><a href='javascript:deactivateStudent($id)' class='toolboxlink'>Deactivate Student</a></li>";
				}
			}
			
			if ($id != $_SESSION['userid']){
				addToolBox($user['firstname']." ".$user['lastname'], 
				"<ul class='toolboxlinklist'>
				<li><a href='' class='toolboxlink'>Message User</a></li>
				$bonuslink
				</ul>"
				);
			}
			drawToolBoxes();
			beginMainContent();
			
			echo "
			<p style='padding-bottom:3px;text-align:left;'><span class='text''>{$user['firstname']} {$user['lastname']}</span> <span class='label'>(".$typestring.")</span>";
			if ($_SESSION['usertype'] == USER_TEACHER and $user['active'] == 0){ echo " - <span id='isactive' style='font-style:italic;font-size:14px;color:#990000'>This user is inactive (<a href='javascript:activateStudent($id)' class='toolboxlink'>Activate Student</a>)</span>";}
			echo "</p><div id='contentbox'>
			<div class='contentboxheader'>User Statistics</div>
			<div class='contentboxbody'>
			<p><span class='label'>Joined: </span><span class='text'>".date($dateFormat, getTimeWithZone($user['joined'], $_SESSION['timezone']))."</span></p>
			<p><span class='label'>Last Seen: </span><span class='text'>".date($dateFormat, getTimeWithZone($user['lastactive'], $_SESSION['timezone']))."</span></p>
			</div>
			</div>";
			if ($_SESSION['usertype'] == USER_TEACHER or $_SESSION['userid'] == $id){
			echo "<div id='contentbox'>
				<div class='contentboxheader'>Classes</div>
				<div class='contentboxbody'>";
				if (!empty($classes)){
					foreach ($classes as $class){
						echo "<p><span class='label'>$class</span></p>";
					}
				}else{
					echo "{$user['firstname']} {$user['lastname']} is not in any classes";
				}
				echo "
				</div>
				</div>";
			}
		}
		
	}else{
		echo "<p class='error'>This user could not be found</p>";
	}
	
	endMainContent();
	footer();
?>