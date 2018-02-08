<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_api() {

		$dtable = 	$this->dtable->table("group")
		->fields("id","name","id chart", "id edit")
		->search_fields("name")
		->query(function($q){
			// $q->groupbyId();
			// var_dump($q->select()->assoc());
		})
		->result(function($r){
			$r->edit = '<a class="icoedit" href="group/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
			$r->chart = $this->tag("a")->href("group/progress/id=". $r->chart)->class("icoletters")->render();
		});
		$this->sql(".dataTable", $dtable);
	}
	
	public function makeQuery() {
		return $this->sql()->tableGroup_list()
			->setName(post::name())
			->setDescription(post::description())
			->setExpert(post::expert())
			->setStatus(post::status());
	}
	public function post_add_group_list() {
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert group_list successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert group_list failed]]");
		});
	}

	public function post_edit_group_list() {
		$sql = $this->makeQuery()->whereId($this->uId(3))->update();
		$this->commit(function() {
			debug_lib::true("[[update group_list successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update group_list failed]]");
		});
	}

}
?>