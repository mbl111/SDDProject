<?

class ListSetting implements BaseSetting{

	function render(){
		echo "<div class='field'>
			<label>$name</label>
			<input class='input' maxlength='30' type='text' name='$ident'id='$ident' value='$defaultValue'/>";
			if (isset($hint)){
				echo "<span class='hint'>$hint</span>";
			}
		echo "</div>";
	}

}

?>