<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view{
	
	public function config(){
		//------------------------------ globals
		$this->global->page_title = 'group';

		//------------------------------ load form
		$f = $this->form("@group", $this->urlStatus());

		//------------------------------ list of branch
		$this->listBranch($f);
		//-------------- check branch
		$this->sql(".branch.group", $this->xuId());
		//------------------------------ edit form
		$this->sql(".edit", "group", $this->xuId(), $f);

		// var_dump($f);exit();

		$this->data->dataTable = $this->dtable('group/status=api/', array('id','name',"وضعیت", 'edit'));	
	}
}
?>