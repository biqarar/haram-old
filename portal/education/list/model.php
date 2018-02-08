<?php
class model extends main_model {
	public function post_api() {
		$dtable = $this->dtable->table("education")
			->fields("id", "group", "section", "id edit")
			->search_fields("`group`", "section")
			->result(function($r){
				$r->edit = '<a href="education/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>