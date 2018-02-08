<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class controller extends main_controller{
	public $permission = array("prerequisite" => array("insert" => array("public"),
						"update" => array("public")));
	public function config(){
		$this->listen(array(
			"max" => 1,
			"url"=>array("add")
			), array("prerequisite","option"));
		$this->listen(array(
			"max" => 2,
			"url"=>array("edit", "/^\d+$/")
			), array("prerequisite","option"));
	}
}
?>