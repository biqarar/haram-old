<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class controller extends main_controller{
	public function config(){

	$this->listen(array(
			"max" => 2,
			"url"=>array("change","status" => "add")
			), function(){
				save(array("price", "change" ,"option"));
				$this->access = global_cls::supervisor();
			// $this->permission = array("price_change" => array("insert" => array("public")));
	});

	$this->listen(array(
			"max" => 3,
			"url"=>array("change","status" => "edit" , "id" => "/^\d+$/")
			), function(){
				save(array("price", "change" ,"option"));
				$this->access = global_cls::supervisor();
			// $this->permission = array("price_change" => array("update" => array("public")));
	});

	$this->listen(array(
			"max" => 2,
			"url"=>array("status" => "listapi")
			), function(){
				save(array("price", "change" ,"list" , "mod" => "api"));
				$this->access = global_cls::supervisor();
			// $this->permission = array("price_change" => array("select" => array("public")));
	});

	$this->listen(array(
		"max" => 3,
		"url"=>array("status" => "classeslist" , "classesid" => "/^\d+$/")
		), function(){
			save(array("price","classes", "mod" => "api"));
		$this->permission = array("price" => array("select" => array("public")));
	});
		
	$this->listen(array(
			"max" => 2,
			"url"=>array("classes" , "classesid" => "/^\d+$/")
			), function(){
				save(array("price","classes"));
			$this->permission = array("price" => array("insert" => array("public")));
		});	

	$this->listen(array(
			"max" => 2,
			"url"=>array("status" => "add" , "usersid" => "/^\d+$/")
			), function(){
				save(array("price","option"));
			$this->permission = array("price" => array("insert" => array("public")));
		});

	$this->listen(array(
			"max" => 4,
			"url"=>array("status" => "edit" , "id" => "/^\d+$/", "usersid" => "/^\d+$/")
			), function(){
				save(array("price","option"));
			$this->permission = array("price" => array("update" => array("public")));
		});

		$this->listen(array(
			"max" => 2,
			"url"=>array("status" => "detail" , "usersid" => "/^\d+$/")
			), function(){
				save(array("price","detail"));
			$this->permission = array("price" => array("select" => array("public")));
		});	
	}
}
?>