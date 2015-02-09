<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableReport()
			->setTable(post::table())
			->setName(post::name())
			->setUrl(post::url());
	}

	public function post_add_report() {
		// $this->sms("09356032043", "FUCK YOU");
		//------------------------------ insert report
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert report successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert report failed]]");
		});
	}

	public function post_edit_report() {
		//------------------------------ update report
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update report successful]]");
		});

		//------------------------------ update code
		$this->rollback(function() {
			debug_lib::fatal("[[update report failed]]");
		});
	}

}
?>