<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view{
	public function config(){
		//------------------------------ globals
		$this->global->page_title='course';

		//------------------------------ locad form
		$f = $this->form("@course", $this->urlStatus());

		//------------------------------ list of branch
		$this->listBranch($f);

		//------------------------------ remove branch_id because list of branch is loaded
		// $f->remove("branch_id");
		
		//----------------- check branch
		$this->sql(".branch.course", $this->xuId());
		
		//------------------------------ edit form
		$this->sql(".edit", "course", $this->xuId(), $f);

		$this->data->dataTable = $this->dtable(
			"course/status=api/", 
			array("begin_time", "end_time", "name", "edit"));

		
	}
}
?>