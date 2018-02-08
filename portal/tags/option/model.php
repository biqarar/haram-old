<?php
/**
* @author reza mohitit rm.biqarar@gmail.com
*/
class model extends main_model{
	public function makeQuery() {
		return $this->sql()->tableTags()->setName(post::name());
	}

	public function post_add_tags() {
		$sql = $this->makeQuery()
			->insert();
		$this->commit(function() {
			debug_lib::true("[[insert tags successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert tags failed]]");
		});
	}

	public function post_edit_tags() {
		$sql = $this->makeQuery()
			->whereId($this->uId())
			->update();
		$this->commit(function() {
			debug_lib::true("[[update tags successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update tags failed]]");
		});
	}

	public function sql_readyForEdit($id = false) {
		return $this->sql()->tableTags()->whereId($id)->select()->assoc();
	}
}
?>