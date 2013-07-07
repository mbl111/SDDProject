<?
	$usesettings = true;
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();

	$errors = array();
	mustBeLoggedin();
	
	$userId = $_SESSION['userid'];
	$pretendingToBeStudent = false;
	if ($_SESSION['usertype']==USER_TEACHER){
		if (isset($_GET['masquerade'])){
			if (studentBelongsTo($_SESSION['userid'], $_GET['masquerade'])){
				$userId = $_GET['masquerade'];
				$pretendingToBeStudent = true;
			}
		}
	}
	
	if ($_SESSION['namechanged'] == 1 && !$pretendingToBeStudent){
		echo "<script>
		$(document).ready(function() {
			$('#changename').hide();
		});
		</script>";
	}
	
	$q = dbQuery("SELECT * FROM user_details WHERE id=$userId");
	$q2 = dbQuery("SELECT * FROM users WHERE id=$userId");
	$bio = "";
	$detail = mysql_fetch_assoc($q);
	$detail = array_merge($detail, mysql_fetch_assoc($q2));
	$bio = $detail['bio'];
	
	?>
	<link rel="stylesheet" type="text/css" href="css/form.css" />
	
	<script>
		$(document).ready(function() {
		
			$("#changepassb").attr("disabled", true);
			$("#changepassb").css("background-color", "#A9A9A9");
			
			
			$("#changepassb").click(function(e){
				var b = $("#pass.input").val();
				$("#changepass").html("<span style='color:#990000'>Changing password... Please wait</span>");
				$.post('ajax/setting/changepass.php', {pass:b}, function(data) {
					if (data=="true"){
						$("#changepass").html("Your password has been changed.");
					}else{
						$("#changepass").html("Failed to change your password. ("+ data +") Refresh to try again");
					}
				}).done(function() {})
				.fail(function() { 
					$("#changepass").html("Request time out. Refresh to try again");
				});
			});
			
			$("#pass.input").keyup(function(e){
				var pass = $("#pass.input").val();
				if (pass.length < 5){
					$("#pass.input").css("border-color", "#FF0000");
					$("#changepassb").attr("disabled", true);
					$("#changepassb").css("background-color", "#A9A9A9");
				}else{
					$("#pass.input").css("border-color", "#00FF00");
					$("#changepassb").removeAttr("disabled");
					$("#changepassb").css("background-color", "#F9F9F9");
				}
			});
			
			$("#changetzb").click(function(e){
				var b = $("#timezone.input").val();
				$("#changetz").html("<span style='color:#990000'>Changing timezone... Please wait</span>");
				$.post('ajax/setting/changetimezone.php', {tz:b}, function(data) {
					if (data=="true"){
						$("#changetz").html("Your timezone has been changed.");
					}else{
						$("#changetz").html("Failed to change your timezone. ("+ data +") Refresh to try again");
					}
				}).done(function() {})
				.fail(function() { 
					$("#changetz").html("Request time out. Refresh to try again");
				});
			});
			
		});
	</script>
	
		<form id="contentbox">
			<p style='margin-bottom:10px;padding:10px;border-bottom: 1px #BDC2BD dashed;font-size:18px;'>You are editing the profile of <b><? echo $detail['firstname']." ".$detail['lastname'];?></b></p>
			<div class="contentboxbody">
				
				<?
				$settingGroup = new SettingGroup("changename", $pretendingToBeStudent ? "Change name" : "Change My Name!");
				
			
				$textSetting = new TextSetting("First Name", "firstname");
				$textSetting->setDefault($detail['firstname']);
				
				$textSetting1 = new TextSetting("Last Name", "lastname");
				
				$textSetting1->setDefault($detail['lastname']);
				
				$hiddenSetting1 = new HiddenField("id", "id", $userId);
				
				$settingGroup->addText("You may only change your name <b>once</b>. Changes for anything other than a correction may result in your account being deactivated");
				$settingGroup->addSetting($textSetting);
				$settingGroup->addSetting($textSetting1);
				$settingGroup->addSetting($hiddenSetting1);
				$settingGroup->render();
				
				$settingGroup = new SettingGroup("changebio", $pretendingToBeStudent ? "Change bio" : "Change My Bio!");
			
				$hiddenSetting2 = new HiddenField("id", "id", $userId);
			
				$textSetting = new TextSetting("Bio", "bio");
				$textSetting->setDefault($bio);
				$textSetting->setType(1);
				$textSetting->setLength(1000);
				
				$settingGroup->addSetting($textSetting);
				$settingGroup->addSetting($hiddenSetting2);
				$settingGroup->render();
				
				?>
				
				<div id="changepass" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Password</label>
						<input maxlength="20" class="input" type="password" name="pass" id="pass" value=""/>
						<span class="hint">Type a secure password (20 characters max.)</span>
					</div>
					
					<input class="input" id='changepassb' style="width:180px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="<?echo $pretendingToBeStudent ? "Change password" : "Change My Password!";?>"/>
				</div>
				
				<div id="changetz" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Timezone</label>
						<?drawTimeZoneDropDown("class='input' id='timezone' name='timezone'", getTimeZone());?>
						<span class="hint">Select a timezone near you</span>
					</div>
					
					<input class="input" id='changetzb' style="width:180px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="<? echo $pretendingToBeStudent ? "Change timezone" : "Change My timezone!"?>"/>
				</div>
				
			</div>
			
			
		</form>


	
<?
	endMainContent();
	footer();
?>