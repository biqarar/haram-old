<?php
class validateExtends_cls{

	public function reg($reg = false) {
		if (preg_match($reg, $this->value)) {
			return true;
		}else{
			return false;
		}
	}
	
	public function id() {
		if ($this->value == 'null' or preg_match("/^\d+$/", $this->value)) {
			return true;
		}else{
			return false;
		}
	}

	public function date() {
		if ($this->value == null) return true;	
		
		// if (!preg_match("/^(13|14)([0-9][0-9])(\/|-)?(((0?[1-6])(\/|-)?((0?[1-9])|([12][0-9])|(3[0-1])))|(((0?[7-9])|(1[0-2]))(\/|-)?((0?[1-9])|([12][0-9])|(30))))$/", $this->value, $date)) {
		if (!preg_match("/^(\d{4})(\-|\/|)(\d{1,2})(\-|\/|)(\d{1,2})$/", $this->value, $date)) {

			return false;
		}else{
			$this->value = $date[1]
			.
			((intval($date[3]) < 10) ? "0".intval($date[3]) : intval($date[3]))
			.
			((intval($date[5]) < 10) ? "0".intval($date[5]) : intval($date[5]));
		}
		return true;
	}


	public function password() {
		if (!preg_match("/^(.*){6,32}$/", $this->value)) {
			return false;
		}else{
			$this->value = md5($this->value);
		}
		return true;
	}


	public function fn($fn) {
		$args = func_get_args();
		$debug = call_user_func_array($fn, array_slice($args, 1));
		if (!$debug) {
			return false;
		}
		return ture;
	}

	public function nationalcode() {

		if(
			!isset(validator_lib::$save["form"]['nationality']) || 
			       validator_lib::$save["form"]['nationality']->value != '97')
		{
			if (preg_match("/\d/", intval($this->value))) {
				return true;
			}else{
				return false;
			}
		}else{
			
			$code = $this->value;
			$r = false;
			if (strlen($code) == 10) {
				$c = str_split($code);
				$main_place = array();
				$i = 10;
				$p = 0;
				foreach ($c as $n => $value) {
					$main_place[$i] = $value;
					if ($i != 1) {
						$p = $p + ($value * $i);
					}
					$i--;
				}
				$ba = fmod($p, 11);
				if ($ba < 2) {
					if ($main_place[1] == $ba) {
						$r = true;
					}else{
						$r = false;
					}
				}else{
					if ($main_place[1] == (11 - $ba)) {
						$r = true;
					}else{
						$r = false;
					}
				}
			}
			if ($r) {
				$this->value = $code;
				return true;
			}else{
				return false;
			}
		}
	}

	public function number($a = false, $b = false) {

		if($this->value == '' || $this->value === 0 ) return true;
		
		$a = (preg_match("/^\d+$/", $a)) ? $a : false;
		$b = (preg_match("/^\d+$/", $b)) ? $b : false;

		if($a === false && $b === false){
			$reg = "/^\d+$/";
		}elseif($a && $b === false) {
			$reg = "/^\d{" . $a . "}$/";
		}elseif($a && $b){
			$reg = "/^\d{" . $a . "," . $b . "}$/";
		}

	
		if (preg_match($reg, $this->value)) {
			$this->value = intval($this->value);
			return true;
		}else{
			return false;
		}
	}

	public function float(){

		if($this->value == '') return true;
		// ilog(gettype($this->value). " : ". $this->value . "\n");
		// exit();
		if (preg_match("/^((\d+\.?\d+)|(\d+)|(\d))$/", $this->value) || gettype($this->value) == "duble") {
			$this->value = floatval($this->value);
			return true;
		}else{
			return false;
		}
	}

	public function description($max = false) {
		if($this->value == '') return true;
		

		$this->value = trim($this->value);
		$this->value = preg_replace("/\s+/", " ", $this->value);

		$max = ($max) ? intval($max) : false;

		$allCharset = ".";
		if($max && $max != -1){
			
			$reg = "/^" . $allCharset . "{3," . $max . "}$/";
		}elseif($max == -1){
			$reg = "/(.*)/";
		}else{
			$reg = "/^" . $allCharset . "{3,255}$/";
		}
		if (preg_match($reg, $this->value)) {
			return true;
		}
		return false;
	}


	public function farsi($min = false, $max = false) {
		
		if($this->value == '') return true;
		
		$min  = (preg_match("/^\d+$/", $min)) ? $min : false;
		$max  = (preg_match("/^\d+$/", $max)) ? $max : false;

		$this->value = trim($this->value);

		$this->value = preg_replace("/\s+/", " ", $this->value);

		$str_check = preg_replace("/\s/", "", $this->value);

		$fa = "[ضصثقفغعهخحجچشسیبلاتنمکگظطزرذدپوًٌٍَُِّْؤئيإأآةكٓژٰ‌ٔء﷼ةإأيئؤك]";

		if(!$min && !$max){
			$reg = "/^" . $fa . "+$/";
		}elseif($min && !$max) {
			$reg = "/^" . $fa . "{" . $min . "}$/";
		}elseif($min && $max){
			$reg = "/^" . $fa . "{" . $min . "," . $max . "}$/";
		}
		
		if(preg_match($reg, $str_check)) {
			return true;
		}
		return false;
	}

	public function email() {
		if ($this->value == null) return true;
		$reg = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9_.+-]+\.[a-zA-Z0-9-.]+$/";
		if (preg_match($reg, $this->value)) {
			return true;
		}else{
			return false;
		}
	}

	public function price() {
		return true;
	}

	public function username() {
		return true;
	}

	public function time() {
		return true;
	}

	public function transactions() {
		return true;
	}
}
?>