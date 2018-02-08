<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tableEducation_users()
				->setEducation_id(post::education_id())
				->setYear(post::year())
				->setField(post::field())
				->setAverage(post::average())
				->setTrend(post::trend());
	}

	public function post_add_education_users(){

		//------------------------------ insert
		$sql = $this->makeQuery()->setUsers_id($this->SESSION_usersid())->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert education_users successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert education_users failed]]");
		});
	}

	public function post_edit_education_users(){
		
		//------------------------------ update education_users
		$sql = $this->makeQuery()->whereId($this->xuId())->andUsers_id($this->SESSION_usersid())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update education_users successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update education_users failed]]");
		});
	}
}
?>