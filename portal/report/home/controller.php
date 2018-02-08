<?php
class controller extends main_controller {

	public $permission = array("report" => array("select" => array("public")));
	public function config(){

	$this->listen(array(
			"url" => array("plan", "progress", "id" => "/^\d+$/")
			), function (){
				save(array("report","plan", "progress" ));
				$this->permission = array("report" => array("select" => array("public")));
	});


	$this->listen(array(
			"url" => array("plan", "progress", "id" => "/^\d+$/", 'type' => 'all')
			), function (){
				save(array("report","plan", "progress" ));
				$this->permission = array("report" => array("select" => array("public")));
	});

	$this->listen(array(
			"url" => array("price", "status" => "reportpricelist", "session" => "/^\d+$/")
			), function (){
				save(array("report","price", "mod" => "reportpricelist"));
				$this->permission = array("report" => array("select" => array("public")));
	});
		//---------------------------- bridge
	$this->listen(array(
			"url" => array("daily")
			), function (){
				save(array("report","daily"));
				$this->permission = array("report" => array("select" => array("public")));
	});
			//---------------------------- bridge
	$this->listen(array(
			"url" => array("daily", "day" => "/^(sat)|(sun)|(mon)|(tue)|(wed)|(thu)|(fri)$/")
			), function (){
				save(array("report","daily"));
				$this->permission = array("report" => array("select" => array("public")));
	});

			//---------------------------- bridge
	$this->listen(array(
			"url" => array("daily","bridge", "day" => "/^(sat)|(sun)|(mon)|(tue)|(wed)|(thu)|(fri)$/")
			), function (){
				save(array("report","daily","bridge"));
				$this->permission = array("report" => array("select" => array("public")));
	});
				//---------------------------- bridge
	$this->listen(array(
			"url" => array("daily","bridge", "day" => "/^(sat)|(sun)|(mon)|(tue)|(wed)|(thu)|(fri)$/", "xlsx" =>"1")
			), function (){
				save(array("report","daily","bridge"));
				$this->permission = array("report" => array("select" => array("public")));
	});
			//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" =>"planstatusactive")
			), function (){
				save(array("report","classes","planstatusactive"));
				$this->permission = array("report" => array("select" => array("public")));
	});

			//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" =>"activeclasseslist")
			), function (){
				save(array("report","classes","activeclasseslist"));
				$this->permission = array("report" => array("select" => array("public")));
	});

			//---------------------------- bridge
	$this->listen(array(
			"max" => 5,
			"url" => array("classes", "type" =>"personactive")
			), function (){
				save(array("report","classes","personactive"));
				$this->permission = array("report" => array("select" => array("public")));
	});


	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "bridge", "/(.*)/")
			), function (){
				save(array("report","classes","bridge"));
				$this->permission = array("report" => array("select" => array("public")));
	});

	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "bridge", "classesid" => "/(.*)/" , "xlsx" => "1")
			), function (){
				save(array("report","classes","bridge"));
				$this->permission = array("report" => array("select" => array("public")));
	});
	//---------------------------- personstatus
	$this->listen(array(
			"url" => array("classes", "type" => "personstatus", "classesid" => "/(.*)/" , "xlsx" => "1")
			), function (){
				save(array("report","classes","personstatus"));
				$this->permission = array("report" => array("select" => array("public")));
	});
	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "person", "classesid" => "/(.*)/" , "xlsx" => "1")
			), function (){
				save(array("report","classes","person"));
				$this->permission = array("report" => array("select" => array("public")));
	});

	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "activeclasses")
			), function (){
				save(array("report","classes","activeclasses"));
				$this->permission = array("report" => array("select" => array("public")));
	});

	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "register")
			), function (){
				save(array("report","classes","register"));
				$this->permission = array("report" => array("select" => array("public")));
	});

	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "classification", "/(.*)/")
			), function (){
				save(array("report","classes","classification"));
				$this->permission = array("report" => array("select" => array("public")));
	});

	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes", "type" => "person", "/(.*)/")
			), function (){
				save(array("report","classes","person"));
				$this->permission = array("report" => array("select" => array("public")));
	});
	//----------------------------
	$this->listen(array(
			"max" => 3,
			"url" => array("classes", "status" => "apilist")
			), function (){
				save(array("report","classes", "mod" => "api"));
				$this->permission = array("report" => array("select" => array("public")));
	});


	//---------------------------- bridge
	$this->listen(array(
			"url" => array("classes","type" => "personstatus", "/(.*)/")
			), function (){
				save(array("report","classes","personstatus"));
				$this->permission = array("report" => array("select" => array("public")));
	});

	// ----------------------------
	// $this->listen(array(
	// 		"max" => 3,
	// 		"url" => array("classes", "status" => "report")
	// 		), function (){
	// 			save(array("report","classes","report",  "mod" => "report"));
	// 			$this->permission = array("report" => array("select" => array("public")));

	// });

	//----------------------------
	$this->listen(array(
				"max" => 3,
				"url" => array("classes")
				), function (){
					save(array("report","classes"));
					$this->permission = array("report" => array("select" => array("public")));
		});

	//----------------------------
	$this->listen(array(
				"max" => 3,
				"url" => array("price")
				), function (){
					save(array("report","price"));
					$this->permission = array("report" => array("select" => array("public")));
		});


	//----------------------------
	$this->listen(array(
				"max" => 5,
				"url" => array("price", "weekly", "start_date" => "/(.*)/", "end_date" => "/(.*)/")
				), function (){
					save(array("report","price", "weekly"));
					$this->permission = array("report" => array("select" => array("public")));
		});

		//---------------------------- plan
	$this->listen(array(
			"max" => 2,
			"url" => "plan"
			), function (){
				save(array("report","plan"));
				$this->permission = array("report" => array("select" => array("public")));
	});

		//----------------------------
	$this->listen(array(
				"max" => 5,
				"url" => array("plan", "planstatus", "start_date" => "/(.*)/", "end_date" => "/(.*)/")
				), function (){
					save(array("report","plan", "planstatus"));
					$this->permission = array("report" => array("select" => array("public")));
		});

		//----------------------------
	$this->listen(array(
				"max" => 5,
				"url" => array("plan", "dateclassesbridge", "start_date" => "/(.*)/", "end_date" => "/(.*)/")
				), function (){
					save(array("report","plan", "dateclassesbridge"));
					$this->permission = array("report" => array("select" => array("public")));
		});
	}
}
?>