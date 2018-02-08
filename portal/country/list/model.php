<?php
class model extends main_model {
	public function post_api() {
		$dtable = $this->dtable->table("country")
			->fields("id", "name", "id edit")
			->search_fields("name")
			->result(function($r){
				$r->edit = '<a href="country/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>