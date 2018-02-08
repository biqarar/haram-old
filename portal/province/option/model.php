<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableProvince()->setName(post::name());
	}

	public function post_add_province() {
		//------------------------------ insert province
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert province successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert province failed]]");
		});
	}

	public function post_edit_province() {
		//------------------------------ update province
		$sql =$this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update province successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update province failed]]");
		});
	}
}
?>