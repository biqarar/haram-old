<?php
class query_cls {
	public final function sql($name = false){
		if(preg_match("/^(\.|@|#)/Ui", $name)){
			$args = func_get_args();
			return call_user_func_array(array(main_lib::$controller, 'sql'), $args);
		}
		$sql = new sqlMaker_lib();
		$querys = main_lib::$controller->querys;
		if($name){
			$querys->$name = $sql;
		}else{
			$name = preg_replace("/_cls$/", "", get_class($this));
			$sName = $name;
			$continue = true;
			$count = 0;
			do{
				if(!isset($querys->$sName)){
					$continue = false;
				}else{
					++$count;
					$sName = "{$name}_$count";
				}
			}while ($continue);

			$querys->$sName = $sql;
		}
		return $sql;
	}
	
	public function dateNow($type = "Ymd"){
		$time =  new jTime_lib;
		return $time->date($type, false, false);
		
	}
	
	public function db($string) {
		$db = new dbconnection_lib;
		return $db->query($string);
	}


	public function changeDate($date = false, $days = 0, $operator = "+") {
		$x =  new changeDate_cls;
		return $x->change($date, $days, $operator);
	}
	
	public function classification_finde_active_list($q = false) {
		 return 
		 $q->groupOpen()
		->condition("and", "#date_delete" , "is", "#null")
		->condition("or", "#because", "is", "#null")
		->groupClose();
	}

	// public function redirect($redirect = false, $exit = true, $php = false){
	// 	$redirectClass = new redirector_cls($redirect, $exit, $php);
	// 	$this->redirect = $redirectClass;
	// 	return $redirectClass;
	// }
}
?>