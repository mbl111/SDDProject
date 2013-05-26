<?php

define("SITENAME","Online Creative Neuro-Learning Tools");

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


function drawTimeZoneDropDown(){
echo '<select name="DropDownTimezone" id="DropDownTimezone">
      <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
      <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10.0">(GMT -10:00) Hawaii</option>
      <option value="-9.0">(GMT -9:00) Alaska</option>
      <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
      <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
      <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="-3.5">(GMT -3:30) Newfoundland</option>
      <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
      <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
      <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
      <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
      <option value="3.5">(GMT +3:30) Tehran</option>
      <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="4.5">(GMT +4:30) Kabul</option>
      <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="5.75">(GMT +5:45) Kathmandu</option>
      <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
      <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
      <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
      <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
</select>';
}

function mustBeLoggedin($pageToSend = "index.php"){

}

function loggedIn(){
	return false;
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
echo '<div id="footer" style="height:50px; margin-top:10px;width:100%;">
	<span style="font-size:12px;font-style:italic;">Copyright 2013 - Matt and Justin</span>
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
	
	
	switch($contentDetails['type']){
		case "news":
			$displayableContent = str_replace('$$CONTENT_USER', resolveUserFromID($content['poster']), $displayableContent);
			$displayableContent = str_replace('$$CONTENT_BODY', $content['body'], $displayableContent);
			
			if ($content['lasteditor'] > 0){
				$displayableContent = str_replace('$$?IF_EDIT', "", $displayableContent);
				$displayableContent = str_replace('$$?ENDIF_EDIT', "", $displayableContent);
				$displayableContent = str_replace('$$CONTENT_EDITOR', resolveUserFromID($content['lasteditor']), $displayableContent);
				$displayableContent = str_replace('$$CONTENT_EDIT_TIME', resolveUserFromID($content['edittime']), $displayableContent);
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
			
			$quizData = getQuiz($contentID);
			$quizSection = "";
			shuffle($quizData);
			foreach ($quizData as $question){
				
			}
			
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
	return mysql_fetch_assoc(dbQuery("SELECT * FROM $dataTable WHERE `id`=$contentID"));
}

function resolveUserFromID($uid){
	return "[SpA]mbl111";
}

function getQuiz($contentID){
	return mysql_fetch_assoc(dbQuery("SELECT * FROM content WHERE `quiz_id`=$contentID"));
}






?>
