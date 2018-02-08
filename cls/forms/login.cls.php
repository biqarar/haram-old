<?php
class forms_login_cls extends forms_lib{
	function __construct(){
		$this->username = $this->make("text")->label("نام کاربری")->name("username");
		$this->password = $this->make("password")->name("password")->label("password");
		$this->submit = $this->make("#submitlogin")->value("login");
	}
}
?>