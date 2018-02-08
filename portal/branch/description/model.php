<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function makeQuery() {
		return $this->sql()->tableBranch_description()
				->setBranch_id(post::branch_id())
				->setTitle(post::title())
				->setValue(post::value());
	}

	public function post_add_branch_description() {
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert branch_description successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert branch_description failed]]");
		});
	}

	public function post_edit_branch_description() {
		$sql = $this->makeQuery()->whereId($this->uId(3))->update();
		$this->commit(function() {
			debug_lib::true("[[update branch_description successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update branch_description failed]]");
		});
	}
}
?>