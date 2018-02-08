<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller{
	
	function config(){
		//------------------------------ users/description
		$this->listen(array(
			"max"=> 3,
			'url' => array("description", "/^(|add|edit)$/")),
		function () {
			save(array("users", "description"));
			$this->permission = array("users_description" => array("insert" => array("public", "private"),
									"update" => array("public")));
			}
		);

		//------------------------------ users/group
		$this->listen(array(
			"max"=> 3,
			'url' => array("group", "/^(|add|edit)$/")),
		function () {
			save(array("users", "group"));
			$this->permission = array("users_group" => array("insert" => array("public") , "update" => array("public")));
		});

		//------------------------------ users/branch
		$this->listen(array(
			"max"=> 3,
			'url' => array("branch", "/^(|add|edit)$/")),
		function () {
			save(array("users", "branch"));
			$this->permission = array("users_branch" => array("insert" => array("public") , "update" => array("public")));
		});

	}
}
?>