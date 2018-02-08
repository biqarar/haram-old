<?php 
class model extends main_model {
	public function post_api() {
		
		$dtable = $this->dtable->table("report")
		->fields("id", "tables", "name", "url", "id edit")
		->search_fields("name", "gender")
		->result(function($r) {
			$r->edit = '<a class="icoedit" href="report/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
		});
		$this->sql(".dataTable", $dtable);
	}

}
?>
