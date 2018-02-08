<?php
class controller extends main_controller{

	function config(){
		$this->listen(array(
			"max" => 3,
			"url" => array("report","id"=> "/^\d+$/")
			),
			function () {
				save(array("classification", "report"));
				$this->permission = array("classification" => array("select" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("progress","id"=> "/^\d+$/")
			),
			function () {
				save(array("classification", "progress"));
				$this->permission = array("classification" => array("select" => array("public")));
			}
		);
		//------------------------------ load form to search in person // apipriceback apiclassification
		$this->listen(array(
			"max" => 3,
			"url" => "/^(search)\/(classesid\=\d+)$/"
			),
			function () {
				save(array("classification", "search"));
				$this->permission = array("classification" => array("insert" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("returnclasses","id" =>  "/^\d+$/")
			), function () {
			save(array("classification", "returnclasses"));
			$this->permission = array("classification" => array("insert" => array("public")));
		});

		$this->listen(array(
			"max" => 3,
			"url" => array("classes", "/^\d+$/", "/^\d+$/")
			), function () {
			save(array("classification", "api", "classification"));
			$this->permission = array("classification" => array("insert" => array("public")));
		});

		//------------------------------ classification list
		$this->listen(array(
			"max" => 3,
			"url" => array("printlist", "classesid" => "/^\d+$/")
			),
			function () {
				save(array("classification", "printlist"));
				$this->permission = array("classification" => array("select" => array("public")));
			}
		);

		//------------------------------ load form to insert users to a classes
		$this->listen(array(
			"max" => 2,
			"url" => array("class","classesid"=> "/^\d+$/")
			),
			function () {
				save(array("classification", "classes"));
				$this->permission = array("classification" => array("select" => array("public")));
			}
		);

		//------------------------------ api to insert users to classes
		$this->listen(array(
			"max" => 4,
			"url" => array("apipriceback", "usersid" => "/^\d+$/", "classesid" => "/^\d+$/")
		),
		function () {
			save(array("class" => "classification", "method"=> "api", "mod" => "priceback"));
				$this->permission = array("classification" => array("insert" => array("public", "private")));
			}
		);

		//------------------------------ api to insert users to classes
		$this->listen(array(
			"max" => 7,
			"url" => array(
			  "apiclassification"
			, "id" => "/^\d+$/"
			, "date" => "/^(.*)+$/"
			, "because" => "/^(.*)$/"
			, "usersid" => "/^\d+$/"
			, "classesid" => "/^\d+$/")
		),
		function () {
			save(array("class" => "classification", "method"=> "api", "mod" => "classificationapi"));
				$this->permission = array("classification" => array("insert" => array("public", "private")));
			}
		);
		//------------------------------ api to insert users to classes
		$this->listen(array(
			"max" => 4,
			"url" => array("apiprice", "usersid" => "/^\d+$/", "classesid" => "/^\d+$/")
		),
		function () {
			save(array("class" => "classification", "method"=> "api", "mod" => "price"));
				$this->permission = array("classification" => array("insert" => array("public", "private")));
			}
		);

		//------------------------------ api to insert users to classes
		$this->listen(array(
			"max" => 5,
			"url" => array("api", "usersid" => "/^\d+$/", "classesid" => "/^\d+$/" , "type" => "/^(add|returnclasses)$/")
		),
		function () {
			save(array("class" => "classification", "method"=> "api", "mod" => "insert"));
				$this->permission = array("classification" => array("insert" => array("public", "private")));
			}
		);


		//------------------------------ load form to insert absence for all person in classes
		$this->listen(array(
			"max" => 3,
			"url" => array("absence","classesid"=> "/^\d+$/")
			),
			function () {
				save(array("classification", "absence"));
				$this->permission = array("absence" => array("insert" => array("public")));
			}
		);

		//------------------------------ load form to insert absence for  one person in classes
		//------------------------------ go to absence option
		$this->listen(array(
			"max" => 3,
			"url" => array("absence","classificationid"=> "/^\d+$/")
			),
			function () {
				save(array("absence", "option"));
				$this->permission = array("absence" => array("insert" => array("public")));
			}
		);
	}
}
?>