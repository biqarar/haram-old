<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller
{
	function config(){
		$this->listen(array(
			"max" => 1,
			"url" => array("add")), 
		array("posts", "tags"));
		$this->listen(array(
			"max" => 2,
			"url" => array("edit", "/^\d+$/")
			), array("posts", "tags"));
	}
}
?>