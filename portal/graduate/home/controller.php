<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class controller extends main_controller{
	function config(){
		$this->listen(array(
			"max" => 1,
			"url" => array("add")
			), array("graduate", "option"));
		$this->listen(array(
			"max" => 2,
			"url" => array("edit" , "/^\d+$/")
			), array("graduate", "option"));
		$this->listen(array(
			"max" => 3,
			"url" => array("classes", "/^(add|edit)$/")
			), array("graduate", "classes"));
	}
}
?>