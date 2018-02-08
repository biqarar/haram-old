<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller
{
	public $permission = array("consultation" => array("insert" => array("public"),
						"update" => array("public")));
	function config(){
		$this->listen(array(
			"max" => 1,
			"url" => array("add")), 
		function(){
			save(array("consultation", "option"));
			$this->permission = array("consultation" => array("insert"));
		});
		$this->listen(array(
			"max" => 2,
			"url" => array("edit")), 
		array("consultation", "option"));
		$this->listen(array(
			"max" => 1,
			"url" => array("list")), 
		array("consultation", "list"));
		$this->listen(array(
			"max" => 1,
			"url" => array("/^\d+$/")), 
		array("consultation"));

	}
}
?>