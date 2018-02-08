<?php
class page_lib{
	public static function string($code){
		$error = array();
		$error[400] = 'BAD REQUEST';
		$error[401] = 'UNAUTHORIZED';
		$error[403] = 'FORBIDDEN';
		$error[404] = 'NOT FOUND';
		$error[405] = 'METHOD NOT ALLOWED';
		$error[408] = 'REQUEST TIME OUT';
		$error[410] = 'GONE';
		$error[411] = 'LENGTH REQUIRED';
		$error[412] = 'PRECONDITION FAILED';
		$error[413] = 'REQUEST ENTITY TOO LARGE';
		$error[414] = 'REQUEST URI TOO LARGE';
		$error[415] = 'UNSUPPORTED MEDIA TYPE';
		$error[500] = 'INTERNAL SERVER ERROR';
		$error[501] = 'NOT IMPLEMENTED';
		$error[502] = 'BAD GATEWAY';
		$error[503] = 'SERVICE UNAVAILABLE';
		$error[506] = 'VARIANT ALSO VARIES';
	}
	public static function page($str){
		$class = debug_backtrace(true);
		self::make("PAGE NOT FOUND($str)", $class, 404);
	}

	public static function core($str){
		$class = debug_backtrace(true);
		self::make("CORE NOT FOUND($str)", $class, 404);
	}

	public static function access($str){
		$class = debug_backtrace(true);
		self::make("ACCESS DENIED($str)", $class, 403);
	}

	public static function internal($str){
		$class = debug_backtrace(true);
		self::make("INTERNAL ERROR($str)", $class, 500);
	}
	public static function make($str, $obj, $status){

		header("HTTP/1.0 $status ".self::string($status));

		$error = preg_replace("/^(.*)\((.*)\)$/", "$2", $str);

		if(DEBUG){
		$error = "<pre>$str";

			foreach ($obj as $key => $value) {
				if(array_key_exists('file', $obj[$key])){
					$error .= "\n\tat ".$obj[$key]['file'].':'.$obj[$key]['line'];
				}
			}
			$error .= "\n";
			echo $error;

		}else{

			$hadith = self::hadith();
			$hadith = "";

			require_once "404.php";
			
		}

		exit();
	}

	public static function hadith() {

		$hadith = array(
			
			"سلام النظافته منا\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			"سلام النظافته من\n لایمانیهسیبیبیسب",
			
			
			);
		return $hadith[rand(0,5)];
	}
}

?>