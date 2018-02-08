<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_changepasswd() {

		$msg = "خطا در تغییر کلمه عبور";

		if(post::newpasswd() == "") {
			debug_lib::fatal("لطفا کلمه عبور را وارد کنید");
		}

		//------------------------------ if old password is true
		if($this->login()){
			$user = $this->sql()
				->tableUsers()
				->whereId($_SESSION['my_user']['id']);

				if(!isset($_SESSION['supervisor'])){
					$user->andPassword(md5(post::oldpasswd()));
				}

				$user = $user->limit(1)->select();

			if($user->num() == 1){
				//------------------------------ if password == repassword
				if(post::newpasswd() == post::repasswd()){
					$changepasswd = $this->sql()
						->tableUsers()
						->setPassword(md5(post::newpasswd()))
						->whereId($_SESSION['my_user']['id'])
						->update();
				}else{
					//------------------------------ make fatal error (password != repasswrod)
					debug_lib::fatal("کلمه عبور جدید و تکرار کلمه عبور باهم برابر نیستند");
				}

			}else{
				//------------------------------ make falal error (old password is incurect)
				debug_lib::fatal("کلمه عبور فعلی اشتباه است");
			}
		}else{
			// $this->redirect("login");
		}

		//------------------------------ commit code
		$this->commit(function(){
			debug_lib::true("کلمه عبور با موفقیت تغییر یافت");
		});

		//------------------------------ rollback code
		$this->rollback(function($msg){
			debug_lib::fatal($msg);
		}, $msg);

	}
}
?>