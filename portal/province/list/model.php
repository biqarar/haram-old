<?php

class model extends main_model {
	public function post_api() {
		$dt = $this->dtable->table('province')
		->fields("id", "name","id edit")
		->search_fields("name")
		->result(function($r){
			$r->edit = '<a class= "icoedit" href="province/status=edit/id='. $r->edit . '"></a>';
			});
		$this->sql(".dataTable", $dt);
	}
}

?>