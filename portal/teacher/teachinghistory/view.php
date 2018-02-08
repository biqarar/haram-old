
<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config(){

		//------------------------------ globals
		$this->global->page_title ="teachinghistory";

		//------------------------------ check users (if teacher, can not be display by changing id)
		$this->check_users_type($this->SESSION_usersid());

		//------------------------------ url
		$this->global->url = "status=add/usersid=" . $this->SESSION_usersid();
				
		//------------------------------  load form		
		$f = $this->form("@teachinghistory", $this->urlStatus());

		$this->data->list = $this->sql(".list", "teachinghistory", function($query) {
			$query->whereUsers_id($this->SESSION_usersid());
		})
		->removeCol('id,users_id')
		->compile();
	}
}
?>