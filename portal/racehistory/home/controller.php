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
				save(array("racehistory","option"));
			$this->permission = array("racehistory" => array("insert" => array("public", "private")));
		});
	}
}
?>