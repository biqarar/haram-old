<?php 
class model extends main_model {
	public function post_api() {
		
		$dtable = $this->dtable->table("branch")
		->fields('id', 'name', 'gender', "id edit")
		->search_fields("name", "gender")
		->result(function($r) {
			$r->edit = '<a class="icoedit" href="branch/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
		});
		$this->sql(".dataTable", $dtable);
	}

}
?>
