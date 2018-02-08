<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller {
	public $access = true;
	public function config() {
		$this->listen(array(
			"max" => 2,
			"url" => array("selectbranch")
			), function() {
			save(array("login", "selectbranch"));
			if($this->login() && !$this->login("select_branch")){
				$this->access = true;
			}elseif($this->login() && $this->login("select_branch")){
				unset($_SESSION['my_user']['branch']['selected']);
				$this->redirect("login");
			}
		}
		);

		$this->listen(array(
			"max" => 1,
			"url" => null
			),
		function() {
			save(array("login"));
			if($this->login() && $this->login("select_branch")){
				$this->redirect("profile");
			}elseif($this->login() && !$this->login("select_branch")){
				header("location:" . host . "/login/selectbranch");exit();
			}else{
				$this->access = true;
			}

		});
	}
}
?>