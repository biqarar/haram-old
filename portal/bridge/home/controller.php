<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
class controller extends main_controller{
	function config(){
		$this->listen(
				array(
					"max" => 2,
					'url' => array("status" => "add", "usersid" => "/^(\d+)$/")
					),
				function(){
					save(array("bridge", "option"));
					$this->permission = array("bridge" => array("select" => array("public")));
				}
			);

		$this->listen(
				array(
					"max" => 2,
					'url' => array("status" => "detail", "usersid" => "/^(\d+)$/")
					),
				function(){
					save(array("bridge", "detail"));
					$this->permission = array("bridge" => array("select" => array("public")));
				}
			);

		$this->listen(
				array(
					"max" => 3,
					'url' => array("status" => "edit", "usersid" => "/^(\d+)$/" )
					),
				function(){
					save(array("bridge", "option"));
					$this->permission = array("bridge" => array("update" => array("public")));
				}
			);
	}
}
?>