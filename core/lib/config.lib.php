<?php
class config_lib{
	static $get;
	static $allurl;
	
	// store all of url without any remove
	static $URL;
	static $url;
	static $method;
	static $class;
	static $mod;
	static $notget;

	// config_lib::$surl['edit']
	// store passed parameter like jibres.com/edit=23
	static $surl;

	// other parameter in url
	static $aurl;
	static $child = false;
	static $sClass;
	static $sMethod;
	static $sChild;
	static $subdomain;
	static $aSubdomain;
	// ******************** Javad
	static $class_real;
	static $method_real;
	static $child_real;
	// ******************** Javad

	static function config(){
		// hendel of config
		new config_hendel_lib;
		/**
		 * before default config
		 */
		if(autoload::hendel('config_cls', true)){
			autoload::hendel('config_cls');
			if(method_exists("config_cls", "_before")){
				config_cls::_before();
			}
		}

		/**
		 * default config
		 */
		self::get();
		self::url();
		self::child();
		self::method();
		self::cls();
		self::surl();
		self::mod();
		$sClass = false;
		$sMethod = false;
		$sChild = false;

		/**
		 * after default config
		 */
		if(class_exists("config_cls")){
			if(method_exists("config_cls", "_after")){
				config_cls::_after();
			}
		}
	}

	static function get(){
		$domain = $_SERVER['HTTP_HOST'];
		if(defined("MAIN_DOMAIN")){
			$domain = preg_replace("/(www\.)?".MAIN_DOMAIN."$/", "", $domain);
			if($domain !=""){
				$domain = preg_replace("/\.$/", "", $domain);
				self::$aSubdomain = preg_split("[\.]", $domain, -1, PREG_SPLIT_NO_EMPTY);
				self::$subdomain = $domain;
			}
		}
		self::$allurl = config_hendel_lib::$a_url;
		self::$URL = config_hendel_lib::$clean_url;
		self::$get = config_hendel_lib::$get;
	}

	static function url(){
		if(count(self::$allurl) > 3){
			$urls = array_slice(self::$allurl, 3);
			self::$url = join($urls,'/');
			self::$allurl = array_slice(self::$allurl, 0,3);
		}
	}

	static function child(){
		if(count(self::$allurl) == 3){
			self::$child = self::$allurl[2];
			if(preg_match("[=]", self::$allurl[2])){
				self::changeChild();
			}
			self::$allurl = array_slice(self::$allurl, 0, 2);
		}else{
			self::$child = '';
		}
	}

	static function method(){
		if(count(self::$allurl) == 2 && !empty(self::$allurl[1])){			
			if(self::$allurl[1] == 'home' && self::$url =='' && self::$child == ''){
				page_lib::page("method::home");
			}
			self::$method = self::$allurl[1];
			if(preg_match("[=]", self::$allurl[1])){
				self::changeChild();
				self::changeMethod();
			}
			self::$allurl = array_slice(self::$allurl, 0, 1);
		}else{
			self::$method = 'home';
		}
	}

	static function cls(){
		if(count(self::$allurl) == 1){
			if(self::$allurl[0] == 'home' && self::$method == 'home'){
				page_lib::page("class::home");
			}
			self::$class = self::$allurl[0];

			if(preg_match("[=]", self::$allurl[0])){
				self::changeChild();
				self::changeMethod();
				self::changeClass();
			}
			self::$allurl = null;
		}else{
			self::$class = 'home';
		}
	}

	static function surl(){
		$s = preg_split("[\/]", self::$url);
		self::$aurl = (self::$url !='')? $s : array();
		$s2 = array();
		foreach ($s as $key => $value) {
			preg_match("/^([^=]*)(=(.*))?$/", $value, $ss);
			$s2[$ss[1]] = (isset($ss[3]))? $ss[3] : null;
		}
		self::$surl = (self::$url !='')? $s2 : array();
	}

	static function mod(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			self::$mod = 'model';
		}else if($_SERVER['REQUEST_METHOD'] == 'GET'){
			self::$mod = 'view';
		}else{
			page_lib::access("method is false");
		}
	}

	static function makeurl($p){
		if($p != 'home'){
			self::$url = (self::$url !='')? $p.'/'.self::$url : $p;
			self::surl();
		}
	}

	static function changeChild($str = ''){
		if(self::$child != ''){
			self::$url = (self::$url !='')? self::$child.'/'.self::$url : self::$child;
		}
		self::$sChild = self::$child;
		self::$child = $str;
		self::surl();
	}

	static function changeMethod($str = 'home'){
		if(self::$method != 'home'){
			self::$url = (self::$url !='')? self::$method.'/'.self::$url : self::$method;
		}
		self::$sMethod = self::$method;
		self::$method = $str;
		self::surl();
	}

	static function changeClass($str = 'home'){
		if(self::$class != 'home'){
			self::$url = (self::$url !='')? self::$class.'/'.self::$url : self::$class;
		}
		self::$sClass = self::$class;
		self::$class = $str;
		self::surl();
	}

	static function listen(){
		$args = func_get_args();
		$listen = new config_listen;
		call_user_func_array(array($listen, "conditions"), $args);
		if ($listen->cond && isset($args[1])) {
			if(is_object($args[1])){
				call_user_func_array($args[1], array());
			}
		}
		return $listen;
	}
	static function real($index){
		switch ($index) {
			case 'class':
			$index = 0;
			break;
			case 'method':
			$index = 1;
			break;
			case 'child':
			$index = 2;
			break;
		}

		return isset(config_hendel_lib::$real_url[$index]) ? config_hendel_lib::$real_url[$index] : false;
	}
}

class post{
	public static function __callStatic($name, $args){
		$post =  isset($_POST[$name]) ? $_POST[$name] : false;
		$patterns = array(
			'/ة/',
			'/إ/',
			'/أ/',
			'/ي/',
			// '/ئ/',
			'/ؤ/',
			'/ك/',

			'/۰/',
			'/۱/',
			'/۲/',
			'/۳/',
			'/۴/',
			'/۵/',
			'/۶/',
			'/۷/',
			'/۸/',
			'/۹/'
			);
		$replacements = array(
			'ه',
			'ا',
			'ا',
			'ی',
			// 'ی',
			'و',
			'ک',

			'0',
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9'
			);
		if($post && !is_array($post)) {
			$post = preg_replace($patterns, $replacements, $post);
		}
		return $post;
	}
}
class get{
	public static function __callStatic($name, $args){
		return isset($_GET[$name])? $_GET[$name] : false;
	}
}

class config_listen{
	public $cond = true;

	public function conditions($conditions){
		foreach ($conditions as $key => $value) {
			if(method_exists($this, $key)){
				$this->$key($value);
			}
		}
	}


	function url($url_Parameters){
		$this->parametersCaller($url_Parameters, config_hendel_lib::$a_url);
	}

	function sub_domain($sub_domain_Parameters){
		$this->parametersCaller($sub_domain_Parameters, config_hendel_lib::$sub_domain, '.');
	}

	function domain($domain_Parameters){
		$this->parametersCaller($domain_Parameters, config_hendel_lib::$a_domain, '.');
	}

	function parametersCaller($parameters, $array, $join = "/"){
		if(!is_array($parameters)){
			$this->check_parameters($parameters, join($array, $join));
			return;
		}
		$k = 0;
		foreach ($parameters as $key => $value) {
			if(!isset($array[$key])){
				$this->cond = false;
				break;
			}
			$this->check_parameters($value, $array[$key]);
			$k++;
		}
	}

	function check_parameters($reg, $value){
		if(preg_match("/^(\/.*\/|#.*#|[.*])[gui]{0,3}$/i", $reg)){
			if(!preg_match($reg, $value)){
				$this->cond = false;
			}
		}elseif($reg != $value){
			$this->cond = false;
		}
	}
}

?>