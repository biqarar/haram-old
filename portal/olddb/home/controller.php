<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */

class controller extends main_controller {

	public function config() {		
	
		$this->listen(array(
			"max" => 2,
			"url" => array("student", "id" => "/^\d+$/")
			),
			function (){
				save(array("olddb","student"));
				$this->permission = array("student" => array("select" => array("public", "private")));	
			});

		$this->listen(array(
			"max" => 2,
			"url" => array("price", "id" => "/^\d+$/")
			),
			function (){
				save(array("olddb","price"));
				$this->permission = array("oldprice" => array("select" => array("public", "private")));	
			});

		$this->listen(array(
			"max" => 2,
			"url" => array("classes", "id" => "/^\d+$/")
			),
			function (){
				save(array("olddb","classes"));
				$this->permission = array("oldclasses" => array("select" => array("public", "private")));	
			});
		$this->listen(array(
			"max" => 2,
			"url" => array("classification", "id" => "/^\d+$/")
			),
			function (){
				save(array("olddb","classification"));
				$this->permission = array("oldclassification" => array("select" => array("public", "private")));	
			});

		$this->listen(array(
			"max" => 2,
			"url" => array("certification", "id" => "/^\d+$/")
			),
			function (){
				save(array("olddb","certification"));
				$this->permission = array("oldsertification" => array("select" => array("public", "private")));	
			});

	}
}
?>