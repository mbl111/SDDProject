<?
	include("includes/header.php");
	
	if (loggedIn() and $_SESSION['usertype'] == USER_TEACHER){

	echo '<script>
	
		function activateStudent(id){
			$("#isactive").html("Please wait...");
			doActivate(id);
		}
		
		function doActivate(id){
			$.post("ajax/activateuser.php", {id:id}, function(data) {
				if (data=="true"){
					$("#isactive").html("User has been activated.");
					$(".studentactivate").html("<a href=\'javascript:deactivateStudent(" + id + ");\' class=\'toolboxlink\'>Deactivate Student</a>");
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
			$.post("ajax/deactivateuser.php", {id:id}, function(data) {
				if (data=="true"){
					$("#isactive").html("User has been deactivated.");
					$(".studentactivate").html("<a href=\'javascript:activateStudent(" + id + ");\' class=\'toolboxlink\'>Activate Student</a>");
				}else{
					$("#isactive").html("Failed to activate user");
				}
			}).done(function() {})
			.fail(function() { 
				$("#isactive").html("Request time out");
			});
		}
	</script>';
	}?>
	<link rel="stylesheet" type="text/css" href="css/userpage.css" />
<?
	$id = -1;
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
	
	if ($id > 0){
		$columns = "firstname, lastname, usertype, joined, lastactive";
		if (loggedIn() and $_SESSION['usertype'] == USER_TEACHER){
			$columns .= ", teacher, class, active";
		}elseif (loggedIn() and $id == $_SESSION['userid']){
			$columns .= ", class, teacher";
		}
		$query = dbQuery("SELECT $columns from users WHERE id=$id");
		
		if (mysql_num_rows($query) == 0){
				drawToolBoxes();
				beginMainContent();
			echo "<p class='error'>This user could not be found</p>";
		}else{
			$user = mysql_fetch_assoc($query);
			
			$bio = dbQuery("SELECT bio from user_details WHERE id=$id");
			$userbio = mysql_fetch_assoc($bio);
			$typestring = "null";
			if ($user['usertype']==0){
				$typestring = "Teacher";
			}else{
				$typestring = "Student";
			}
			$classes = array();
			if (isset($user['class']) and $user['class']!=""){
				$classes = explode(",", $user['class']);
			}
			
			
			$bonuslink = "";
			if (loggedIn() and $_SESSION['usertype']==USER_TEACHER and $_SESSION['userid'] == $user['teacher']){
				if ($user['active']==0){
					$bonuslink = "<li class='studentactivate'><a href='javascript:activateStudent($id)' class='toolboxlink'>Activate Student</a></li>";
				}else{
					$bonuslink = "<li class='studentactivate'><a href='javascript:deactivateStudent($id)' class='toolboxlink'>Deactivate Student</a></li>";
				}
			}
			
			if (loggedIn() and  $id != $_SESSION['userid']){
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
			<p style='padding-bottom:3px;text-align:left;'><span class='text'>{$user['firstname']} {$user['lastname']}</span> <span class='label'>(".$typestring.")</span>";
			if (loggedIn() and $_SESSION['usertype'] == USER_TEACHER and $user['active'] == 0){
				echo "  <span id='isactive' style='font-style:italic;font-size:14px;color:#990000'>This user is inactive (<a href='javascript:activateStudent($id)' class='toolboxlink'>Activate Student</a>)</span>";
			}else{
				echo "  <span id='isactive' style='font-style:italic;font-size:14px;color:#990000'></span>";
			}
			echo "</p><div id='contentbox'>
			<div class='contentboxheader'>User Statistics</div>
			<table class='contentboxbody'>
				<tr><td class='label' style='width:90px;text-align:right;padding-right:3px;'>Joined:</td><td class='text'>".date($dateFormat, getTimeWithZone($user['joined'], getTimezone()))."</td></tr>
				<tr><td class='label' style='width:90px;text-align:right;padding-right:3px;'>Last Seen:</td><td class='text'>".date($dateFormat, getTimeWithZone($user['lastactive'], getTimezone()))."</td></tr>
				<tr><td class='label' style='width:90px;text-align:right;padding-right:3px;'>Bio:</td><td class='text'>"; if (mysql_num_rows($bio) == 1){echo $userbio['bio'];}echo "</td></tr>
			</table>
			</div>";
			if (loggedIn() and $_SESSION['usertype'] == USER_TEACHER or loggedIn() and $_SESSION['userid'] == $id){
			echo "<div id='contentbox'>
				<div class='contentboxheader'>Classes</div>
				<ul class='contentboxbody' style='list-style-type: circle;list-style-position:inside;margin-left: 10px;line-height: 18px;'>";
				if (!empty($classes)){
					foreach ($classes as $class){
						echo "<li><span class='text'><a href='classpage.php?id=$class' class='toolboxlink' style='font-weight:bold;text-decoration:underline;'>".getClassName($class)."</a></span></li>";
					}
				}else{
					echo "{$user['firstname']} {$user['lastname']} is not in any classes";
				}
				echo "
				</ul>
				</div>";
			}
		}
		
	}else{
		drawToolBoxes();
		beginMainContent();
		echo "<p class='error'>No user defined</p>";
	}
	
	endMainContent();
	footer();
?>