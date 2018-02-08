<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config() {
		//------------------------------ global
		$this->global->page_title='country';

		//------------------------------ load form
		$f = $this->form("@country", $this->urlStatus());

		//------------------------------ edit form
		$this->sql(".edit", "country", $this->xuId(), $f);

		//------------------------------ list of country
		$this->data->dataTable = $this->dtable("country/status=api/", array("id","name", "edit"));
	}
}
?>