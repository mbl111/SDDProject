<?
$ENGINE_TEMPLATES = array();

class Template {
	
	private $values = array();
	
	function assign($key, $value){
		$this->values[$key] = $value;
	}
	
	function render($template_name){
		$template = $this->getTemplate($template_name);
		
		foreach ($this->values as $key => $value){
			$template = preg_replace('/\['.$key.'\]/', $value, $template);
		}
		
		$template = preg_replace('/\<\!\-\- IF (.*) \-\-\>/', '<? if ($1) : ?>', $template);
		$template = preg_replace('/\<\!\-\- ELSE \-\-\>/', '<? else : ?>', $template);
		$template = preg_replace('/\<\!\-\- ENDIF \-\-\>/', '<? endif; ?>', $template);
		eval(' ?>' .$template.'<? ');
		//Debugging output
		//echo $template;
	}
	
	function loadTemplate($template_name){
		global $ENGINE_TEMPLATES;
		$path = 'templates/'.$template_name.'.html';
		if (file_exists($path)){
			$ENGINE_TEMPLATES[$template_name] = file_get_contents($path);
		}else{
			die("Template $template_name not found");
		}
	}
	
	function getTemplate($template_name){
		global $ENGINE_TEMPLATES;
		if (!isset($ENGINE_TEMPLATES[$template_name])){
			$this->loadTemplate($template_name);
		}
		return $ENGINE_TEMPLATES[$template_name];
	}

}
?>