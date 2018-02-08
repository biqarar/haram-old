<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableEducation()
				->setGroup(post::group())
				->setSection(post::section());
	}

	public function post_add_education() {
		//------------------------------ insert education
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert education successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert education failed]]");
		});
	}

	public function post_edit_education() {

		//------------------------------ update education
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update education successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update education failed]]");
		});
	}
}
?>