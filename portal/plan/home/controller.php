<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{
	function config(){
		//------------------------------ plan section (use in classification)
		// $this->listen(array(
		// 	"max" => 3,
		// 	"url" => array("section", "status" => "add")
		// 	), 
		// 	function () {
		// 		save(array("plan", "section"));
		// 		$this->permission = array("plan_section" => array("insert" => array("public")));
		// 	}
		// );

		// //------------------------------ plan section (use in classification)
		// $this->listen(array(
		// 	"max" => 3,
		// 	"url" => array("section", "status" => "edit", "id" => "/^\d+$/")
		// 	), 
		// 	function () {
		// 		save(array("plan", "section"));
		// 		$this->permission = array("plan_section" => array("update" => array("public")));
		// 	}
		// );
		//------------------------------ plan section (use in classification)
		$this->listen(array(
			"max" => 3,
			"url" => array("status" => "apisection")
			), 
			function () {
				save(array("plan", "list", "mod"=> "apisection"));
				$this->permission = array("plan_section" => array("select" => array("public")));
			}
		);
		//------------------------------ plan section (use in classification)
		$this->listen(array(
			"max" => 3,
			"url" => array("api", "id" => "/^\d+$/")
			), 
			function () {
				save(array("plan", "api", "mod" => "api"));
				$this->permission = array("price" => array("insert" => array("public"), "update" => array("public")));
			}
		);
	}
}
?>