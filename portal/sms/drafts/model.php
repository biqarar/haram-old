<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableDrafts()
			->setTag(post::tag())
			->setGroup(post::group())
			->setText(post::text());
	}

	public function post_add_drafts() {
		// $this->sql(".sms.send_classes", 2, "classification");
		// exit();
		// $this->sql(".sms.classes", 108, "hi");

		//------------------------------ insert drafts
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert drafts successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert drafts failed]]");
		});
	}

	public function post_edit_drafts() {
		//------------------------------ update drafts
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update drafts successful]]");
		});

		//------------------------------ update code
		$this->rollback(function() {
			debug_lib::fatal("[[update drafts failed]]");
		});
	}

	public function post_list() {
		
		$dtable = $this->dtable->table("drafts")
		->fields('id', 'group' ,'tag', 'text', "id edit")
		->search_fields("tag", "text")
		->result(function($r) {
			$r->edit = '<a class="icoedit" href="sms/drafts/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
		});
		$this->sql(".dataTable", $dtable);
	}
}
?>