<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{
	public function makeQuery() {
		return $this->sql()->tableConsultation_list()
			->setConsultation_id(post::consultation_id())
			->setDate(post::date())
			->setStart_time(post::start_time())
			->setEnd_time(post::end_time())
			->setStatus(post::status());
	}

	public function post_add_consultation_list() {

		$sql = $this->makeQuery()->insert();
		// var_dump($sql->string());
		$this->commit(function() {
			debug_lib::true("[[insert consultation_list successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert consultation_list failed]]");
		});
	}

	public function post_edit_consultation_list() {
		$sql = $this->makeQuery()->whereId($this->uId())->update();
		$this->commit(function() {
			debug_lib::true("[[update consultation_list successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update consultation_list failed]]");
		});
	}
}
?>