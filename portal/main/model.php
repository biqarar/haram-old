<?php
class main_model{
	public $plugin = true;
	public $commit = array();
	public $rollback = array();
	public $endProcess = false;
	public $dtable;
	public final function __construct($controller, $local = false){
		$this->dtable = new dtable;
		$this->controller = $controller;
		$sQl = new dbconnection_lib;
		$sQl->query("START TRANSACTION");
		if(isset($_POST['_post']) && method_exists($this, "post_$_POST[_post]")){
			$this->redirect(false, true, true);
			$sModel = "post_$_POST[_post]";
			#before config
			// $this->beforeConfig();
			#on config
			$this->$sModel();
			#after config
			// $this->afterConfig();

			$this->endEfect();

			if(isset($_POST['INPUT_MODEL_ID']) && isset($_SESSION['model'])){
				$KEY = array_search($_POST['INPUT_MODEL_ID'], $_SESSION['model']);
				unset($_SESSION['model'][$KEY]);
			}
			$array = debug_lib::compile();
			foreach ($array as $key => $value) {
				if(!is_array($value) || $key == 'msg') continue;
				foreach ($value as $k => $v) {

					$err = isset($v['error']) ? gettext($v['error']) : $v;
					if(!is_array($err) && preg_match("/^\[\[(update|insert|delete|select)\s(.*)\s(true|false|successful|failed)\]\]$/", $err, $parm)){
						$err = _($parm[1]).' '._($parm[2]).' '._($parm[3]);
					}elseif(is_string($v)){
						$array[$key][$k] = gettext($v);
						continue;
					}
					// elseif(preg_match("/\s/", $err)){
					// 	$new_err = "";
					// 	$err = preg_replace("/\'/", "", $err);
					// 	$split = preg_split("/\s/", $err);
					// 	foreach ($split as $key => $value) {
					// 		$new_err .= gettext($value) . " ";
					// 	}
					// 	$err = $new_err;
					// 	continue;
					// }

					if(isset($v['error'])){
						$array[$key][$k]['error'] = $err;
					}else{
						$array[$key][$k] = $err;
					}
				}
			}
			if(ifAjax()){
				header('Content-Type: application/json');
				echo json_encode($array);
			}elseif($this->redirect){
				if(!isset($_SESSION['error'])) $_SESSION['error'] = array();
				$_SESSION['error'][$this->redirect->compileUrl()] = $array;
				$this->checkRedirect();
			}
		}elseif(!$local){
			page_lib::access("model");
		}

	}

	public function endEfect(){
		$sQl = new dbconnection_lib;
		if($this->endProcess) return;
		$endEfect = (debug_lib::$status)? "commit" : "rollback";
		if(isset($this->$endEfect)){
			$aEndEfect = $this->$endEfect;
		}
		if(isset($this->$endEfect) && isset($aEndEfect[0]) && is_object($aEndEfect[0])){
			call_user_func_array($aEndEfect[0], array_slice($this->$endEfect, 1));
		}
		$sQl->query("$endEfect");
		$this->endProcess = true;
	}
	// public final function validate($name = false){
	// 	if(preg_match("/^(#|@|\.)/", $name)){
	// 		$validate = forms($name);
	// 		if(preg_match("/^#/", $name)){
	// 			if(isset($validate->attr['name']) && isset($validate->validate)){
	// 				$ret = array($validate->attr['name'] => call_user_func(array($this, 'post'), $validate->attr['name'], $validate->validate));
	// 			}
	// 		}else{
	// 			$ret = array();
	// 			foreach ($validate as $key => $value) {
	// 				if(isset($value->attr['name']) && isset($value->validate)){
	// 					$ret[$value->attr['name']] = call_user_func(array($this, 'post'), $value->attr['name'], $value->validate);
	// 				}
	// 			}
	// 		}
	// 		return (object) $ret;
	// 	}else{
	// 		return new FormsValidate_lib(func_get_args());
	// 	}
	// }

	public function sql_loadForm($table, $args){
		$sql = $this->sql();
		$sqlf = $sql::$table();
		return $sqlf->loadForm($args);
	}

	// public final function post($p, $valodator){
	// 	return new validate_lib(array($_POST, $p), $valodator,  'form');
	// }
	// public final function url($u, $valodator){
	// 	if(is_int($u)){
	// 		$url = config_lib::$surl;
	// 	}else{
	// 		$url = config_lib::$aurl;
	// 	}
	// 	return new validate_lib(array($url, $u), $valodator, 'url');
	// }

	public final function sql($name = false){
		$sql = new query_cls;
		$args = func_get_args();
		return call_user_func_array(array($sql, 'sql'), $args);
	}

	public final function commit(){
		$this->commit = func_get_args();
	}

	public final function rollback(){
		$this->rollback = func_get_args();
	}

	final function loadOnPlugin($name, $args){
		$this->loadBeforePlugin($name, $args);
		return call_user_func_array(array($this,$name), $args);
		$this->loadAfterPlugin($name, $args);
	}

	final function loadBeforePlugin($name, $args){
		if(!isset($this->plugin) || !$this->plugin) return;

	}

	final function loadAfterPlugin($name, $args){
		if(!isset($this->plugin) || !$this->plugin) return;
	}

	public function sms(){
		if(!$this->sms_api) $this->sms_api = new sms_lib();
		return call_user_func_array(array($this->sms_api, 'send'), func_get_args());
	}

	public function __call($name, $args){
		if(preg_grep('/^'.$name.'$/', $this->controller->__autocallMethod)){
			return call_user_func_array(array($this->controller, $name), $args);
		}
		if(preg_match("/^_sql_(.*)$/", $name, $method)){
			return $this->loadOnPlugin("sql_".$method[1], $args);
		}else{
			page_lib::core("invalid $name");
		}
	}

	public function __get($name){
		if(preg_grep('/^'.$name.'$/', $this->controller->__autogetProperty)){
			return $this->controller->$name;
		}else{
			return false;
		}
	}

	public function classification_finde_active_list($q = false) {
		 return 
		 $q->groupOpen()
		->condition("and", "#date_delete" , "is", "#null")
		->condition("or", "#because", "is", "#null")
		->groupClose();
	}
}
?>