<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller{

	function config(){
		
		// //------------------------------ course description
		// $this->listen(array(
		// 	"max" => 3,
		// 	"url" => array("description", "/^(add|edit)$/")
			
		// 	), 
		// 	function () {
		// 		save(array("course", "description"));
		// 		$this->permission = array("course_description" => array("insert" => array("public") , "update" => array("public")));
		// 	}
		// );

		//------------------------------ course description
		$this->listen(array(
			"max" => 4,
			"url" => array("courseclasses",  "status" => "add")
			
			), 
			function () {
				save(array("course", "courseclasses"));
				$this->permission = array("courseclasses" => array("insert" => array("public") , "update" => array("public")));
			}
		);

		//------------------------------ course description
		$this->listen(array(
			"max" => 3,
			"url" => array("courseclasses" , "apilist", "courseid" => "/^\d+$/")
			
			), 
			function () {
				save(array("course", "courseclasses" , "mod" => "apilist"));
				$this->permission = array("courseclasses" => array("select" => array("public")));
			}
		);


		//------------------------------ course description
		$this->listen(array(
			"max" => 4,
			"url" => array("courseclasses" , "apiadd", "courseid" => "/^\d+$/", "classesid" => "/^\d+$/")
			
			), 
			function () {
				save(array("course", "courseclasses" , "mod" => "apiadd"));
				$this->permission = array("courseclasses" => array("select" => array("public")));
			}
		);
		//------------------------------ course description
		$this->listen(array(
			"max" => 4,
			"url" => array("courseclasses" , "apidelete", "courseid" => "/^\d+$/", "classesid" => "/^\d+$/")
			
			), 
			function () {
				save(array("course", "courseclasses" , "mod" => "apidelete"));
				$this->permission = array("courseclasses" => array("select" => array("public")));
			}
		);
	}
}
?>