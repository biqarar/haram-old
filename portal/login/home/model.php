<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_login() {
		//------------------------------ set login countey for load captcha
		if($this->sql(".loginCounter.login")){

			$u = $this->sql()->tableUsers();
				if(preg_match("/@/", post::username())) {
					$u = $u->whereEmail(post::username());
				} else {
					$u = $u->whereUsername(post::username());
				}

			$u = $u->andPassword(md5(post::password()))->limit(1)->select();

			//------------------------------ username and password tru
			if($u->num() == 1) {				
				
				//------------------------------ clear history of try to login
				$this->sql(".loginCounter.clear");

				//------------------------------ set login session (permission, menu , ...)
				if($this->sql(".login" , $u->assoc("id"))) {
					//------------------------------ redirect to page (profile || save page)
					if(isset($_SESSION['redirect'])){
						$redirect = $_SESSION['redirect'];
						unset($_SESSION['redirect']);
						$this->redirect($redirect);
					}else{
						$this->redirect("/profile");
					}	
				}
			}else{
				debug_lib::fatal("نام کاربری و کلمه عبور اشتباه است");
			}
		}
	}
}
?>