
<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config(){

		//------------------------------ globals
		$this->global->page_title ="teachinghistory";

		//------------------------------ check users (if teacher, can not be display by changing id)
		$this->check_users_type($this->xuId("usersid"));

		//------------------------------ url
		$this->global->url = ($this->xuId("status") == "add") ? 
				"status=add/usersid=" . $this->xuId("usersid") :
				"status=edit/id=" . $this->xuId();
				
		//------------------------------  load form		
		$f = $this->form("@teachinghistory", $this->urlStatus());

		//------------------------------  edit form
		$this->sql(".edit", "teachinghistory", $this->xuId(), $f);

		$this->data->list = $this->sql(".list", "teachinghistory", function($query, $usersid) {
			$query->whereUsers_id($usersid);
		}, $this->xuId("usersid"))
		->removeCol('id,users_id')
		->compile();
	}
}
?>