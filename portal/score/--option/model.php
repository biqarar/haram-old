<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableCountry()->setName(post::name());
	}

	public function post_add_country(){
		//------------------------------ insert country
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert country successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert country failed]]");
		});
	}

	public function post_edit_country(){
		//------------------------------ update country
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update country successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update country failed]]");
		});
	}
}
?>