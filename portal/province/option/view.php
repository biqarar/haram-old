<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config(){
		//------------------------------ globals
		$this->global->page_title = 'province';

		//------------------------------ load form
		$f = $this->form("@province", $this->urlStatus());

		//------------------------------ edit form
		$this->sql(".edit", "province", $this->xuId(), $f);

		//------------------------------ list of province
		$this->data->dataTable = $this->dtable("province/status=api/", array("id", "name", "edit"));
	}
}
?>