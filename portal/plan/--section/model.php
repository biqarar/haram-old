<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function makeQuery() {
		return $this->sql()->tablePlan_section()
				->setPlan_id(post::plan_id())
				->setSection(post::section());
	}

	public function post_add_plan_section() {
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert plan_section successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert plan_section failed]]");
		});
	}

	public function post_edit_plan_section() {
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		$this->commit(function() {
			debug_lib::true("[[update plan_section successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update plan_section failed]]");
		});
	}
}
?>