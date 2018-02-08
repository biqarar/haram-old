<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class view extends main_view
{
	function config(){
		$this->global->page_title = "consultation_list";
		$this->global->url = $this->uStatus(true); // add | edit 
		$f = $this->form("@consultation_list", $this->uStatus());
		$f->remove("users_id,plan_id,quality,result,description,good_remember,bad_remember");

		$this->sql(".edit", "consultation_list", $this->uId(), $f);
	}
}
?>