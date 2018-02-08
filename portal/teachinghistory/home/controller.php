<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{
	function config(){
		$this->listen(array(
			"max" => 2,
			"url"=>array("status" => "add" , "usersid" => "/^\d+$/")
			), function(){
				save(array("teachinghistory","option"));
			$this->permission = array("teachinghistory" => array("insert" => array("public", "private")));
		});
	}
}
?>