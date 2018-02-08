<?php
/**
*
*/
class checkPermission_cls {
	public $sql = false;

	public function __construct() {
		$this->sql = new sqlMaker_lib;
	}
	public function check($table = false) {
		// $_SESSION['gust'] = ($this->login()) ? false : true;
		// $per = $this->sql()->tablePermission()->whereUsers_id
		// $x = $this->sql->tablePermission()->select()->allAssoc();
		// var_dump($x);
		// exit();
		// exit(page_lib::access("s"));
		// $users_id = ($this->login()) ? $_SESSION['my_user']['id'] : page_lib::access("users_id");

	}

	public function config() {

	}
}
?>