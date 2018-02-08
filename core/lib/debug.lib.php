<?php
class debug_lib{
	#errors
	private static $_fatal = array();
	private static $_warn = array();
	private static $_true = array();
	private static $_msg = array();
	private static $_property = array();
	private static $_form = array();
	public static $x = true;
	#error status: 0 : fatal , 1 : true, 2 : warning;
	public static $status = 1;


	#set errors
	public static function fatal($error, $field = false, $group = 'public'){
		self::$x = false;
		self::$status = 0;
		if($field){
			array_push(self::$_fatal, array('error' => $error, $group => $field, "group" => $group));
		}else{
			array_push(self::$_fatal, $error);
		}
	}
	public static function warn($error, $field = false, $group = 'public'){
		if(self::$x){
			self::$status = 2;
		}
		if($field){
			array_push(self::$_warn, array('error' => $error, $group => $field, "group" => $group));
		}else{
			array_push(self::$_warn, $error);
		}
	}
	public static function true($error){
		array_push(self::$_true, $error);
	}

	#set msg
	public static function msg($msg, $key = false){
		if(is_array($msg)){
			foreach ($msg as $key => $value) {
				self::$_msg[$key] = $value;
			}
		}else{
			if($key){
				self::$_msg[$msg] = $key;
			}else{
				array_push(self::$_msg, $msg);
			}
			
		}
	}

	public static function property($property, $key = false){
		if(is_array($property)){
			foreach ($property as $key => $value) {
				self::$_property[$key] = $value;
			}
		}else{
			if($key !== false){
				self::$_property[$property] = $key;
			}else{
				array_push(self::$_property, $property);
			}
			
		}
	}

	public static function form($f){
		if(!array_search($f, self::$_form)){
			self::$_form[] = $f;
		}
	}
	public static function compile($j = false){
		$e = array();
		$e['status'] = self::$status;
		if(count(self::$_fatal) > 0) $e['fatal'] = self::$_fatal;
		if(count(self::$_warn) > 0) $e['warn'] = self::$_warn;
		if(count(self::$_msg) > 0) $e['msg'] = self::$_msg;
		if(count(self::$_property) > 0){
			foreach (self::$_property as $key => $value) {
				$e[$key] = $value;
			}
		}
		if(count(self::$_form) > 0) $e['msg']['form'] = self::$_form;
		if(count(self::$_true) > 0 || count($e) ==0) $e['true'] = self::$_true;
		return ($j)? json_encode($e) : $e;
	}
}

?>