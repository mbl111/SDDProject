<?
class SettingGroup{

	var $settings = array();
	var $name = "";
	var $buttontext = "";
	var $buttonWidth = 150;

function __construct($settingName, $buttontext = "Change my text"){
	$this->name = $settingName;
	$this->buttontext = $buttontext;
}

function addSetting($setting){
	if (is_subclass_of($setting, "BaseSetting")){
		$this->settings[] = $setting;
	}else{
		$this->settings[] = "Tried to add a non-setting here";
	}
}

function addConstant($ident, $value){
	
}

function addText($text){
	$this->settings[] = $text;
}

function setButtonWidth($width){
	$this->buttonWidth = $width;
}

function render(){

	echo '<script>$(document).ready(function() {$("#'.$this->name.'b").click(function(e){';
	foreach($this->settings as $set){
		if (is_subclass_of($set, "BaseSetting")){
			echo ' var '.$set->ident.' = $("#'.$set->ident.'.'.$set->cls.'").val();';
		}
	}
	echo ' $("#'.$this->name.'").html("<span style=\'color:#990000\'>Updating... Please wait</span>");';
	echo '  $.post("ajax/setting/'.$this->name.'.php", {';
	$string = "";
	foreach($this->settings as $set){
		if (is_subclass_of($set, "BaseSetting")){
			$string .= $set->ident.':'.$set->ident.",";
		}
	}
	echo rtrim($string, ",");
	echo '} , function(data) {
				if (data=="true"){
					$("#'.$this->name.'").html("Your settings have been updated.");
				}else{
					$("#'.$this->name.'").html("Failed to change setting. ("+ data +") Refresh to try again");
				}
			}).done(function() {})
			.fail(function() { 
				$("#'.$this->name.'").html("Request time out. Refresh to try again");
			});
		});});</script>';

	echo "<div id='".$this->name."' style='margin-bottom:10px;padding-bottom:10px;border-bottom: 1px #BDC2BD dashed;'>";
	
	foreach($this->settings as $set){
		if (is_subclass_of($set, "BaseSetting")){
			$set->render();
		}elseif (is_string($set)){
			echo '<p style="font-style:italic;font-size:12px;margin-bottom:3px;">'.$set.'</p>';
		}
	}
	
	echo "<input class='input' id='{$this->name}b' style='width:{$this->buttonWidth}px;font-weight:bold;margin-left:160px;' type='button' name='submit' value='{$this->buttontext}'/>";
	
	echo '</div>';
}

}

abstract class BaseSetting{

	var $name = "No Name";
	var $default = "";
	var $ident = "";
	var $hint;
	var $cls = 'input';

function __construct($name="No Name",$ident="noname"){
	$this->name = $name;
	$this->ident = $ident;
}

function setDefault($def = ""){
	$this->default = $def;
}

function setHint($hint){
	$this->hint = $hint;
}

abstract function render();

}
class HiddenField extends BaseSetting{
	
	var $cls = 'input';
	
	function __construct($name="No Name",$ident="noname",$value="novalue"){
		$this->name = $name;
		$this->ident = $ident;
		$this->default = $value;
	}
	
	function render(){
		echo "<".$this->cls." class='input' style='visibility:hidden;position:absolute;' name='".$this->ident."' id='".$this->ident."' value='".$this->default."'/>";
	}

}

class TextSetting extends BaseSetting{

	var $type = 'text';
	var $length = 30;
	
	function setType($t){
		if ($t == 0){
			$this->type = "text";
			$this->cls = "input";
		} else {
			$this->cls = "textarea";
			$this->type = "textarea";
		}
	}
	
	function setLength($l){
		$this->length = $l;
	}
		
	function render(){
		echo "<div class='field'>
			<label>".$this->name."</label>
			<".$this->cls." class='".$this->cls."' maxlength='".$this->length."' type='".$this->type."' name='".$this->ident."' id='".$this->ident."' value='".$this->default."'";
			if ($this->cls == "textarea"){
				echo ">".$this->default;
				echo "</".$this->cls.">";
			}else{
				echo "/>";
			}
			
			if (isset($this->hint)){
				echo "<span class='hint'>{$this->hint}</span>";
			}
		echo "</div>";
	}

}

?>