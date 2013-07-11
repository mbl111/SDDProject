<?
	include("includes/header.php");
	if (isset($_POST['submitquiz'])){
		if (isset($_POST['class']) and isset($_POST['questioncount'])){
			$class = $_POST['class'];//
			$questionCount = $_POST['questioncount'];//
			$title = makeSafe($_POST['title']);//
			$body = makeSafe($_POST['description']);//
			$dueDate = $_POST['date'];
			$dueHour = $_POST['hour'];
			$dueMin = $_POST['min'];
			$dueSecond = $_POST['sec'];
			$unixdate = strtotime($dueDate) + ($timeConstant['hour'] * $dueHour) + ($timeConstant['min'] * $dueMin) + ($timeConstant['sec'] * $dueSecond) + (($timeConstant['hour'] * 2) + ($timeConstant['hour'] * $_SESSION['timezone']));
			$afterdue = 0;
			if (isset($_POST['afterdue'])){
				$afterdue = 1;
			}
			$timestamp = time();
			$query = dbQuery("INSERT INTO content (`type`, `title`, `timestamp`, `class`) VALUES ('quiz', '$title', '$timestamp', $class)");
			if ($query){
				$idq = dbQuery("SELECT `nid` FROM content WHERE `timestamp`=$timestamp AND `title`='$title'");
				$id = mysql_fetch_assoc($idq);
				$quizid = $id['nid'];
				$query2 = dbQuery("INSERT INTO content_quiz (`id`, `poster`, `due`, `body`, `canDoAfterDue`) VALUES ($quizid, {$_SESSION['userid']}, $unixdate, '$body', $afterdue)");
				if ($query2){
				for ($i = 1; $i <= $questionCount; $i++){
					if (isset($_POST['q'.$i])){
						//We exist!! :D
						$q = makeSafe($_POST['q'.$i]);
						$a = makeSafe($_POST['a'.$i]);
						$b = makeSafe($_POST['b'.$i]);
						$c = makeSafe($_POST['c'.$i]);
						$d = makeSafe($_POST['d'.$i]);
						
						
						
						dbQuery("INSERT INTO content_quiz_questions (`quiz_id`, `q`, `1`, `2`, `3`, `4`) VALUES ($quizid, '$q', '$a' , '$b', '$c', '$d')");
					}
					header("Location:classpage.php?id=$class");
				}
				}else{
					header("Location:message.php?id=10");
				}
			}else{
				header("Location:message.php?id=10");
			}
		}else{
			//Error making quiz
			header("Location:message.php?id=10");
		}
	}
	
	$class = -1;
	if (isset($_GET['class'])){
		$class = $_GET['class'];
	}
	
	if ($class == -1){
		header("Location:message.php?id=9");
	}
?>

<script>
	var questions = 0;
	var existingQuestions = 0;
	//Update question count
	function updateCount(count){
		existingQuestions++;
		questions++;
		$("input[id=questioncount]").val(questions + "");
		return questions;
	}
	
	function removeQuestion(id){
		if (existingQuestions == 1){
			alert("Cant remove the last question!!");
		}else{
			$(".question" + id).hide(1000, function(){
				$(".question" + id).html("");
				$("#qfield" + id).html("");
				existingQuestions--;
			});
			}
	}
	
	function addQuestion(id){
		$("#holder" + id).html(
		'<div id="contentbox" class="question' + id + '"><div class="contentboxheader">Question ' + id + '<a href="javascript:removeQuestion(' + id + ');" class="styledLink" style="float:right;">Remove Question</a></div>'+
		'<div class="contentboxbody">'+
		'<p style="font-style:italic;font-size:12px;margin-bottom:3px;">The Questions, Correct Answer and Answer 1 are required. Leave the other answers blank if you wish to not include them.</p>' +
		'<div class="field"><label>Question:</label><textarea class="input" type="text" name="q' + id + '" id="question"></textarea></div>' +
		'<div class="field"><label>Correct Answer:</label><input class="input" type="text" name="a' + id + '" id="question" /></div>' +
		'<div class="field"><label>Other Answer 1:</label><input class="input" type="text" name="b' + id + '" id="question" /></div>' +
		'<div class="field"><label>Other Answer 2: <span style="font-style:italic;font-size:12px;">(Optional)</span></label><input class="input" type="text" name="c' + id + '" id="question" /></div>' +
		'<div class="field"><label>Other Answer 3: <span style="font-style:italic;font-size:12px;">(Optional)</span></label><input class="input" type="text" name="d' + id + '" id="question" /></div>' +
		'</div></div><div id="holder' + (id + 1) +'"></div>');
		$(".question" + id).hide(0);
		$(".question" + id).show(1000);
	}
	
	$(document).ready(function() {
	
		$("#addQuestion").click(function() {
			addQuestion(updateCount(1));
		});
		
		addQuestion(updateCount(1));
		
		
		$('#quiz').submit(function() {
			var questions = $("#questioncount").val();
			var valid = true;
			var invalid = 0;
			
			var title = $("#titlebox").val();
			var desc = $("#description").val();
			var date = $("#datepicker").val();
			
			if (title == ""){
				valid = false;
				$("#title.field").addClass('highlightYellow', 2000);
			}			
			if (desc == ""){
				valid = false;
				$("#desc.field").addClass('highlightYellow', 2000);
			}
			if (date == ""){
				valid = false;
				$("#date.field").addClass('highlightYellow', 2000);
			}
			
			for (var i = 1; i <= questions; i++){
				var questionHtml = $(".question" + i).html();
				var q = $("#question[name=q" + i + "]").val();
				var ca = $("#question[name=a" + i + "]").val();
				var oa = $("#question[name=b" + i + "]").val();
				if (questionHtml != "" && (q == "" || ca == "" || oa == "")){
					valid = false;
					$(".question" + i).addClass('highlightYellow', 2000);
					invalid++;
				}
			}
			if (!valid){
				$("#error").html("You have some blank fields. They have been highlighted. Please fill them out, or remove the question if you are not using it");
			}
			return false;
		});
	});
	
	 $(function() {
		$( "#datepicker" ).datepicker();
	});
	
	//TODO
</script>

<?
	drawToolBoxes();
	beginMainContent();
	$hourOptions = "";
	$minOptions = "";
	$secOptions = "";
	//<option value="-12.0" '; if ($selected == -12){echo "selected";} echo'>(GMT -12:00) Eniwetok, Kwajalein</option>
	for ($h = 0; $h < 24; $h++){
		$hourOptions .= "<option value='$h'>$h</option>";
	}
	
	for ($ms = 0; $ms < 60; $ms++){
		$minOptions .= "<option value='$ms'>$ms</option>";
		$secOptions .= "<option value='$ms'>$ms</option>";
	}
?>
	<script src='js/ui/jquery.ui.effect.js'></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	 <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="css/quizform.css"/>
	<form method="post" action="" id="quiz">
			<div id="contentbox"><div class="contentboxbody">
				<div class="field" id='title'>
					<label>Title:</label>
					<input class="input" type="text" name="title" id="titlebox" />
				</div>
				<div class="field" id='desc'>
					<label>Quiz Description:</label>
					<textarea class="input" type="text" name="description" id="description" rows="4"></textarea>
				</div>
				<div class="field" id='date'>
					<label>Due Date: <span style='font-size:12px;font-weight:normal;'>(MM/DD/YYYY)</span></label>
					<input class="input" type="text" id="datepicker" name='date'/>
				</div>
				<div class="field">
					<label>Due Time: </label>
					<span class='bold'>Hours</span><select class="input" name="hour" style='width:60px;'><? echo $hourOptions?></select>
					<span class='bold'>Minutes</span><select class="input" name="min" style='width:60px;'><? echo $minOptions?></select>
					<span class='bold'>Seconds</span><select class="input" name="sec" style='width:60px;'><? echo $secOptions?></select>
				</div>
				<div class="field" style='padding-bottom:10px;'>
					<label>Can do after due:</label>
					<input type="checkbox" id="afterdue" style="line-height:25px;padding-top:5px;" value="do">
				</div>
			</div></div>
			
			<div id='holder1'></div>
			
			<div id="contentbox"><div class="contentboxbody">
				<span id='error' style='font-weight:bold;color:#FF5566'></div>
				<div class="field">
					<label style="visibility:hidden;">.</label>
					<input class='input' type="button" value="Add Question" name='addQ' id="addQuestion"/>
				</div>
				
				<div class="field">
					<label style="visibility:hidden;">.</label>
					<input class='input' type="submit" value="Submit" name='submitquiz' id="submitquiz"/>
				</div>
			</div>
			
			<input id="questioncount" type='hidden' name='questioncount' value='0' />
			<input id="class" type='hidden' name='class' value='<? echo $class; ?>' />
		</form>
<?	
	endMainContent();
	footer();
?>