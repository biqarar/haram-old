<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller{
	
	function config(){
		$this->listen(array(
			"max" => 5,
			"url" => array("progress", "id" => "/^\d+$/", "classesid" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "progress" ));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 3,
			"url" => array("certification", "usersid" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "certification" ));
			$this->permission = array("certification" => array("select" => array("public")));
		});


		$this->listen(array(
			"max" => 5,
			"url" => array("score","status" => "apilist", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "score" , "mod" => "api"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 5,
			"url" => array("score", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "score"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 5,
			"url" => array("status","status" => "apilist", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "status" , "mod" => "api"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 2,
			"url" => array("status", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "status"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 2,
			"url" => array("score", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "score"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 1,
			"url" => array("id" => "/^\d+$/")
			), function() {
			save(array("users", "learn"));
			$this->permission = array("person" => array("select" => array("public")));
		});
		
		$this->listen(array(
			"max" => 2,
			"url" => array("classes", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "classes"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 3,
			"url" => array("absence", "id" => "/^\d+$/", "classesid" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "absence"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 6,
			"url" => array( "listabsence", "status" => "xapi", "usersid" => "/^\d+$/", "classesid" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "absence" , "mod" => "api"));
			$this->permission = array("person" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 3,
			"url" => array("absence", "classes", "id" => "/^\d+$/")
			), function() {
			save(array("users", "learn", "absence" , "classes"));
			$this->permission = array("person" => array("select" => array("public")));
		});



	}
}
?>