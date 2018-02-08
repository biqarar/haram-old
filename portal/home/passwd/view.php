<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'change password';
		
		//------------------------------ make chane password form
		$changepasswd = array();

		$hidden = $this->form("#hidden")->value("changepasswd");
		$changepasswd[] = $hidden->compile();
		
		if(!isset($_SESSION['supervisor'])){
			$oldpasswd = $this->form("password")->name("oldpasswd")->label("oldpasswd");
			$changepasswd[] = $oldpasswd->compile();
		}

		$newpasswd = $this->form("password")->name("newpasswd")->label("newpasswd");
		$changepasswd[] = $newpasswd->compile();
		
		$repasswd =  $this->form("password")->name("repasswd")->label("repasswd");
		$changepasswd[] = $repasswd->compile();
		
		$submit = $this->form("#submitedit")->value("update");
		$changepasswd[] = $submit->compile();
		
		
		
		$this->data->changepasswd = $changepasswd;
	}
}
?>