<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config() {
		//------------------------------ global
		$this->global->page_title='file_tag';

		//------------------------------ load form
		$f = $this->form("@file_tag", $this->urlStatus());
		$f->remove("condition");
		$f->add('width', 'text')->label("width")->name("width");
		$f->add('ratio', 'text')->label("ratio")->name("ratio");
		$f->atEnd("submit");
		//------------------------------ edit form
		$this->sql(".edit", "file_tag", $this->xuId(), $f);
		//------------------------------ list of country
		$this->data->dataTable = $this->dtable("files/type=tag/status=api/", array("id","tag", "table_name", 'type', 'max_size'));
	}
}
?>