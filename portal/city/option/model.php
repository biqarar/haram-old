<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model {

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableCity()->setName(post::name())->setProvince_id(post::province());
	}

	public function post_add_city() {
		//------------------------------ insert city
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert city successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert city failed]]");
		});
	}

	public function post_edit_city() {
		//------------------------------ update city
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update city successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update city failed]]");
		});
	}
}
?>