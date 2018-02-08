<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config(){

		//------------------------------- global
		$this->global->page_title = "education_users";

		//------------------------------ check users (if teacher, can not be display by changing id)
		$this->check_users_type($this->xuId("usersid"));
		
		//------------------------------ url
		$this->global->url = ($this->xuId("status") == "add") ? 
				"usersid=" . $this->xuId("usersid") :
				"status=edit/id=" . $this->xuId();

		//------------------------------- load form
		$f = $this->form("@education_users", $this->urlStatus());


		//------------------------------- edit form
		$this->sql(".edit", "education_users", $this->xuId(), $f);

		$this->data->list  = $this->sql(".list", "education_users", function($query, $usersid){
			$query->whereUsers_id($usersid);
		}, $this->xuId("usersid"))->compile();
		
	}
}
?>