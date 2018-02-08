<?php
class validator_lib{
	public $group, $status = true, $select, $error, $value, $type = 'fatal', $_functions = array(), $name, $validate, $onError;
	public static $save = array();
	public function __construct($value, $validator, $group = 'public'){
		if(is_array($value)){
			$this->name = $value[0];
			$this->value = $value[1];
		}else{
			$this->name = count(self::$save);
			$this->value = $value;
		}
		self::$save[$group] = !isset(self::$save[$group]) || !is_array(self::$save[$group]) ? array() : self::$save[$group];
		self::$save[$group][$this->name] = $this;
		$this->group		= $group;
		$this->validate	= $validator;
		$this->config();
	}

	final function config(){
		if(method_exists($this->validate, 'getFunctions') && $this->validate->getFunctions()){
			foreach ($this->validate->getFunctions() as $key => $args) {
				if(empty($args)){
					$args = array();
				}elseif(!is_array($args)){
					$args = array($args);
				}
				if(!method_exists($this, $key)){
					if(!is_object($args[0])){
						page_lib::page("validate inline extends $key not found");
					}
					$this->_functions[$key] = \Closure::bind($args[0], $this);
				}
				$onf = $this->status;
				$ret = call_user_func_array(array($this, $key), $args);
				if($ret === false || ($onf === true && $this->status === false)){
					$this->setError($key);
				}
			}
		}

	}

	final function setError($key){
		if($this->status || $this->error === null){
			$this->status = false;
			$this->error = isset($this->validate->form->$key) ? $this->validate->form->$key : "error on $key";
		}
	}

	final function compile(){
		if($this->status !== false){
			return $this->value;
		}else{
			debug_lib::{$this->type}($this->error, $this->name, $this->group);
			return (empty($this->onError))? false : $this->onError;
		}
	}

	final function type($type){
		$this->type = ($type = 'warn')? 'warn' : 'fatal';
	}

	final function onError($onError){
		$this->onError = $onError;
	}

	final function __call($name, $args){
		array_shift($args);
		return call_user_func_array($this->_functions[$name], $args);
	}
}
?>