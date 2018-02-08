<?php
function ilog($v, $string){
	
	// $v = preg_replace("/INNER JOIN/i", "\n\tINNER JOIN", $v);
	// $v = preg_replace("/FROM/i", "\n\tFROM", $v);
	// $v = preg_replace("/WHERE/i", "\n\tWHERE", $v);
	// $v = preg_replace("/ORDER/i", "\n\tORDER", $v);
	// $v = preg_replace("/LIMIT/i", "\n\tLIMIT", $v);
	// file_put_contents("../../../log.txt", urldecode($_SERVER['REQUEST_URI'])."\n".$v."\n", FILE_APPEND);
	file_put_contents("../../../log.txt", $string . "|" . $v."\n", FILE_APPEND);
}
if(!function_exists('apache_request_headers')) {
	function apache_request_headers() {
		$headers = array();
		foreach($_SERVER as $key => $value) {
			if(substr($key, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

class main_lib{
	static $controller = null;
	public function __construct(){
		require_once(lib.'g.lib.php');
		config_lib::config();
		$this->loadController();
	}

	public function loadController(){
		if(!autoload::check("controller")){
			config_lib::changeChild();
			if(!autoload::check("controller")){
				config_lib::changeMethod();
				if(!autoload::check("controller")){
					config_lib::changeClass();
				}
			}
		}
		$controller = main_lib::$controller = new controller;
		if(!$controller->onUrl && config_lib::$url != ''){
			page_lib::page("url::".config_lib::$url);
		}
		$controller->hendel();
	}
}

function save(){
	$args = func_get_args();
	if(is_array($args[0])){
		$args = $args[0];
	}

	if(array_key_exists("class", $args)){
		$class = $args['class'];
	}elseif(isset($args[0])){
		$class = $args[0];
	}else{
		$class = false;
	}

	if(array_key_exists("method", $args)){
		$method = $args['method'];
	}elseif(isset($args[1])){
		$method = $args[1];
	}else{
		$method = false;
	}

	if(array_key_exists("child", $args)){
		$child = $args['child'];
	}elseif(isset($args[2])){
		$child = $args[2];
	}else{
		$child = false;
	}
	if(array_key_exists("mod", $args)){
		$mod = $args['mod'];
	}elseif(isset($args[3])){
		$mod = $args[3];
	}else{
		$mod = false;
	}
	if($child !== false){
		config_lib::changeChild($child);
	}

	if($method !== false){
		config_lib::changeMethod($method);
	}

	if($class !== false){
		config_lib::changeClass($class);
	}
	if($mod){
		$_POST['_post'] = $mod;
		config_lib::$mod = 'model';
	}
}
?>