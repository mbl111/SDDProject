<?

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
			
			$("#changebiob").click(function(e){
				var b = $("#bio.textarea").val();
				$("#changebio").html("<span style='color:#990000'>Changing bio... Please wait</span>");
				$.post('ajax/changebio.php', {bio:b}, function(data) {
					if (data=="true"){
						$("#changebio").html("Your bio has been changed.");
					}else{
						$("#changebio").html("Failed to change your bio. ("+ data +") Refresh to try again");
					}
				}).done(function() {})
				.fail(function() { 
					$("#changebio").html("Request time out. Refresh to try again");
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
		});
	</script>
	
		<form id="contentbox">
			<div class="contentboxbody">
				<div id="changename" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					<p style="font-style:italic;font-size:12px;margin-bottom:3px;">You are limited to <b>ONE</b> name change to correct errors. Changes for any other reason will result in account deactivation</p>
					
					<div class="field">
						<label>First Name:</label>
						<input class="input" maxlength="30" type="text" name="firstname" id="firstname" value="<?echo $_SESSION['firstname'];?>"/>
						<span class="hint">A brief one line description of the bug</span>
					</div>
					
					<div class="field">
						<label>Last Name:</label>
						<input class="input" maxlength="30" type="text" name="lastname" id="lastname" value="<?echo $_SESSION['lastname'];?>"/>
						<span class="hint">Last name</span>
					</div>
					
					<input class="input" id='changenameb' style="width:150px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my name!"/>
				</div>
				
				<div id="changebio" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Bio:</label>
						<textarea maxlength="1000" class="textarea" type="text" name="bio" id="bio" value="<? echo $bio; ?>"><? echo $bio; ?></textarea>
						<span class="hint">A small paragraph about yourself. (1000 characters max)</span>
					</div>
					
					<input class="input" id='changebiob' style="width:150px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my bio!"/>
				</div>
				
				<div id="changepass" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					
					<div class="field">
						<label>Password:</label>
						<input maxlength="20" class="input" type="text" name="pass" id="pass" value=""/>
						<span class="hint">Type a secure password (20 characters max.)</span>
					</div>
					
					<input class="input" id='changepassb' style="width:180px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my password!"/>
				</div>
				
			</div>
		</form>


	
<?
	endMainContent();
	footer();
?>