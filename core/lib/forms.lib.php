<?php
class forms_lib{
	private $Element_Sortable_MCLS = array();
	function make($name, $args = false){
		if(is_object($name)){
			$this->$args = $name;
		}elseif(preg_match("/^\.(.*)$/", $name, $fname)){
			$class = "forms_{$fname[1]}_cls";
			return new $class;
		}elseif(preg_match("/^\#(.*)$/", $name, $fname)){
			$form = new forms_Extends_cls;
			if(isset($form->{$fname[1]})){
				return $form->{$fname[1]};
			}else{
				page_lib::page("FORM EXTEND $fname[1]");
			}
		}elseif(preg_match("/^\@(.*)$/", $name, $fname)){
			$controller = main_lib::$controller;
			return $controller->sql("@loadForm", $fname[1], $args);
		}else{
			return new formMaker_lib($name);
		}
	}

	private function sortable(){
		if(count($this->Element_Sortable_MCLS) == 0){
			$array = array();
			foreach ($this as $key => $value) {
				if($key == 'Element_Sortable_MCLS') continue;
				array_push($array, $key);
			}
			$this->Element_Sortable_MCLS = $array;
		}
		ksort($this->Element_Sortable_MCLS);
	}

	function compile($autoSet = true){
		$this->sortable();
		$array = array();
		foreach ($this->Element_Sortable_MCLS as $k => $v) {
			$value = $this->$v;
			if(method_exists($value, "compile")){
				array_push($array, $value->compile($autoSet));
			}
		}
		return $array;
	}

	function after($name, $after){
		$this->sortable();
		$index = array_search($name, $this->Element_Sortable_MCLS);
		if($index === false) return $this;
		$peroperty = $this->Element_Sortable_MCLS[$index];

		$aindex = array_search($after, $this->Element_Sortable_MCLS);
		if($aindex === false) return $this;
		$array = array();
		$aValue = null;
		foreach ($this->Element_Sortable_MCLS as $key => $value) {
			if($key == $index) continue;
			array_push($array, $value);
			if($key == $aindex){
				array_push($array, $peroperty);
			}
		}
		$this->Element_Sortable_MCLS = $array;
		return $this;
	}

	function before($name, $before){
		$this->sortable();
		$index = array_search($name, $this->Element_Sortable_MCLS);
		if($index === false) return $this;
		$peroperty = $this->Element_Sortable_MCLS[$index];

		$bindex = array_search($before, $this->Element_Sortable_MCLS);
		if($bindex === false) return $this;
		$array = array();
		$aValue = null;
		foreach ($this->Element_Sortable_MCLS as $key => $value) {
			if($key == $index) continue;
			if($key == $bindex){
				array_push($array, $peroperty);
			}
			array_push($array, $value);
		}
		$this->Element_Sortable_MCLS = $array;
		return $this;
	}
	function atEnd($name){
		$this->sortable();
		$index = array_search($name, $this->Element_Sortable_MCLS);
		if($index === false) return $this;
		$peroperty = $this->Element_Sortable_MCLS[$index];
		$array = array();
		foreach ($this->Element_Sortable_MCLS as $key => $value) {
			if($key == $index) continue;
			array_push($array, $value);
		}
		array_push($array, $peroperty);
		$this->Element_Sortable_MCLS = $array;
		return $this;

	}

	function atFirst($name){
		$this->sortable();
		$index = array_search($name, $this->Element_Sortable_MCLS);
		if($index === false) return $this;
		$peroperty = $this->Element_Sortable_MCLS[$index];
		$array = array();
		array_push($array, $peroperty);

		foreach ($this->Element_Sortable_MCLS as $key => $value) {
			if($key == $index) continue;
			array_push($array, $value);
		}
		$this->Element_Sortable_MCLS = $array;
		return $this;
	}

	function add($name, $type = false, $replace = false){
		$this->sortable();
		$form = new $this;
		$frm = $type == false ? $name : $type;
		if(!$type){

			foreach ($frm as $key => $value) {
				if(!isset($this->$key) || $replace){
					$this->$key = $value;
					$k = array_search($key, $this->Element_Sortable_MCLS);
					if($k == false){
						array_push($this->Element_Sortable_MCLS, $key);
					}
				}
			}
			return $this;
		}else{
			$k = array_search($name, $this->Element_Sortable_MCLS);
			if(is_object($frm)){
				$this->$name = $frm;
				if($k === false){
					array_push($this->Element_Sortable_MCLS, $name);
				}
			}else{
				if(!isset($this->$name) || $replace){
					$this->$name = $form->make($type);
					if($k == false){
						array_push($this->Element_Sortable_MCLS, $name);

					}
				}
			}
			return $this->$name;
		}
	}

	function remove(){
		$args = func_get_args();
		if(is_array($args[0])){
			$black = $args[0];
		}elseif(count($args) > 1){
			$black = $args;
		}else{
			$black = preg_split("/([\.,\s\-])/", $args[0],-1, PREG_SPLIT_NO_EMPTY);
		}
		foreach ($black as $key => $value) {
			$k = array_search($value, $this->Element_Sortable_MCLS);
			if($k !== false){
				unset($this->Element_Sortable_MCLS[$k]);
				unset($this->$value);
			}
		}
		$this->sortable();
		return $this;

	}

	function white(){
		$args = func_get_args();
		if(is_array($args[0])){
			$white = $args[0];
		}elseif(count($args) > 1){
			$white = $args;
		}else{
			$white = preg_split("/([\.,\s\-])/", $args[0],-1, PREG_SPLIT_NO_EMPTY);
		}

		foreach ($this->Element_Sortable_MCLS as $key => $value) {
			if(!preg_grep("/^".$value."$/", $white)){
					unset($this->Element_Sortable_MCLS[$key]);
					unset($this->$value);
			}
		}
		$this->sortable();
		return $this;
	}
}
?>