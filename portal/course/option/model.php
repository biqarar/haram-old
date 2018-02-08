<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object to insert and update fields
		return $this->sql()->tableCourse()
				->setBegin_time(post::begin_time())
				->setEnd_time(post::end_time())
				// ->setExpert(post::expert())
				->setBranch_id($this->post_branch())
				->setName(post::name());
	}

	public function post_add_course() {

		if(post::begin_time() == "" || post::end_time() == "") {
			debug_lib::fatal("لطفا تاریخ شروع و تاریخ پایان دوره را وارد کنید");
		}

		//------------------------------ insert course
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert course successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert course failed]]");
		});
	}

	public function post_edit_course() {

		$this->sql(".branch.course", $this->xuId());
		//------------------------------ update course
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update course successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update course failed]]");
		});
	}
}
?>