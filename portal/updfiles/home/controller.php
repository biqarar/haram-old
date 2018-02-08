<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{
	function config(){
		if(count(config_lib::$aurl) != 2 ){
			page_lib::page("updfile file not found");
		}elseif(!preg_match("/^\d{4}$/", config_lib::$aurl[0]) || !preg_match("/^(\d{10})\.(.{2,6})$/", config_lib::$aurl[1], $file)){
			page_lib::page("updfile file not found");
		}else{
			$path = config_lib::$aurl[0];
			$file = $file[1];
			header("Content-Type: image/jpeg");
			echo file_get_contents(query_files_cls::getAddr()."$path/$file");
		}
		exit();
	}
}
?>