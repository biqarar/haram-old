<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class controller extends main_controller{
	public $permission = array("tables" => array("insert" => array("public"),
						"update" => array("public")));
	function config(){
		$this->listen(array(
			"max" => 1,
			"url" => array("add")
			), array("tables", "option"));
		$this->listen(array(
			"max" => 2,
			"url" => array("edit" , "/^\d+$/")
			), array("tables", "option"));
	}
}
?>