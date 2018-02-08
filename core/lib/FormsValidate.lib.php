<?php
class FormsValidate_lib{
	static $__CLASSVALIDATEMETHOD = false;
	function __construct(){
		
		$this->form = (!isset($this->form)) ? (object) array() : (object) $this->form;
		$this->sql = (!isset($this->sql)) ? (object) array() : (object) $this->sql;

		$extends = func_get_args();

		if(isset($extends[0]) && is_array($extends[0])){
			$extends = $extends[0];
		}elseif(count($extends == 0) && isset($this->extends)){
			$extends = (is_array($this->extends)) ? $this->extends : array($this->extends);
			unset($this->extends);
		}
		if(count($extends) >= 1){
			call_user_func_array(array($this,'extend'), $extends);
		}
		if(method_exists($this, 'config')){
			$this->config();
		}
	}
	function extend(){
		$arg = func_get_args();
		$extends = array();
		foreach ($arg as $key => $value) {
			$clsName = "FormsValidate_{$value}_lib";
			array_push($extends, new $clsName);
		}
		call_user_func_array(array($this, 'fExtend'), $extends);
		return $this;
	}

	function fExtend(){
		$extends = func_get_args();
		foreach ($extends as $key => $value) {
			foreach ($value as $K => $V) {
				if(preg_match("/^(form|sql)$/", $K)){
					foreach ($V as $k => $v) {
						if(!isset($this->$K->$k)){
							$this->$K->$k = $v;
						}
					}
				}elseif(!isset($this->$K)){
					$this->$K = $V;
				}
			}
		}
	}

	final function form($name, $value){
		$this->form->$name = $value;
		return $this;
	}

	final function sql($name, $value){
		$this->sql->$name = $value;
		return $this;
	}

	function __call($name, $arg){
		if(!self::$__CLASSVALIDATEMETHOD){
			$x = new validate_lib(false, false, false);
			self::$__CLASSVALIDATEMETHOD = get_class_methods($x);
		}
		if(array_search($name, self::$__CLASSVALIDATEMETHOD)!== false){
			$this->$name = (count($arg) > 0) ? $arg : true;
		}
		return $this;
	}
}
?>