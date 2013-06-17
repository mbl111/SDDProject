<?
	$usesettings = true;
	include("includes/header.php");
	drawToolBoxes();
	beginMainContent();

	$errors = array();
	mustBeLoggedin();
	if ($_SESSION['namechanged'] == 1){
		echo "<script>
		$(document).ready(function() {
			$('#changename').hide();
		});
		</script>";
	}
	
	$q = dbQuery("SELECT * FROM user_details WHERE id={$_SESSION['userid']}");
	$bio = "";
	$detail = mysql_fetch_assoc($q);
	$bio = $detail['bio'];
	
	?>
	<link rel="stylesheet" type="text/css" href="css/form.css" />
	
	<script>
		$(document).ready(function() {
		
			$("#changepassb").attr("disabled", true);
			$("#changepassb").css("background-color", "#A9A9A9");
		
			$("#changenameb").click(function(e){
				var fn = $("#firstname.input").val();
				var ln = $("#lastname.input").val();
				$("#changename").html("<span style='color:#990000'>Changing name... Please wait</span>");
				$.post('ajax/changename.php', {firstname:fn, lastname:ln}, function(data) {
					if (data=="true"){
						$("#changename").html("Your name has been changed.");
					}else{
						$("#changename").html("Failed to change your name. ("+ data +") Refresh to try again");
					}
				}).done(function() {})
				.fail(function() { 
					$("#changename").html("Request time out. Refresh to try again");
				});
			});
			
			
			$("#changepassb").click(function(e){
				var b = $("#pass.input").val();
				$("#changepass").html("<span style='color:#990000'>Changing password... Please wait</span>");
				$.post('ajax/changepass.php', {pass:b}, function(data) {
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
				$.post('ajax/changetimezone.php', {tz:b}, function(data) {
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
			<div class="contentboxbody">
				<div id="changename" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					<p style="font-style:italic;font-size:12px;margin-bottom:3px;">You are limited to <b>ONE</b> name change to correct errors. Changes for any other reason will result in account deactivation</p>
					
					<div class="field">
						<label>First Name:</label>
						<input class="input" maxlength="30" type="text" name="firstname" id="firstname" value="<?echo $_SESSION['firstname'];?>"/>
					</div>
					
					<div class="field">
						<label>Last Name:</label>
						<input class="input" maxlength="30" type="text" name="lastname" id="lastname" value="<?echo $_SESSION['lastname'];?>"/>
					</div>
					
					<input class="input" id='changenameb' style="width:150px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my name!"/>
				</div>
				
				
				<div id="changepass" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Password:</label>
						<input maxlength="20" class="input" type="password" name="pass" id="pass" value=""/>
						<span class="hint">Type a secure password (20 characters max.)</span>
					</div>
					
					<input class="input" id='changepassb' style="width:180px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my password!"/>
				</div>
				
				<div id="changetz" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Timezone:</label>
						<?drawTimeZoneDropDown("class='input' id='timezone' name='timezone'", getTimeZone());?>
						<span class="hint">Select a timezone near you</span>
					</div>
					
					<input class="input" id='changetzb' style="width:180px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my timezone!"/>
				</div>
				
			</div>
			
			<?
				$settingGroup = new SettingGroup("changebio", "Touch the Butt");
			
				$textSetting = new TextSetting("Poopie", "bio");
				$textSetting->setDefault("goats");
				
				$settingGroup->addSetting($textSetting);
				$settingGroup->render();
				
			?>
			
		</form>


	
<?
	endMainContent();
	footer();
?>