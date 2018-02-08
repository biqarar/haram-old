<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller{
	
	function config(){	
		//------------------------------ branch descriptiion
		$this->listen(array(
			"max" => 3,
			"url" => array("description", "/^(add|edit)$/")
			), 
			function() {
				save(array("branch", "description"));
				$this->permission = array("branch_description" => array("insert" => array("public"), "update" => array("public")));
			}
		);

		//------------------------------ branch move (manage branch_cash)
		$this->listen(array(
			"max" => 3,
			"url" => array("status"=> "change", "usersid" => "/^\d+$/")
			), 
			function() {
				save(array("branch", "change"));
				$this->permission = array("users_branch" => array("update" => array("public")));
			}
		);		
		
		//------------------------------ branch move (manage branch_cash)
		$this->listen(array(
			"max" => 3,
			"url" => array("apichange","usersbranchid"=> "/^(\d+)$/", "type" => "/^(waiting|enable|block|delete|operator|teacher|student)$/")
			), 
			function() {
				save(array("branch", "change" , "mod" => "apichange"));
				$this->permission = array("users_branch" => array("update" => array("public")));
			}
		);
		$this->listen(array(
			"max" => 5,
			"url" => array("status" => "apiaddbranch","usersid"=> "/^(\d+)$/","branchid"=> "/^(\d+)$/")
			), 
			function() {
				save(array("branch", "change" , "mod" => "apichangeusersbranch"));
				$this->permission = array("users_branch" => array("update" => array("public")));
			}
		);	
	}

}
?>