<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function sql_get_username($usersid = false) {
		return $this->sql()->tableUsers()->whereId($usersid)->limit(1)->select()->assoc("username");
	}

	public function post_selectbranch() {

		$branch_id = false;

		if(post::logout()) {

			$this->redirect("logout");

		}else{
			if(isset($_SESSION['supervisor']) AND
				preg_match("/^supervisor\_".$_SESSION['supervisor']."$/",post::select_branch())){
				$_POST['supervisor'] = $this->sql_get_username($_SESSION['supervisor']);
			}
			if(post::supervisor() AND post::supervisor() != "" AND isset($_SESSION['supervisor'])){
				$supervisor_id = $_SESSION['supervisor'];

				$username = $this->sql()->tableUsers()->whereUsername(post::supervisor())->select();
				if($username->num()== 1) {

					$this->sql(".login", $username->assoc('id'));

					$_SESSION['supervisor'] = $supervisor_id;

					$this->redirect("profile");

				}else{

					debug_lib::fatal("نام کاربری یافت نشد");
				}

			}elseif(post::select_branch()){

				$branch_id = $this->sql(".branch.check", post::select_branch());
			}

			if($branch_id && $branch_id != null) {

				$_SESSION['my_user']['branch']['selected'][] = $branch_id;

				$this->sql(".login", $this->login("id"));

				if(isset($_SESSION['redirect'])){
					$redirect = $_SESSION['redirect'];
					unset($_SESSION['redirect']);
					$this->redirect($redirect);
				}else{
					$this->redirect("profile");
				}

			}else{
				$this->redirect("login");
			}

		}

	}
}
?>