<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'branch';
		
		//------------------------------ load form
		$f = $this->form("@branch", $this->urlStatus());

		//------------------------------ list of branch
		$this->data->dataTable = $this->dtable(
			"branch/status=api/", 
			array("id", "name", "gender", "edit"));

		// $this->data->list = $list->compile();
		//------------------------------ edit form
		$this->sql(".edit", "branch", $this->xuId(), $f);
	}

}
?>