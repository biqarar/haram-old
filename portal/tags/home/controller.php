<?php
/**
* @author reza mohitit rm.biqarar@gmail.com
*/
class controller extends main_controller{
	public $permission = array("tags" => array("insert" => array("public"),
						"update" => array("public")));
	function config(){
		$this->listen(array(
			"max" => 1,
			"url"=>array("add")
			), array("tags","option"));
		$this->listen(array(
			"max" => 2,
			"url"=>array("edit", "/^\d+$/")
			), array("tags","option"));
	}
}
?>