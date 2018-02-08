<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller{
	function config(){	
	
	
	$this->listen(array(
			"max" => 7,
			"url" => array(
				"race",
			 	"status" => "setsettings",
			 	"raceid" => "/^\d+$/",
			 	"type" => "/^(.*)$/"
			 	)
			), 
			function() {
				save(array("hefzlig","racing", "mod" => "setsettings"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);
		
		$this->listen(array(
			"max" => 4,
			"url" => array("online")
			), array("hefzlig","online")
			
		);	

		// var_dump("f");exit();
		$this->listen(array(
			"max" => 3,
			"url" => array("groupapi","ligid" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","hefzgroup", "mod" => "api"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);	

		$this->listen(array(
			"max" => 3,
			"url" => array("raceteam","id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","raceteam"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);	
		$this->listen(array(
			"max" => 3,
			"url" => array("ligsapi","id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","ligs", "mod" => "api"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);		
		// race/status=setpresence/raceid=12/teamid=5/teamusersid=23
		$this->listen(array(
			"max" => 7,
			"url" => array(
				"race",
			 	"status" => "setpresence",
			 	"raceid" => "/^\d+$/",
			 	"teamid" => "/^\d+$/",
			 	"teamusersid" => "/^\d+$/",
			 	"checked" => "/^(true|false)/"
			 	)
			), 
			function() {
				save(array("hefzlig","racing", "mod" => "setpresence"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);


		$this->listen(array(
			"max" => 3,
			"url" => array("hefzgroup", "status" => "listapi")
			), 
			function() {
				save(array("hefzlig","hefzgroup", "mod" => "listapi"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("hefzgroup")
			), 
			function() {
				save(array("hefzlig","hefzgroup"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array(
			 	"status" => "mrhefz",
			 	"id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","mrhefz"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array(
			 	"status" => "showresult",
			 	"id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","show"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 5,
			"url" => array(
				"race",
			 	"status" => "raceapi",
			 	"teamid" => "/^\d+$/",
			 	"raceid" => "/^\d+$/",
			 	"type" => "/^(up|down)$/"
			 	)
			), 
			function() {
				save(array("hefzlig","racing", "mod" => "insertmanfiapi"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array(
				"racing",
			 	"status" => "resultapi",
			 	"raceid" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","racing", "mod" => "resultapi"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 6,
			"url" => array(
				"race",
			 	"status" => "raceapi",
			 	"teamuserid" => "/^\d+$/",
			 	"raceid" => "/^\d+$/",
			 	"value" => "/^(.*)$/",
			 	"type" => "/^(race1|race2)$/")
			), 
			function() {
				save(array("hefzlig","racing", "mod" => "insertapi"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("race", "status" => "setdelete", "id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","racedelete", "mod" => "apidelete"));
				$this->permission = array("hefz_ligs" => array("delete" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("race", "status" => "delete", "id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","racedelete"));
				$this->permission = array("hefz_ligs" => array("delete" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("race", "status" => "racing", "id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","racing"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("race", "status" => "listapi")
			), 
			function() {
				save(array("hefzlig","race", "mod" => "listapi"));
				$this->permission = array("hefz_ligs" => array("select" => array("public"), "update" => array("public")));
			}
		);
		
		$this->listen(array(
			"max" => 3,
			"url" => array("race")
			), 
			function() {
				save(array("hefzlig","race"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 5,
			"url" => array("teams", "status" => "apidelete", "teamuserid" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","teams", "mod"=> "apidelete"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 5,
			"url" => array("teams", "status" => "apiadd", "usersid" => "/^\d+$/", "teamid" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","teams", "mod"=> "apiadd"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 3,
			"url" => array("teams", "status" => "show", "id" => "/^\d+$/")
			), 
			function() {
				save(array("hefzlig","teamsshow"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);
		//------------------------------ branch descriptiion
		$this->listen(array(
			"max" => 4,
			"url" => array("ligs", "status" => "listapi", "type" => "/^(race|manage|result)$/")
			), 
			function() {
				save(array("hefzlig","ligs", "mod" => "listapi"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);
		$this->listen(array(
			"max" => 3,
			"url" => array("ligs")
			), 
			function() {
				save(array("hefzlig","ligs"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		$this->listen(array(
			"max" => 4,
			"url" => array("teams", "status" => "listapi")
			), 
			function() {
				save(array("hefzlig","teams", "mod" => "listapi"));
				$this->permission = array("hefz_ligs" => array("select" => array("public")));
			}
		);
		$this->listen(array(
			"max" => 3,
			"url" => array("teams")
			), 
			function() {
				save(array("hefzlig","teams"));
				$this->permission = array("hefz_ligs" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		

		
	}

}
?>