<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
class controller extends main_controller{
	public $access = false;

	function config(){
		$this->listen(array(
			"max" => 4,
			"url" => array("status" => "activelist", "type" => "/^(teacher|operator)$/")
			), function(){
			save(array("teacher", "list"));
			$this->permission = array("teacher" => array("select" => array("public")));
		});

		$this->listen(array(
			"max" => 4,
			"url" => array("status" => "apiactivelist", "type" => "/^(teacher|operator)$/")
			), function(){
			save(array("teacher", "list", "mod" => "api"));
			$this->permission = array("teacher" => array("select" => array("public")));
		});
// var_dump($_SESSION);exit();
		$this->listen(array(
			"max" => 3,
			"url" => array("status" => "apilist", "type" => "/^(teacher|operator)$/")
			), function(){
			save(array("teacher", "list", "mod" => "api"));
			$this->permission = array("teacher" => array("select" => array("public")));
		});

		$this->listen(
				array(
					"max" => 4,
					'url' => array("api","search" => "/(.*)/")
					),
				function(){
					save(array("teacher", "api", 'mod' => "list"));
					$this->access = true;
				}
			);


		$users_id = $this->SESSION_usersid();
		$surl = config_lib::$surl;

		if(isset($surl['id'])){
			$url_id = $surl['id'];
		}elseif(isset($surl['usersid'])){
			$url_id = $surl['usersid'];
		}else{
			$url_id = 0;
		}

		if(
			isset($_SESSION['my_user']['type']) &&
			$_SESSION['my_user']['type'] == 'teacher' &&
			($url_id == $users_id)) {

			$this->access = true;
		}

		$this->listen(
				array(
					"max" => 3,
					'url' => array("bridge", "status" => "add", "usersid" => $users_id)
					),
				function(){
					save(array("teacher", "bridge"));
				}
			);

		$this->listen(
				array(
					"max" => 3,
					'url' => array("person", "status" => "detail", "usersid" => $users_id)
					),
				function(){
					save(array("teacher", "person"));
				}
			);

		$this->listen(
				array(
					"max" => 3,
					'url' => array("teachinghistory", "status" => "add", "usersid" => $users_id)
					),
				function(){
					save(array("teacher", "teachinghistory"));
				}
			);

		$this->listen(
				array(
					"max" => 3,
					'url' => array("education", "status" => "add", "usersid" => $users_id)
					),
				function(){
					save(array("teacher", "education"));
				}
			);

			$this->listen(
				array(
					"max" => 3,
					'url' => array("extera", "status" => "add", "usersid" => $users_id)
					),
				function(){
					save(array("teacher", "extera"));
				}
			);

	}
}
?>