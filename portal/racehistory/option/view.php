<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config(){

		//------------------------------- global
		$this->global->page_title = "racehistory";

		//------------------------------ url
		$this->global->url = ($this->xuId("status") == "add") ? 
				"status=add/usersid=" . $this->xuId("usersid") :
				"status=edit/id=" . $this->xuId();

		//------------------------------- load form
		$f = $this->form("@racehistory", $this->urlStatus());

		//------------------------------- edit form
		$this->sql(".edit", "racehistory", $this->xuId(), $f);
	}
}
?>