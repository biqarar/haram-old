<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class controller extends main_controller{
	public $permission = array("regulation" => array("insert" => array("public"),
						"update" => array("public")));
	
	function config(){
		$this->listen(array(
			"max" => 1,
			"url" => array("add")
			), array("regulation", "option"));
		$this->listen(array(
			"max" => 2,
			"url" => array("edit" , "/^\d+$/")
			), array("regulation", "option"));
		$this->listen(array(
			"max" => 1,
			"url" => ""
			), function(){
			save(array("regulation", "home"));
			$this->access = global_cls::supervisor();
		});
	}
}
?>