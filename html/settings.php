<?
	$usesettings = true;
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();

	$errors = array();
	mustBeLoggedin();
	
	$userId = $_SESSION['userid'];
	$pretendingToBeStudent = false;
	if ($_SESSION['usertype']==USER_TEACHER  or isAdmin()){
		if (isset($_GET['masquerade'])){
			if (studentBelongsTo($_SESSION['userid'], $_GET['masquerade']) or isAdmin()){
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
	<link rel="stylesheet" type="text/css" href="css/settingsform.css" />
	
	<script>
		$(document).ready(function() {
		
			$("#changepassb").attr("disabled", true);
			$("#changepassb").css("background-color", "#A9A9A9");
			
			
			$("#changepassb").click(function(e){
				var b = $("#pass.input").val();
				$("#changepass").html("<span style='color:#990000'>Changing password... Please wait</span>");
				$.post('ajax/setting/changepass.php', {pass:b, id:<?echo $userId;?>}, function(data) {
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
				$.post('ajax/setting/changetimezone.php', {tz:b, id:<?echo $userId;?>}, function(data) {
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
		
		<? if (isAdmin()){ ?>
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
							if (data.split(" ")[0]=="true"){
								document.location.href = document.location.href.split("?")[0] + "?masquerade=" + data.split(" ")[1];
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
		<?}?>
	</script>
	
	<? if (isAdmin()){
			echo '<link rel="stylesheet" type="text/css" href="css/form.css"/>';
			echo "<div id='contentbox'>";
			echo "<form style='padding:10px 0px;'><field><label>Select User to Edit</label>";
			echo "<input type='text' class='input' id='query' name='query' maxlength='20' autocomplete='off' style='width:200px;'/>";
			echo "</field><input type='button' id='seluser' class='input' style='width:200px; margin: 0px 10px;' value='Select'/><span id='feedback'></span></form>";
			echo "<div class='dropdown' style='font-family:Verdana;margin-left:110px;margin-top:-10px;position:absolute;'>
                    <ul class='result'></ul>
                </div></div>";
	}?>
	
		<form id="contentbox">
			<p style='margin-bottom:10px;padding:10px;border-bottom: 1px #BDC2BD dashed;font-size:18px;'>You are editing the profile of <b><? echo $detail['firstname']." ".$detail['lastname'];?></b><br/>
			<span style='font-size:16'>Username: <? echo $detail['username'] ?></span></p>
			<div class="contentboxbody">
				
				<?
				$settingGroup = new SettingGroup("changename", "Save Name Changes");
				
			
				$textSetting = new TextSetting("First Name", "firstname");
				$textSetting->setDefault($detail['firstname']);
				
				$textSetting1 = new TextSetting("Last Name", "lastname");
				
				$textSetting1->setDefault($detail['lastname']);
				
				$hiddenSetting1 = new HiddenField("id", "id", $userId);
				
				if (!$pretendingToBeStudent){
					$settingGroup->addText("You may only change your name <b>once</b>. Changes for anything other than a correction may result in your account being deactivated");
				}
				$settingGroup->addSetting($textSetting);
				$settingGroup->addSetting($textSetting1);
				$settingGroup->addSetting($hiddenSetting1);
				$settingGroup->render();
				
				$settingGroup = new SettingGroup("changebio", "Save Bio Changes");
			
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
					<div class="field">
						<label>Confirm Password</label>
						<input maxlength="20" class="input" type="password" name="pass" id="pass" value=""/>
						<span class="hint">Type your password again (20 characters max.)</span>
					</div>
					<input class="input" name="id" type="hidden" value="<?echo $_SESSION['userid'];?>"/>
					<input class="input" id='changepassb' style="width:180px;font-weight:bold;margin-left:160px;" type="button" name="submit" value="Change Password"/>
				</div>
				
				<div id="changetz" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Timezone</label>
						<?drawTimeZoneDropDown("class='input' id='timezone' name='timezone'", getTimeZone());?>
						<span class="hint">Select a timezone near you</span>
					</div>
					<input class="input" name="id" type="hidden" value="<?echo $_SESSION['userid'];?>"/>
					<input class="input" id='changetzb' style="width:180px;font-weight:bold;margin-left:160px;" type="button" name="submit" value="Save Timezone"/>
				</div>
				
			</div>
			
			
		</form>


	
<?
	endMainContent();
	footer();
?>