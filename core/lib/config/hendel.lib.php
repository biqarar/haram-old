<?php
class config_hendel_lib{
	// document root path
	static $path;

	// clean url without root path
	static $clean_url;

	// get method
	static $get;

	// array of url split by (/)
	static $a_url;

	// array of domain split by (.)
	static $a_domain;

	// array of subdomain split by (.)
	static $sub_domain;

	// content files
	static $content;

	// array of real url split by (/)
	static $real_url;

	function __construct(){
		if(defined("PATH")){
			self::$path = PATH;
		}else{
			self::$path = "/";
		}
		self::$content = content;
		
		$path = preg_replace("/^\./","",self::$path);
		
		$clean_url = $_SERVER['REQUEST_URI'];
		$clean_url = preg_replace("#^$path#", '', $clean_url);
		$clean_url = urldecode($clean_url);

		preg_match("/^([^?]*)(\?.*)?$/", $clean_url, $url);

		self::$get = (isset($url[2]))? preg_replace("/^\?$/", '', $url[2]) : '';
		self::$clean_url = $url[1];

		self::$a_url = self::$real_url = preg_split("[\/]", preg_replace("/^\/|\/$/", '', $url[1]), -1 , PREG_SPLIT_NO_EMPTY);
		$domain = $_SERVER['HTTP_HOST'];
		self::$a_domain = self::$sub_domain = preg_split("[\.]", $domain);
		array_pop(self::$sub_domain);
		array_pop(self::$sub_domain);
	}

	static function url_unshift($value){
		self::$clean_url = $value.self::$clean_url;
		array_unshift(self::$a_url, $value);
	}

	static function url_shift(){
		self::$clean_url = preg_replace("/^[^\/]*\//", "", self::$clean_url);
		array_shift(self::$a_url);
	}

	static function change_content($content){
		self::$content = root_dir.$content.'/';
	}
}
?>