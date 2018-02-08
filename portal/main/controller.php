<?php
class main_controller{
	public $onUrl = false;
	public $__autocallMethod = array("sql", "redirect", "checkRedirect", "addMethod", "addPeroperty");
	public $__autogetProperty = array( "redirect");
	public $access = false; // for after lunch

	public final function __construct(){

		if(global_cls::supervisor()){
			$this->access = true; // for befor lunch
		}

		// if(preg_match("/favicon\.ico$/", $_SERVER['REQUEST_URI'])){
		// 	die("favicon.ico");
		// }

		$this->xuStatus();

		$permission = new checkPermission_cls;
		$permission->check();
		$this->querys = (object) "query";
		$this->addPeroperty('querys');
		$this->addMethod('tag');
		$this->addMethod('login');
		$this->addMethod('branch');
		$this->addMethod('post_branch');
		$this->addMethod('jTime');
		$this->addMethod('dateNow');
		$this->addMethod('xuStatus');
		$this->addMethod('urlStatus');
		$this->addMethod('uStatus');
		$this->addMethod('uId');
		$this->addMethod('xuId');
		$this->addMethod('db');
		$this->addMethod('SESSION_usersid');
		$this->addMethod("checkPermissions");
		$this->addMethod('changeDate');
		if(method_exists($this, 'config')){
			$this->config();
		}
		list($access, $msg) = $this->checkPermissions();
		// var_dump($access, $msg);
		if(!$this->SESSION_usersid() && config_lib::$class != "login") {
			if(ifAjax()){
				page_lib::access($msg);
			}else{
				$_SESSION['redirect'] = config_lib::$URL;
				header("location:".host.'/login');
				exit();
			}
		}elseif($this->SESSION_usersid()  && config_lib::$class == "login"){
			// 	var_dump("fufc");exit();
			// header("location:".host);
			// exit();
		}

		if(!$access) {
			if(ifAjax()){
				page_lib::access($msg);
			}else{
				page_lib::access($msg);
			}
		}
	}

	public final function hendel(){
		$sMod = config_lib::$mod;
		$this->$sMod = new $sMod($this);
	}

	public final function addMethod($name){
		array_push($this->__autocallMethod, $name);
	}

	public final function addPeroperty($name){
		array_push($this->__autogetProperty, $name);
	}

	public final function sql($name){
		preg_match("/^(\.|@|#)?([a-z0-9_]*|([a-z0-9_]+)\.([a-z0-9_]+))$/Ui", $name, $type);
		$args = func_get_args();
		$args = array_splice($args, 1);

		if($type[1] == "@"){
			return call_user_func_array(array($this->model(), 'sql_'.$type[2]), $args);
		}elseif($type[1] == "." || count($type) == 5){
			$sClass = (count($type) == 5) ? $type[3] : $type[2];
			$qClass = "query_".$sClass.'_cls';
			$qMethod = (count($type) == 5) ? $type[4] : 'config';
			return call_user_func_array(array(new $qClass, $qMethod), $args);
		}elseif($type[1] == "#"){
			$qMethod = "sql_{$type[2]}";
			return call_user_func_array(array($this->model(), $qMethod), $args);
		}

	}

	public final function model(){
		if(!isset($this->model)){
			$this->model = new model($this, true);
		}
		return $this->model;
	}
	public final function view(){
		if(!isset($this->view)){
			$this->view = new view($this, true);
		}
		return $this->view;
	}

	public final function redirect($redirect = false, $exit = true, $php = false){
		$redirectClass = new redirector_cls($redirect, $exit, $php);
		$this->redirect = $redirectClass;
		return $redirectClass;
	}

	public final function checkRedirect(){
		if(isset($this->redirect) && is_object($this->redirect)){
			$this->redirect->redirect();
		}
	}

	public final function listen($cond, $callback = false){
		if(is_array($cond)){
			$listen = new listen_cls($cond);
			$cond = $listen->cond;
		}
		if($cond){
			$this->onUrl = true;
			if(gettype($callback) == 'object'){
				$args = func_get_args();
				$this->CallBackFunc = $callback;
				call_user_func_array($this->CallBackFunc, array_splice($args, 2));
			}else if(is_array($callback)){
				save($callback);
			}
		}
	}



	public function changeDate($date = false, $days = 0, $operator = "+") {
		$x =  new changeDate_cls;
		return $x->change($date, $days, $operator);
	}

	public function tag($tag = false) {
		return new tagMaker_lib($tag);
	}

	public function jTime() {
		return new jTime_lib;
	}

	/**
	* @return date whit 8 number
	* @example 13930801
	*/

	public function dateNow($_format = "Ymd")
	{
		return $this->jTime()->date($_format, false, false);
	}

	public function db($string) {
		$db = new dbconnection_lib;
		return $db->query($string);
	}


	/**
	* check url and permisson
	* @example url = status=add then permission musb be "insert=public"
	*/
	public function xuStatus($c = false) {
		$URL    = config_lib::$URL;
		$aurl   = config_lib::$aurl;
		$surl   = config_lib::$surl;
		$table  = preg_split("/\//", $URL);
		$table  = $table[0];
		$return = array();
		$return['table'] = $table;
		if($c){
			$return[$c] = isset($surl[$c]) ? $surl[$c] : false;
		}else{
			$return['surl'] = $surl;
		}
		//------------------------------ check url

		$insertORupdate = (isset($surl['status']) && $surl['status'] == 'add') ? "insert" : "update";
		$status = isset($surl['status']) ? $surl["status"] : false;

		//------------------------------ if url is "status=add"

		if(isset($surl['status']) && ($surl['status'] == 'add')){

			$this->listen(
				array(
					"max" => 1,
					'url' => array("status" => "$status")
					),
				function($table, $insertORupdate){
					save(array("$table", "option"));
					$this->permission = array("$table" => array("$insertORupdate" => array("public")));
				}, $table , $insertORupdate
				);

		//------------------------------ if url is "status=edit"

		}elseif (isset($surl['status']) && $surl['status'] == 'edit') {

			$this->listen(
				array(
					"max" => 2,
					'url' => array("status" => "$status", "id" => "/^(\d+)$/")
					),
				function($table, $insertORupdate){
					save(array("$table", "option"));
					$this->permission = array("$table" => array("$insertORupdate" => array("public")));
				}, $table , $insertORupdate
				);

		//------------------------------ if url is "status=list"

		}elseif(isset($surl['status']) && $surl['status'] == 'list' ){

			$this->listen(
				array(
					"max" => 3,
					'url' => array("status" => "$status")
					),
				function($table){
					save(array("$table", "list"));
					$this->permission = array("$table" => array("select" => array("public")));
				}, $table
				);

		//------------------------------ if url is "status=detail"

		}elseif(isset($surl['status']) && $surl['status'] == 'detail' ){

			$this->listen(
				array(
					"max" => 2,
					'url' => array("status" => "$status", "id" => "/^(\d+)$/")
					),
				function($table){
					save(array("$table", "detail"));
					$this->permission = array("$table" => array("select" => array("public")));
				}, $table
				);
		}elseif(isset($surl['status']) && $surl['status'] == 'api' ){
			$this->listen(
				array(
					"max" => 4,
					'url' => array("status" => "$status")
					),
				function($table){
					save(array("$table", "list", 'mod' => "api"));
					$this->permission = array("$table" => array("select" => array("public")));
				}, $table
				);
		}
	}

	/**
	* @return string
	*/
	public function urlStatus() {
		return (isset(config_lib::$surl['status'])) ? config_lib::$surl['status']: "add";
	}

	/**
	* @return number
	* return url id
	* @example if url = status=edit/id=10 then return 10;
	*/
	public function xuId($id = "id") {
		return (isset(config_lib::$surl[$id])) ? urldecode(config_lib::$surl[$id]): 0;
	}

	public function uStatus($index = 1, $url = false, $url2 = false){
		if(is_bool($index)) {
			$url = true;
			$url2 = $url ? true : false;
			$index = 1;
		}
		$ret = isset(config_lib::$aurl[$index]) ? config_lib::$aurl[$index] : '' ;
		if($url){
			$ret .= isset(config_lib::$aurl[$index+1]) ? '/'.config_lib::$aurl[$index+1] : '';
		}
		if($url2){
			$ret .= isset(config_lib::$aurl[$index+2]) ? '/'.config_lib::$aurl[$index+2] : '';
		}
		return $ret;
	}


	public function uId($index = 2){
		return (isset(config_lib::$aurl[$index])) ? intval(config_lib::$aurl[$index]) : false ;
	}

	public function permissionStatus() {
		if($this->uStatus() == 'add') return "insert";
		if($this->uStatus() == 'edit') return "update";

	}

	public function checkPermissions() {
			// var_dump($this->access);
		// var_dump(config_lib::$class, config_lib::$method );

		if(global_cls::supervisor()){
			return [true, true];
		}

		$msg = "";
		$access = false;
		if($this->access) {
			// For Login page
			$access = true;
		}elseif (!isset($this->access)) {
			// Developer bug !!!
			$access = false;
			$msg = "access not found";
		}elseif (!isset($this->permission)) {
			// Developer bug !!!
			$access = false;
			$msg = "permission not set";

		}else{
			// check permission
			$session_permission = isset($_SESSION['my_user']['permission']['tables']) ? $_SESSION['my_user']['permission']['tables'] : false;
			$page_permission = $this->permission;
			$closeF = false;
			foreach ($page_permission as $table => $oprator) {
				if(isset($session_permission[$table])){
					foreach ($oprator as $op => $public_private) {
						foreach ($public_private as $key => $value) {
							if(isset($session_permission[$table][$op]) && $session_permission[$table][$op]==$value){
								$access = true;
							}
							if($closeF) break;
						}
						if($closeF) break;
					}
				}
				if($closeF) break;
			}
			$msg = "permission denide";
		}
		return array($access, $msg);
	}

	public function SESSION_usersid() {
		return ($this->login()) ? $_SESSION['my_user']['id'] : 0;
	}

	public function login($arg = false) {
		if(isset($_SESSION['my_user']['id'])){
			if($arg && $arg != "all" && $arg != "*"  && $arg != "select_branch") {
				return $_SESSION['my_user'][$arg];
			}elseif($arg && ($arg == "all" || $arg == "*")){
				return $_SESSION['my_user'];
			}elseif($arg && $arg == "select_branch"){

				return
				(isset($_SESSION['my_user']['branch']['selected']) &&
				 !empty($_SESSION['my_user']['branch']['selected'])) ? true :false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	public function branch($arg = false) {
		return $this->sql(".branch.check", $arg);
	}

	public function post_branch() {
		return $this->sql(".branch.post_branch");
	}
}

class dtable{

	function __call($name, $args){
		$this->$name = count($args) > 1 ? $args : $args[0];
		return $this;
	}
}
?>