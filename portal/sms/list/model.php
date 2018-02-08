<?php 
class model extends main_model {
	public function post_api() {
		
		$dtable = $this->dtable->table("drafts")
		->fields('id', 'tag', 'text', "id edit")
		->search_fields("tag", "text")
		->result(function($r) {
			$r->edit = '<a class="icoedit" href="sms/drafts/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
		});
		$this->sql(".dataTable", $dtable);
	}

}
?>
