<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{

	function config(){
		//------------------------------ group expert (for insert course)
		$this->listen(array(
			"max" => 3,
			"url" => array("progress", "id" => "/^\d+$/")
			), function () {
			save(array("group", "progress"));
			$this->permission = array("group" => array("select" => array("public"), "update" => array("public")));
		});
		//------------------------------ group expert (for insert course)
		$this->listen(array(
			"max" => 3,
			"url" => array("expert", "/^(add|edit)$/")
			), function () {
			save(array("group", "expert"));
			$this->permission = array("group_expert" => array("insert" => array("public"), "update" => array("public")));
		});

		//------------------------------ group list
		// $this->listen(array(
		// 	"max" => 3,
		// 	"url" => array("list", "/^(add|edit)$/")
		// 	), function () {
		// 	save(array("group", "list"));
		// 	$this->permission = array("group_list" => array("insert" => array("public"), "update" => array("public")));
		// });
	}
}
?>