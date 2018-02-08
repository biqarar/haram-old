<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableRacehistory()
				->setField(post::field())
				->setClub(post::club())
				->setStep(post::step())
				->setRank(post::rank())
				->setYear(post::year());
	}

	public function post_add_racehistory(){

		//------------------------------ insert
		$sql = $this->makeQuery()->setUsers_id($this->xuId("usersid"))->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert racehistory successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert racehistory failed]]");
		});
	}

	public function post_edit_racehistory(){
		
		//------------------------------ update racehistory
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update racehistory successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update racehistory failed]]");
		});
	}
}
?>