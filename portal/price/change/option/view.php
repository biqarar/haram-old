<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config() {
		//------------------------------ global
		$this->global->page_title='price_change';

		//------------------------------ load form
		$f = $this->form("@price_change", $this->urlStatus());
		// $this->listBranch($f);

		//------------- check branch
		// $this->sql(".branch.price_change", $this->xuId());
		
		//------------------------------ edit form
		$this->sql(".edit", "price_change", $this->xuId(), $f);

		//------------------------------ list of price_change
		$this->data->dataTable = $this->dtable("price/status=listapi/", array("id","name", "type", "edit"));
	}
}
?>