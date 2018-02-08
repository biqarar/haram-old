<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */

class controller extends main_controller {
	public $permission = array();
	public function config() {		
	
		if(config_lib::$url == "captcha.png"){
			new captcha_lib;
			exit();
		}

		$this->listen(array(
			"max" => 1,
			"url" => array("logout")
			), function() {
			$_SESSION = array();
			session_destroy();
			header("location: ".host."/login");
			exit();
		});

		$this->listen(array(
			"max" => 1,
			"url" => "/^(|profile)$/"
			),
		function () {
			
			if($this->login("select_branch")){
				$this->access = true;
			}else{
				header("location: ".host."/login");
			exit();
			}
		}
		);

		$this->listen(array(
			"max" => 1,
			"url" => "/^(changepasswd)$/"
			),
		function () {
			save("home","passwd");	
			if($this->login()){
				$this->access = true;
			}else{
				header("location: ".host."/login");
			exit();
			}
		}
		);
		// $this->listen(array(
		// 	"max" => 2,
		// 	"url" => "changepasswd"
		// 	), array("home","passwd"));


	}
}
?>