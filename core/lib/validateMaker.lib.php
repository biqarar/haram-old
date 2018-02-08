<?php
class validateMaker_lib{
	private $validtorFunctions;
	static $ExtendClass;
	function __construct(){
		$this->form = (!isset($this->form)) ? new  validateObjectError: new  validateObjectError($this->form);
		$this->sql = (!isset($this->sql)) ? new  validateObjectError : new  validateObjectError($this->sql);

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
			$clsName = "FormsValidate_{$value}_cls";
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

	function getFunctions(){
		return $this->validtorFunctions;
	}
	function __call($name, $arg){
		if(preg_match("/^(form|sql)([A-Z].*)$/", $name, $txterr)){
			$method = $txterr[1];
			$this->$method(strtolower($txterr[2]), $arg[0]);
			return $this;
		}
		if(!is_object($this->validtorFunctions)){
			$this->validtorFunctions = (object) array();
		}
		if(!self::$ExtendClass){
			self::$ExtendClass = new validateExtends_cls;
		}
		if(method_exists(self::$ExtendClass, $name)){
			$func = new ReflectionMethod('validateExtends_cls', $name);
			$closure =  $func->getClosure(self::$ExtendClass);
			array_unshift($arg, $closure);
			$this->validtorFunctions->$name = (count($arg) > 0) ? $arg : true;
		}
		$this->validtorFunctions->$name = (count($arg) > 0) ? $arg : true;
		$this->form->$name = "empty text error for $name";
		return $this;
	}
}

function validate(){
	return new validateMaker_lib(func_get_args());
}

class validateObjectError{
	private $Class = false;
	public function __construct($array = false){
		$this->Class = debug_backtrace(true)[1]['object'];
		if(!is_object($array) && $array){
			foreach ($array as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	function __call($name, $args){
		$this->$name = $args[0];
		return $this->Class;
	}
}
?>