<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'گروه های مسابقه';

		//------------------------------ load form
		$f = $this->form("@hefz_group", $this->urlStatus());
		
		//------------------------------ list of hefz_group
		$this->data->dataTable = $this->dtable(
			"hefzlig/hefzgroup/status=listapi/", 
			array("id", "مسابقاتیمی", "نام گروه", "توضیحات", "edit"));

		// $this->data->list = $list->compile();
		//------------------------------ edit form
		$this->sql(".edit", "hefz_group", $this->xuId(), $f);

	}

}
?>