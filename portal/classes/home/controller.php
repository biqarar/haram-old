<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
class controller extends main_controller{

	function config(){
		//------------------------------ pending classes
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "manage")
			), 
			function () {
				save(array("classes", "manage"));
				$this->permission = array("classes" => array("delete" => array("public")));
			}
		);

		//------------------------------ pending classes
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "apimanage")
			), 
			function () {
				save(array("classes", "manage", "mod" => "apimanage"));
				$this->permission = array("classes" => array("delete" => array("public")));
			}
		);


		//------------------------------ pending classes
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "setdone", "classesid" => "/^\d+$/")
			), 
			function () {
				save(array("classes", "done", "mod" => "setdone"));
				$this->permission = array("classes" => array("delete" => array("public")));
			}
		);
		//------------------------------ pending classes
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "done", "classesid" => "/^\d+$/")
			), 
			function () {
				save(array("classes", "done"));
				$this->permission = array("classes" => array("delete" => array("public")));
			}
		);


		//------------------------------ pending classes
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "setrunning", "classesid" => "/^\d+$/")
			), 
			function () {
				save(array("classes", "running", "mod" => "setrunning"));
				$this->permission = array("classes" => array("delete" => array("public")));
			}
		);
		//------------------------------ pending classes
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "running", "classesid" => "/^\d+$/")
			), 
			function () {
				save(array("classes", "running"));
				$this->permission = array("classes" => array("delete" => array("public")));
			}
		);
		
	}
}
?>