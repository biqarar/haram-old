<?php
/**
*
*/
class sql_cls {

	static $first = false;

	static function config($maker = false) {



	}

	static function call($maker, $name) {

		//------------------------------ send  users_id and branch_id to mysql engine
		$sql = new dbconnection_lib;

		//------------------------------ users id
		$users_id = isset($_SESSION['my_user']['id']) ? $_SESSION['my_user']['id'] : false;

		//------------------------------ ip
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;

		//------------------------------ send users id, branch id and ip to mysql engine
		if ($users_id && !self::$first) {
			$q = $sql->query("SET @users_id = $users_id ");
			$q = $sql->query("SET @ip_ = '$ip' ");
			self::$first = true;
		}
	}

	static function update_log($maker = flase, $condition = false) {
		$sql = new dbconnection_lib;
		$assoc = $sql->query("select * from `" .$maker->table . "` where " . $condition . " LIMIT 0,1");
		$old = $assoc->assoc();

		if($old && is_array($old)){
			foreach ($old as $key => $value) {
				if(isset($maker->set[$key])){
					if($value <> $maker->set[$key]){
						self::set_update_log($maker->table, $key, $value, $maker->set[$key], $maker->conditions[0]['value']);
					}
				}
			}
		}
	}

	static function set_update_log($table = false, $field =false, $old_value =false, $new_value =false, $record_id =false ){
		$sql = new dbconnection_lib;
		$new_value = preg_replace("/'|\#/", "", $new_value);

		if(isset($_SESSION['my_user']['id'])){

		$assoc = $sql->query("INSERT INTO `quran_hadith_log`.`update_log`
			SET
			`users_id` = '". $_SESSION['my_user']['id'] ."' ,
			`table` = '$table',
			`field` = '$field',
			`record_id` = '$record_id' ,
			`old_value` = '$old_value',
			`new_value` = '$new_value'");
		// $sql->query("COMMIT");
		}

	}

	static function trash($maker = false, $condition = false) {
		$sql = new dbconnection_lib;
		$assoc = $sql->query("select * from `" .$maker->table . "` where " . $condition . " LIMIT 0,1");
		$trash = $assoc->assoc();
		// var_dump("select * from `" .$maker->table . "` where " . $condition . " LIMIT 0,1");
		$meta = "";
		if(!is_array($trash))
		{
			return false;
		}

		foreach ($trash as $key => $value) {
			if($key == "id") $id = $value;
			$meta .= $key . ":" . $value  .", ";
		}
		if(isset($_SESSION['my_user']['id'])){

			$assoc = $sql->query("INSERT INTO `quran_hadith_log`.`trash`
				SET
				`tables` = '".$maker->table."',
				`users_id` = '". $_SESSION['my_user']['id'] ."' ,
				`record_id` = '$id',
				`meta`  = '$meta'");
			// $sql->query("COMMIT");

		}
	}

}
?>
