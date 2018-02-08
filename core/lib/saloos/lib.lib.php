<?php
namespace lib\saloos;
/**
 * autoload saloos core lib
 */
class lib{
	public function __call($name, $args){
		$class_name = "lib\\{$name}";
		if(!class_exists($class_name)){
			\page_lib::page("lib $name not found");
		}else{
			return new $class_name($args);
		}
	}
}