<?php

define("SITENAME","Online Creative Neuro-Learning Tools");
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
}

function footer(){
global $starttime;
$time = round(microtime_float() - $starttime, 4);
echo '<div id="footer" style="height:50px; margin-top:10px;width:100%;">
	<span style="font-size:12px;font-style:italic;">Copyright 2013 - Matt and Justin - <a href="reportbug.php" style="color:black; font-weight:bold;">Report a bug</a></span><br/>
	<span style="font-size:12px;font-style:italic;color:#101010;">All times in '.getTimeZoneString(getTimezone()).'</span><br/>
	<span style="color:#B0B0B0;font-size:12px;font-style:italic;">Generated in '.$time.' seconds</span>
	</div>
	</div>
	</body>
	</html>';
}

function buildContent($contentID){
	global $dateFormat;

	$contentDetails = getContentGeneral($contentID);
	$contentTemplate = getTemplate($contentDetails['type']);
	$content = getContentSpecifics("content_".$contentDetails['type'], $contentID);
	
	$displayableContent = $contentTemplate;
	
	$displayableContent = str_replace('$$CONTENT_TITLE', $contentDetails['title'], $displayableContent);
	$displayableContent = str_replace('$$CONTENT_TIME', date($dateFormat, getTimeWithZone($contentDetails['timestamp'], +10)), $displayableContent);
	$displayableContent = str_replace('$$CONTENT_ID', $contentDetails['nid'], $displayableContent);
	$displayableContent = str_replace('$$CONTENT_USER', resolveFullnameFromID($content['poster']), $displayableContent);
	
	
	switch($contentDetails['type']){
		case "news":
			$displayableContent = str_replace('$$CONTENT_USER', resolveFullnameFromID($content['poster']), $displayableContent);
			$displayableContent = str_replace('$$CONTENT_BODY', $content['body'], $displayableContent);
			
			if ($content['lasteditor'] > 0){
				$displayableContent = str_replace('$$?IF_EDIT', "", $displayableContent);
				$displayableContent = str_replace('$$?ENDIF_EDIT', "", $displayableContent);
				$displayableContent = str_replace('$$CONTENT_EDITOR', resolveFullnameFromID($content['lasteditor']), $displayableContent);
				$displayableContent = str_replace('$$CONTENT_EDIT_TIME', resolveFullnameFromID($content['edittime']), $displayableContent);
			}else{
				$parts = explode('$$IF_EDIT', $displayableContent);
				$rebuilt = "";
				for ($i = 0; $i < count($parts); $i++){
					if (($i % 2) == 0){
						$rebuilt .= $parts[$i];
					}
				}
				$displayableContent = $rebuilt;
			}
			break;
		
		case "quiz":
			
			$quizBody = "<div style='border-bottom:1px #CDD2CD dashed;margin:-8px -10px 8px -10px;padding:0px 10px 3px 10px;'><span style='font-weight:bold;'>Quiz Due: </span><span style='";
			if ($content['due'] < time()){
				$quizBody .= "color:#EC0000"; 
			}else {
				$quizBody .= "color:#11EC11"; 
			}
			$quizBody .= "';>".date($dateFormat, getTimeWithZone($content['due'], +10))."</div>";
			
			$quizBody .= $content['description']."";
			
			
			
			$displayableContent = str_replace('$$CONTENT_BODY', $quizBody, $displayableContent);
			
			break;
	}
	
	
	
	return $displayableContent;
	
}


function getContentGeneral($contentID){
	return mysql_fetch_assoc(dbQuery("SELECT * FROM content WHERE `nid`=$contentID"));
}

function getTemplate($type){
	return file_get_contents("templates/".$type.".html");
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

//Reusing this code as its faster for showing JUST news on the home page
function generateIndex(){
	global $dateFormat;
	
	if (loggedIn()){
		$query = dbQuery("SELECT class FROM users WHERE `id`={$_SESSION['userid']} LIMIT 1");
		$user = mysql_fetch_assoc($query);
		$query = dbQuery("SELECT * FROM content WHERE `type`='news' AND `class` IN ({$user['class']}, -1)  ORDER BY `timestamp` ASC LIMIT 10");
	}else{
		$query = dbQuery("SELECT * FROM content WHERE `type`='news' AND `class`=-1  ORDER BY `timestamp` ASC LIMIT 10");
	}
	$contentTemplate = getTemplate('news');
	$overallPage = "";
	while(($contentDetails = mysql_fetch_assoc($query))==true){
		$displayableContent = $contentTemplate;
		$content = getContentSpecifics("content_news", $contentDetails['nid']);
		
		$displayableContent = str_replace('$$CONTENT_TITLE', $contentDetails['title'], $displayableContent);
		$displayableContent = str_replace('$$CONTENT_ID', $contentDetails['nid'], $displayableContent);
		$displayableContent = str_replace('$$CONTENT_TIME', date($dateFormat, getTimeWithZone($contentDetails['timestamp'], +10)), $displayableContent);
		$displayableContent = str_replace('$$CONTENT_USER', resolveFullnameFromID($content['poster']), $displayableContent);
		$displayableContent = str_replace('$$CONTENT_BODY', $content['body'], $displayableContent);
		if ($content['lasteditor'] > 0){
			$displayableContent = str_replace('$$?IF_EDIT', "", $displayableContent);
			$displayableContent = str_replace('$$?ENDIF_EDIT', "", $displayableContent);
			$displayableContent = str_replace('$$CONTENT_EDITOR', resolveFullnameFromID($content['lasteditor']), $displayableContent);
			$displayableContent = str_replace('$$CONTENT_EDIT_TIME', resolveFullnameFromID($content['edittime']), $displayableContent);
		}else{
			$parts = explode('$$IF_EDIT', $displayableContent);
			$rebuilt = "";
			for ($i = 0; $i < count($parts); $i++){
				if (($i % 2) == 0){
					$rebuilt .= $parts[$i];
				}
			}
			$displayableContent = $rebuilt;
		}
		
		$overallPage .= $displayableContent;
	}
	return $overallPage;
}

$classnameById = array();

function getClassName($id){
	if (isset($classnameById[$id])){
		return $classnameById[$id];
	}
	$query  = dbQuery("SELECT name FROM classes WHERE `id`=$id");
	$class = mysql_fetch_assoc($query);
	$classnameById[$id] = $class['name'];
	return $class['name'];
}

function getNewsForClass($id){
	$stories = array();
	
	global $dateFormat;
	$query = dbQuery("SELECT * FROM content WHERE `type`='news' AND `class`=$id ORDER BY `timestamp` ASC LIMIT 10");
	$contentTemplate = getTemplate('news');
	$overallPage = "";
	while(($contentDetails = mysql_fetch_assoc($query))==true){
		$displayableContent = $contentTemplate;
		$content = getContentSpecifics("content_news", $contentDetails['nid']);
		
		$displayableContent = str_replace('$$CONTENT_TITLE', $contentDetails['title'], $displayableContent);
		$displayableContent = str_replace('$$CONTENT_ID', $contentDetails['nid'], $displayableContent);
		$displayableContent = str_replace('$$CONTENT_TIME', date($dateFormat, getTimeWithZone($contentDetails['timestamp'], +10)), $displayableContent);
		$displayableContent = str_replace('$$CONTENT_USER', resolveFullnameFromID($content['poster']), $displayableContent);
		$displayableContent = str_replace('$$CONTENT_BODY', $content['body'], $displayableContent);
		if ($content['lasteditor'] > 0){
			$displayableContent = str_replace('$$?IF_EDIT', "", $displayableContent);
			$displayableContent = str_replace('$$?ENDIF_EDIT', "", $displayableContent);
			$displayableContent = str_replace('$$CONTENT_EDITOR', resolveFullnameFromID($content['lasteditor']), $displayableContent);
			$displayableContent = str_replace('$$CONTENT_EDIT_TIME', resolveFullnameFromID($content['edittime']), $displayableContent);
		}else{
			$parts = explode('$$IF_EDIT', $displayableContent);
			$rebuilt = "";
			for ($i = 0; $i < count($parts); $i++){
				if (($i % 2) == 0){
					$rebuilt .= $parts[$i];
				}
			}
			$displayableContent = $rebuilt;
		}
		
		$stories[] = $displayableContent;
	}
	
	return $stories;
}

?>
