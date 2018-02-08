<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config(){
		//------------------------------ globals
		$this->global->page_title = 'city';

		//------------------------------  load form
		$f = $this->form("@city", $this->urlStatus());

		//------------------------------  list of city
		$this->data->dataTable = $this->dtable(
			'city/status=api/',
			array('id', 'name', 'province','edit')
			);
		
		//------------------------------  edit form
		$this->sql(".edit", "city", $this->xuId(), $f);
	}
}
?>