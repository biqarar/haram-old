<?php
class model extends main_model {
	public function post_api() {
		exit();
		$dtable = $this->dtable->table("score_type")
			->fields("id","plan_id","title", "min","max","description", "id edit")
			->search_fields("title")
			->result(function($r){
				$r->edit = '<a href="c/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>