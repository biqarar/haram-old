<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{

	function config(){
		
		//------------------------------ education api to get list of education
		$this->listen(array(
			"max"=>2,
			"url"=>array("api", "/^.+$/")
		), function () {
			save(array("method" => "api", "mod" => "list"));
			$this->access = true;
		});

		//------------------------------ education api to get list of education
		$this->listen(array(
			"max"=>2,
			"url"=>array("users", "usersid" => "/^\d+$/")
		), function () {
			save(array("education", "users"));
			$this->access = global_cls::supervisor();
		});
	}
}
?>