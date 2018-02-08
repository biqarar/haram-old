<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class model extends main_model{
	
	public function makeQuery() {
		//------------------------------  make sql object
		return $this->sql()->tableTeachinghistory()
				->setField(post::field())
				->setType(post::type())
				->setName(post::name())
				->setClub(post::club())
				->setLength(post::length());
	}

	public function post_add_teachinghistory() {
		//------------------------------ insert teachinghistory
		$sql = $this->makeQuery()->setUsers_id($this->xuId("usersid"))->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert teachinghistory successful]]");
		});

		//------------------------------ lollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert teachinghistory failed]]");
		});
	}

	public function post_edit_teachinghistory() {
		//------------------------------ update teaching history
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update teachinghistory successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update teachinghistory failed]]");
		});
	}
}
?>