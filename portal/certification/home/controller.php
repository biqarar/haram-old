<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class controller extends main_controller{
	
	function config(){
		// certification/status=insertapi/classificationid=
		//------------------------------ pending classes
		$this->listen(array(
			"max" => 3,
			"url" => array("status" => "insertapi", "classificationid" => "/^\d+$/")
			), 
			function () {
				save(array("certification", "option", "mod" => "insertapi"));
				$this->permission = array("certification" => array("insert" => array("public")));
			}
		);

		//------------------------------ pending classes
		$this->listen(array(
			"max" => 3,
			"url" => array("apichange","certificationid" => "/^\d+$/", "type" => "/^(setdateprint)|(setdatedeliver)|(deletedateprint)|(deletedatedeliver)$/")
			), 
			function () {
				save(array("certification", "api", "mod" => "apichange"));
				$this->permission = array("certification" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		//------------------------------ pending classes
		$this->listen(array(
			"max" => 3,
			"url" => array("report","type" => "/^(all)|(request)|(print)|(deliver)$/")
			), 
			function () {
				save(array("certification", "report"));
				$this->permission = array("certification" => array("select" => array("public")));
			}
		);
	}
}
?>




