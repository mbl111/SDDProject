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
	
	?>
	<link rel="stylesheet" type="text/css" href="css/form.css" />
	
	<script>
		$(document).ready(function() {
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
		});
	</script>
	
		<form id="contentbox">
			<div class="contentboxbody">
				<div id="changename" style="margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;">
					<p style="font-style:italic;font-size:12px;margin-bottom:3px;">You are limited to <b>ONE</b> name change to correct errors. Changes for any other reason will result in account deactivation</p>
					
					<div class="field">
						<label>First Name:</label>
						<input class="input" type="text" name="firstname" id="firstname" value="<?echo $_SESSION['firstname'];?>"/>
						<span class="hint">A brief one line description of the bug</span>
					</div>
					
					<div class="field">
						<label>Last Name:</label>
						<input class="input" type="text" name="lastname" id="lastname" value="<?echo $_SESSION['lastname'];?>"/>
						<span class="hint">Last name</span>
					</div>
					
					<input class="input" id='changenameb' style="width:150px;font-weight:bold;margin-left:110px;" type="button" name="submit" value="Change my name!"/>
				</div>
				
				<div class="field">
					<label>Description:</label>
					<textarea class="textarea" type="text" name="desc" id="desc" value="<?if (isset($_POST['desc'])){echo $_POST["desc"];}?>"></textarea>
					<span class='hint'>Detailed description of the bug or error</span>
				</div>
				
				<div class="field">
					<label>Steps to reproduce:</label>
					<textarea class="textarea" type="text" name="reproduce" id="reproduce" value="<?if (isset($_POST['reproduce'])){echo $_POST['reproduce'];}?>"></textarea>
					<span class='hint'>How can the bug or error be fixed?</span>
				</div>
				
				<div class="field">
						<label>Done?</label>
						<input class="input" style="width:412px;font-weight:bold;" type="submit" name="submit" value="Submit Bug!"/>
				</div>
				
			</div>
		</form>


	
<?
	endMainContent();
	footer();
?>