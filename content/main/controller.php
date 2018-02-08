<?php
class main_controller{
	public $onUrl = false;
	public $__autocallMethod = array("sql", "redirect", "checkRedirect", "addMethod", "addPeroperty");
	public $__autogetProperty = array( "redirect");
	public $access = true; // main page the access must be true !!!!
	public final function __construct(){
		$permission = new checkPermission_cls;
		$permission->check();
		$this->querys = (object) "query";
		$this->addPeroperty('querys');
		$this->addMethod('tag');
		$this->addMethod('jTime');
		$this->addMethod('dateNow');
		$this->addMethod('uStatus');
		$this->addMethod('uId');
		if(method_exists($this, 'config')){
			$this->config();
		}
		list($access, $msg) = $this->checkPermissions();
		if(!$access) {
			if(ifAjax()){
				page_lib::access($msg);
			}else{
				$_SESSION['redirect'] = config_lib::$URL;
				// header("location:".host.'/portal/login');
				$this->redirect("portal/login");
			}
		}
		// exit();
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

	public function tag($tag = false) {
		return new tagMaker_lib($tag);
	}

	public function jTime() {
		return new jTime_lib;
	}

	public function dateNow() {
		return $this->jTime()->date("Ymd", false, false);
	}
	// reza mohiti
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
			// check Permission
			$session_permission = isset($_SESSION['my_user']['permission']['tables']) ? $_SESSION['my_user']['permission']['tables'] : false;
			$page_permission = $this->permission;
			$closeF = false;
			// var_dump($)
			foreach ($page_permission as $table => $aPermission) {
				if(!isset($session_permission[$table]))	break;
				$onT = array_keys($session_permission[$table]);
				foreach ($aPermission as $key => $value) {
					if(array_search($value, $onT) === false){
						$closeF = true;
						break;
					}
					else $access = true;
				}
				if($closeF) break;
			}
			$msg = "permission denide";
		}
		return array($access, $msg);
	}
}
?>