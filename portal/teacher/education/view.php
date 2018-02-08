<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config(){

		//------------------------------- global
		$this->global->page_title = "education_users";

		//------------------------------ check users (if teacher, can not be display by changing id)
		$users_id = $this->SESSION_usersid();
		$this->check_users_type($users_id);
		
		//------------------------------ url
		$this->global->url =  $users_id;

		//------------------------------- load form
		$f = $this->form("@education_users", $this->urlStatus());


		//------------------------------- edit form
		// $this->sql(".edit", "education_users", $this->xuId(), $f);

		$this->data->list  = $this->sql(".list", "education_users", function($query, $usersid){
			$query->whereUsers_id($usersid);
		}, $users_id)->compile();
		
	}
}
?>