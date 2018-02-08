<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'login';

		//------------------------------ load form
		$f = $this->form("@users");

		$f->username->style("text-align: left");
		$f->password->style("text-align: left");


		//------------------------------ set hidden value to get $_POST in model
		$f->hidden->value("login");
		
		//------------------------------ load captcha from
		if(isset($_SESSION['load_captcha']) && $_SESSION['load_captcha']) {
			$captcha = $this->form("captcha");
			$f->add("captcha", $captcha);
			$f->atEnd("submit");
		}

		//------------------------------ redirect if login
		if(isset($_SESSION['redirect']) && $this->login()){
			$redirect = $_SESSION['redirect'];
			unset($_SESSION['redirect']);
			$this->redirect($redirect);
		}elseif($this->login()){
			$this->redirect("/profile");
		}
		
		//------------------------------ change caption of sumbit
		$f->submit->value("login");

		//------------------------------ remove email field 
		$f->remove("email,status,type");
	}
}
?>