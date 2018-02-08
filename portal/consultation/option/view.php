<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class view extends main_view {
	
	function config(){
		$this->global->page_title  = "consultation";
		$this->global->url = $this->uStatus(true);
		$f = $this->form("@consultation", $this->uStatus());
		$this->sql(".edit", "consultation", $this->uId(), $f);
	}
} 
?>