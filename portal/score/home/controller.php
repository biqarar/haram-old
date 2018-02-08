<?php  
class controller extends main_controller {
	
	public function config(){

		$this->listen(array(
				"max" => 3,
				"url" => array("status" => "show" ,"classesid" => "/^\d+$/" )
				),
				function(){
					save(array("score", "show"));
					$this->permission = array("score" => array("select" => array("public")));
				});

		$this->listen(array(
				"max" => 4,
				"url" => array("status" => "apilist", "classesid" => "/^\d+$/"  )
				),
				function(){
					save(array("score", "show" , "mod" => "api"));
					$this->permission = array("score" => array("select" => array("public")));
				});

		$this->listen(array(
				"max" => 4,
				"url" => array("status" => "add" ,"classesid" => "/^\d+$/" , "scoretypeid" => "/^\d+$/")
				),
				function(){
					save(array("score", "score"));
					$this->permission = array("score" => array("insert" => array("public")));
				});
		
		
		$this->listen(array(
				"max" => 5,
				"url" => array("api","get","classroom", "classesid" => "/^\d+$/" ,"date" => "/^\d{8}$/")
				),
				function(){
					save(array("score", "api", "mod" => "get"));
					$this->permission = array("score" => array("select" => array("public")));
				});


		$this->listen(array(
				"max" => 5,
				"url" => array("api", "classificationid" => "/^\d+$/" ,"scoretypeid" => "/^\d+$/", "value" => "/(.*)/", "retest" => "/(true|false)/")
				),
				function(){
					save(array("score", "api", "mod" => "api"));
					$this->permission = array("score" => array("insert" => array("public")));
				});

		$this->listen(array(
				"max" => 5,
				"url" => array("api", "classificationid" => "/^\d+$/" ,"scoretypeid" => "/^\d+$/", "value" => "/(.*)/", "date" => "/\d{8}/")
				),
				function(){
					save(array("score", "api", "mod" => "api"));
					$this->permission = array("score" => array("insert" => array("public")));
				});
		
		$this->listen(array(
				"max" => 5,
				"url" => array("classes", "status" => "apilist" ,"classesid" => "/^\d+$/", "scoretypeid" => "/^\d+$/")
				),
				function(){
					save(array("score", "classes", "mod" => "api"));
					$this->permission = array("score" => array("insert" => array("public")));
				});

		$this->listen(array(
				"max" => 5,
				"url" => array("classes", "classesid" => "/^\d+$/")
				),
				function(){
					save(array("score", "classes"));
					$this->permission = array("score" => array("insert" => array("public")));
				});


		$this->listen(array(
				"max" => 3,
				"url" => array("type", "status" => "add")
				),
				function(){
					save(array("score", "type", "option"));
					$this->permission = array("score" => array("insert" => array("public")));
				});
		
		$this->listen(array(
				"max" => 3,
				"url" => array("type", "status" => "edit", "id" => "/^\d+$/")
				),
				function(){
					save(array("score", "type", "option"));
					$this->permission = array("score" => array("insert" => array("public")));
				});


		$this->listen(array(
				"max" => 3,
				"url" => array("calculation", "status" => "add")
				),
				function(){
					save(array("score", "calculation", "option"));
					$this->permission = array("score" => array("insert" => array("public")));
				});
		
		$this->listen(array(
				"max" => 3,
				"url" => array("calculation", "status" => "edit", "id" => "/^\d+$/")
				),
				function(){
					save(array("score", "calculation", "option"));
					$this->permission = array("score" => array("insert" => array("public")));
				});
		
		$this->listen(array(
				"max" => 3,
				"url" => array("type", "status" => "apilist")
				),
				function(){
					save(array("score","type" ,"list", "mod" => "api"));
					$this->permission = array("score" => array("insert" => array("public")));
				});


		$this->listen(array(
				"max" => 3,
				"url" => array("calculation", "status" => "apilist")
				),
				function(){
					save(array("score","calculation" ,"list", "mod" => "api"));
					$this->permission = array("score" => array("insert" => array("public")));
				});

		$this->listen(array(
				"max" => 3,
				"url" => array("type", "api", "id" => "/^\d+$/")
				),
				function(){
					save(array("score","type" ,"api", "mod" => "api"));
					$this->permission = array("score" => array("insert" => array("public")));
				});
	}
}
?>  