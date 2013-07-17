<?php

define("SITENAME","Quiz JaM");
	define("USER_STUDENT", 1);
	define("USER_TEACHER", 0);

if (isset($usesettings)){
	include("setting.class.php");
	include("textsetting.class.php");
}

$timeConstant = array('decade' => 315576000, 'year' => 31557600, 'month' => 2629800, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'min' => 60, 'sec' => 1);
$dateFormat = "H:i, d M Y";
$toolboxes = array();

function getTimeGMT($time){
    global $timeConstant;
    return $time - ($timeConstant['hour']*2);
}

function getTimeWithZone($time, $zone){
    global $timeConstant;
    return ($time - ($timeConstant['hour']*2)) + ($zone * $timeConstant['hour']);
}

function getTimeZoneString($tz){
    if ($tz < 0){
        return "$tz GMT";
    }else if ($tz == 0){
        return "GMT";
    }else{
        return "+$tz GMT";
    }
}

function getTimezone(){
	if (isset($_SESSION['timezone'])){
		return $_SESSION['timezone'];
	}
	return 10;
}

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (strtolower(substr($haystack, 0, $length)) === strtolower($needle));
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (strtolower(substr($haystack, -$length)) === strtolower($needle));
}


function drawTimeZoneDropDown($args, $selected = -12){
echo '<select '.$args.'>
      <option value="-12.0" '; if ($selected == -12){echo "selected";} echo'>(GMT -12:00) Eniwetok, Kwajalein</option>
      <option value="-11.0" '; if ($selected == -11){echo "selected";} echo'>(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10.0" '; if ($selected == -10){echo "selected";} echo'>(GMT -10:00) Hawaii</option>
      <option value="-9.0" '; if ($selected == -9){echo "selected";} echo'>(GMT -9:00) Alaska</option>
      <option value="-8.0" '; if ($selected == -8){echo "selected";} echo'>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
      <option value="-7.0" '; if ($selected == -7){echo "selected";} echo'>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="-6.0" '; if ($selected == -6){echo "selected";} echo'>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
      <option value="-5.0" '; if ($selected == -5){echo "selected";} echo'>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="-4.0" '; if ($selected == -4){echo "selected";} echo'>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="-3.5" '; if ($selected == -3.5){echo "selected";} echo'>(GMT -3:30) Newfoundland</option>
      <option value="-3.0" '; if ($selected == -3){echo "selected";} echo'>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
      <option value="-2.0" '; if ($selected == -2){echo "selected";} echo'>(GMT -2:00) Mid-Atlantic</option>
      <option value="-1.0" '; if ($selected == -1){echo "selected";} echo'>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option value="0.0" '; if ($selected == 0){echo "selected";} echo'>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
      <option value="1.0" '; if ($selected == 1){echo "selected";} echo'>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="2.0" '; if ($selected == 2){echo "selected";} echo'>(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="3.0" '; if ($selected == 3){echo "selected";} echo'>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
      <option value="3.5" '; if ($selected == 3.5){echo "selected";} echo'>(GMT +3:30) Tehran</option>
      <option value="4.0" '; if ($selected == 4){echo "selected";} echo'>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="4.5" '; if ($selected == 4.5){echo "selected";} echo'>(GMT +4:30) Kabul</option>
      <option value="5.0" '; if ($selected == 5){echo "selected";} echo'>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="5.5" '; if ($selected == 5.5){echo "selected";} echo'>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="5.75" '; if ($selected == 5.75){echo "selected";} echo'>(GMT +5:45) Kathmandu</option>
      <option value="6.0" '; if ($selected == 6){echo "selected";} echo'>(GMT +6:00) Almaty, Dhaka, Colombo</option>
      <option value="7.0" '; if ($selected == 7){echo "selected";} echo'>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="8.0" '; if ($selected == 8){echo "selected";} echo'>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
      <option value="9.0" '; if ($selected == 9){echo "selected";} echo'>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="9.5" '; if ($selected == 9.5){echo "selected";} echo'>(GMT +9:30) Adelaide, Darwin</option>
      <option value="10.0" '; if ($selected == 10){echo "selected";} echo'>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="11.0" '; if ($selected == 11){echo "selected";} echo'>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="12.0" '; if ($selected == 12){echo "selected";} echo'>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
</select>';
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function mustBeLoggedin($pageToSend = "message.php?id=1"){
	if (!loggedIn()){
		header("Location:$pageToSend");
	}
}

function mustBeTeacher(){
	if ($_SESSION['usertype'] != USER_TEACHER){
		header("Location:message.php?id=2");
	}
}

function loggedIn(){
	return isset($_SESSION['userid']);
}

function isAdmin(){
	return isset($_SESSION['admin']) and $_SESSION['admin'] > 0;
}

function drawToolBoxes(){
	global $toolboxes;
	if (isset($toolboxes)){
		foreach($toolboxes as $toolbox){
			echo "<div id='toolbox'>
					<div class='toolboxheader'>{$toolbox["header"]}</div>
					<div class='toolboxcontent'>
						{$toolbox["body"]}
					</div>
				</div>";
		}
	}else{
		die("Null boxes!");
	}
}

function addToolBox($header = "Missing Header", $bodyHTML = "What! No body HTML"){
	global $toolboxes;
	$toolboxes[] = array(
	"header" => $header,
	"body" => $bodyHTML
	);
}

function beginMainContent(){
echo '</aside><div id="centerelement">';
}

function endMainContent(){
echo "</div></div>";
	if (loggedIn()){
		global $timeConstant;
		$timeCheck = time() - ($timeConstant['min'] * 5);
		$query = dbQuery("SELECT `firstname`, `lastname`, `id` FROM users WHERE `lastactive` > $timeCheck AND `id`!={$_SESSION['userid']}");
		if ($query){
			echo "<div id='contentbox' style='display:table;width:970px;margin-left:10px;'><div style='padding-bottom:3px;' class='contentboxbody'>";
			echo "<p class='usersonlinetitlespan' style='font-weight:bold;'>Users online (Based on activity from the past 5 minutes)</p><br/><div class='usersonlinespan'>";
			$num = mysql_num_rows($query);
			if ($num == 0){
				echo "No one else is online :(";
			}elseif ($num <= 5){
				while (($user = mysql_fetch_assoc($query))){
						echo "<a href='userpage.php?id=".$user['id']."' class='styledLink'>".$user['firstname']." ".$user['lastname']."</a>";
						if ($num == 1){
							echo "."; 
						}else{
							echo ", ";
						}
						$num--;
				}
			}else{
				$count = 5;
				while (($user = mysql_fetch_assoc($query)) and $count > 0){
						echo "<a href='userpage.php?id=" . $user['id'] . "' class='styledLink'>".$user['firstname']." ".$user['lastname']."</a>";
						if ($count == 1) {
							echo " and ".$num--." others.";
						}else{
							echo ", ";
						}
						$num--;
						$count--;
				}
			}
		}
		echo "</div></div>";
	}
	echo "</div></div></div>";
}

function footer(){
global $starttime;
global $dateFormat;
$timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : 0;
$time = round(microtime_float() - $starttime, 4);
$svrtime = date($dateFormat, getTimeWithZone(time(), $timezone));
$reportBugPath = str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);
$reportBugPath = str_replace("bug/", "", $reportBugPath);
echo '<div id="footer" style="height:50px; margin-top:10px;width:100%;">
	<a href="'.$reportBugPath.'help/index.php" style="color:black; font-weight:bold;font-size:12px;line-height:20px;">View Online Help</a><br/>
	<span style="font-size:12px;font-style:italic;"><a href="'.$reportBugPath.'bug/index.php" style="color:black; font-weight:bold;">Bug Tracker</a> - 
	<a href="'.$reportBugPath.'bug/reportbug.php" style="color:black; font-weight:bold;">Report a bug</a><br/>All times in '.getTimeZoneString(getTimezone()).'<br/>
	Copyright 2013 - Matt and Justin<br/></span>
	<span style="color:#B0B0B0;font-size:12px;font-style:italic;">Generated in '.$time.' seconds | Time now is '.$svrtime.'</span>
	</div>
	</div>
	</body>
	</html>';
}

function buildContent($contentID){
	global $dateFormat;

	$contentDetails = getContentGeneral($contentID);
	$content = getContentSpecifics("content_".$contentDetails['type'], $contentID);
	
	$template = new Template;
		
		$timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : 0;
		
		$template->assign("CONTENT_TITLE", $contentDetails['title']);
		$template->assign("CONTENT_ID", $contentDetails['nid']);
		$template->assign("CONTENT_TIME", date($dateFormat, getTimeWithZone($contentDetails['timestamp'], $timezone)));
		$template->assign("CONTENT_USER", resolveFullnameFromID($content['poster']));
		$template->assign("CONTENT_BODY", $content['body']);
		$template->assign("CONTENT_HIDDEN", $contentDetails['visible'] ? "false" : "true");
		
		$template->assign("ADMIN", isAdmin() ? "true" : "false");
		$template->assign("POSTER", (loggedIn() and $content['poster'] == $_SESSION['userid']) ? "true" : "false");
		$template->assign("POSTER_ID", $content['poster']);
		
		$template->assign("GLOBAL_STORY", $contentDetails['class'] == -1 ? "true" : "false");
		if ($contentDetails['class'] != -1){
			$template->assign("CLASS_NAME", getClassName($contentDetails['class']));
			$template->assign("CLASS_ID", $contentDetails['class']);
		}
		
		if ($contentDetails['type'] == 'news'){
			$template->assign("CONTENT_EDITED", ($content['lasteditor'] > 0) ? "true" : "false");
			$template->assign("CONTENT_EDITOR", resolveFullnameFromID($content['lasteditor']));
			$template->assign("CONTENT_EDIT_TIME", date($dateFormat, getTimeWithZone($content['edittime'], $timezone)));
		}elseif ($contentDetails['type'] == 'quiz'){
			$doneQuiz = userHasDoneQuiz($contentDetails['nid'], $_SESSION['userid']);
		
			$template->assign("QUIZ_OVERDUE", $content['due'] < time() ? 'true' : 'false');
			$template->assign("QUIZ_DUE", date($dateFormat, getTimeWithZone($content['due'], $_SESSION['timezone'])));
			$template->assign("QUIZ_STATUS", $doneQuiz ? "Quiz Completed" : "Not Complete");
			$template->assign("QUIZ_PAGE", "false");
			if ($doneQuiz){
				$template->assign("QUIZ_MARKS", getUserMarksForQuiz($contentDetails['nid'], $_SESSION['userid']));
			}
			$template->assign("QUIZ_QUESTION_COUNT", getNumberOfQuestionsForQuiz($contentDetails['nid']));
			$template->assign("QUIZ_DONE", $doneQuiz ? "true" : "false");
		}
		$template->render($contentDetails['type']);
}

function getNewsForClass($id){
	
	global $dateFormat;
	$query = dbQuery("SELECT nid FROM content WHERE `visible`=1 AND `class`=$id  ORDER BY `timestamp` DESC LIMIT 10");
	while(($idSet = mysql_fetch_assoc($query))==true){
		buildContent($idSet['nid']);
	}
}

function getContentGeneral($contentID){
	return mysql_fetch_assoc(dbQuery("SELECT * FROM content WHERE `nid`=$contentID"));
}

function getContentSpecifics($dataTable, $contentID){
	return mysql_fetch_assoc(dbQuery("SELECT * FROM $dataTable WHERE `id`=$contentID LIMIT 1"));
}

//usernames by id
$usernamesById = array();

function resolveUsernameFromID($uid){
	global $usernamesById;

	if ($uid == null){
		return "";
	}
	if (isset($usernamesById[$uid])){
		return $usernamesById[$uid];
	}
	$query = dbQuery("SELECT username FROM users WHERE id=$uid LIMIT 1");
	if ($query){
		$array = mysql_fetch_assoc($query);
		$usernamesById[$uid] = $array['username'];
		return $usernamesById[$uid];
	}else{
		return "";
	}
}

$fullnamesById = array();

function resolveFullnameFromID($uid){
	global $fullnamesById;

	if ($uid == null){
		return "";
	}
	if (isset($fullnamesById[$uid])){
		return $fullnamesById[$uid];
	}
	$query = dbQuery("SELECT firstname, lastname FROM users WHERE id=$uid LIMIT 1");
	if ($query){
		$array = mysql_fetch_assoc($query);
		$fullnamesById[$uid] =  $array['firstname']." ".$array['lastname'];
		return $fullnamesById[$uid];
	}else{
		return "";
	}
}

function myFullName(){
	return $_SESSION['firstname']." ".$_SESSION['lastname'];
}

function getQuiz($contentID){
	return mysql_fetch_assoc(dbQuery("SELECT * FROM content WHERE `quiz_id`=$contentID"));
}


$classnameById = array();

function getClassName($id){
	if (!isset($id) or $id == ""){
		return "ID NOT SET!! getClassName(id)";
	}
	if (isset($classnameById[$id])){
		return $classnameById[$id];
	}
	$query  = dbQuery("SELECT name FROM classes WHERE `id`=$id");
	$class = mysql_fetch_assoc($query);
	$classnameById[$id] = $class['name'];
	return $class['name'];
}

function userHasDoneQuiz($qid, $uid){
	$q = dbQuery("SELECT `user_id` FROM user_quiz_answers WHERE `user_id`=$uid AND `quiz_id`=$qid");
	if ($q){
		return mysql_num_rows($q) > 0;
	}
	return false;

}

function getUserMarksForQuiz($qid, $uid){
	$q = dbQuery("SELECT `user_id` FROM user_quiz_answers WHERE `user_id`=$uid AND `quiz_id`=$qid AND `answer`=1");
	if ($q){
		return mysql_num_rows($q);
	}
	return -1;
}

function getNumberOfQuestionsForQuiz($qid){
	$q = dbQuery("SELECT `id` FROM content_quiz_questions WHERE `quiz_id`=$qid");
	if ($q){
		return mysql_num_rows($q);
	}
	return -1;
}

function getTimeUserSubmittedQuiz($quiz, $user){
	$q = dbQuery("SELECT `timestamp` FROM user_quiz_answers WHERE `quiz_id`=$quiz AND `user_id`=$user LIMIT 1");
	if ($q){
		if (mysql_num_rows($q) == 0){
			return -1;
		}
		$ts = mysql_fetch_assoc($q);
		return $ts['timestamp'];
	}
	return -1;
}

function studentBelongsTo($idToTest, $student){
	$query = dbQuery("SELECT id FROM users WHERE `id`=$student AND `teacher`=$idToTest LIMIT 1");
	if ($query){
		if (mysql_num_rows($query) == 1){
			return true;
		}
	}
	return false;
}

function makeSafe($s){
$s = strip_tags(mysql_real_escape_string($s));
$s = str_replace("\\r\\n", "<br/>", $s);
return $s;
}

function getTitle($page){
	$sub = "Quiz JaM";
	switch ($page){
		case "addstudent.php" :
			return "$sub | Add Student";
			break;
		case "classpage.php" :
			return "$sub | Class";
			break;
		case "createquiz.php" :
			return "$sub | New Quiz";
			break;
		case "index.php" :
			return "$sub | Home";
			break;
		case "login.php" :
			return "$sub | Login";
			break;
		case "logout.php" :
			return "$sub | Logout";
			break;
		case "message.php" :
			return "$sub | Alert!";
			break;
		case "reportbug.php" :
			return "$sub Bug Center";
			break;
		case "settings.php" :
			return "$sub | Settings";
			break;
		case "students.php" :
			return "$sub | My Students";
			break;
		case "submitnews.php" :
			return "$sub | New News";
			break;
		case "userpage.php" :
			return "$sub | User";
			break;
		case "viewcontent.php" :
			return "$sub | Content";
			break;
		case "viewquiz.php" :
			return "$sub | Quiz";
			break;
	}
}

?>
