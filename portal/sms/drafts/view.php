<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'drafts';

		//------------------------------ load form
		$f = $this->form("@drafts", $this->urlStatus());

		//------------------------------ list of drafts
		$this->data->dataTable = $this->dtable(
			"sms/drafts/status=apilist/", 
			array("id",'group', "tag", "text", "edit"));

		// $this->data->list = $list->compile();
		//------------------------------ edit form
		$this->sql(".edit", "drafts", $this->xuId(), $f);
	}

}
?>