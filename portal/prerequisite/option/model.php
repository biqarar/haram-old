<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		return $this->sql()->tablePrerequisite()
				->setPlan_id(post::plan_id())
				->setPrerequisite(post::prerequisite());
	}

	public function post_add_prerequisite(){
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert prerequisite successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert prerequisite failed]]");
		});
	}

	public function post_edit_prerequisite(){
		$sql = $this->makeQuery()
				->whereId($this->uId())
				->update();
		$this->commit(function() {
			debug_lib::true("[[update prerequisite successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update prerequisite failed]]");
		});
	}
}
?>