<?php
class listen_cls{
	public $cond = true;
	function __construct($c){
		$this->static = (isset($c['static']))? $c['static'] : true;
		if(!isset($c['min'])){
			$c['min'] = 0;
		}
		if(!isset($c['max'])){
			if(!isset($c['url'])){
				$c['max'] = 1;
			}else{
				if(is_array($c['url'])){
					$c['max'] = count($c['url']);
				}else{
					$c['max'] = 1;
				}
			}
		}
		foreach ($c as $key => $value) {
			if(method_exists($this, $key)){
				$this->$key($value);
			}
		}
	}
	function max($c){
		if($c < count(config_lib::$aurl))
			$this->cond = false;
	}

	function min($c){
		if(count(config_lib::$aurl) < $c)
			$this->cond = false;
	}

	function url($urlParameters){
		if(!is_array($urlParameters)){
			$this->checkUrlReg($urlParameters, config_lib::$url);
			return;
		}
		$k = 0;
		foreach ($urlParameters as $key => $value) {
			$array = (preg_match("/\d+/", $key)) ? config_lib::$aurl : config_lib::$surl;
			if(!isset($array[$key])){
				$this->cond = false;
				break;
			}
			/*if(($this->static === true || $this->static === 1) && $array == config_lib::$surl){
				if(!isset(config_lib::$aurl[$k]) || config_lib::$aurl[$k] != "$key=".config_lib::$surl[$key]){
					$this->cond = false;
					break;
				}
			}*/
			$this->checkUrlReg($value, $array[$key]);
			$k++;
		}
	}

	function checkUrlReg($reg, $value){
		if(preg_match("/^(\/.*\/|#.*#|[.*])[gui]{0,3}$/i", $reg)){
			if(!preg_match($reg, $value)){
				$this->cond = false;
			}
		}elseif($reg != $value){
			$this->cond = false;
		}
	}

	function func($f){
		if(!call_user_func($f)){
			$this->cond = false;
		}
	}
}
?>