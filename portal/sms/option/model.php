<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableBranch()->setName(post::name())->setGender(post::gender());
	}

	public function post_add_branch() {
		//------------------------------ insert branch
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert branch successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert branch failed]]");
		});
	}

	public function post_edit_branch() {
		//------------------------------ update branch
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update branch successful]]");
		});

		//------------------------------ update code
		$this->rollback(function() {
			debug_lib::fatal("[[update branch failed]]");
		});
	}

}
?>