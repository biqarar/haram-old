<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config(){
		//------------------------------  global
		$this->global->page_title = "price";

		$this->data->dataTable = $this->dtable(
			"price/status=api/", 
			array('id',"name","family", "username", "date", "pay_type", "value", "title", "card",  "transactions" ,"description", "edit"));
	}
}
?>