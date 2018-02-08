<?php
/**
* @author reza mohiti
*/
class model extends main_model {

	public function makeQuery() {

		//--------------- check branch
		$this->sql(".branch.classification", post::classification_id());

		//------------------------------ make sql object
		 return $this->sql()->tableAbsence()
				->setClassification_id(post::classification_id())
				->setDate(post::date())
				->setBecause(post::because())
				->setType(post::type());
	}

	public function post_add_absence(){

		//------------------------------ insert absence
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert absence successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert absence failed]]");
		});
	}

	public function post_edit_absence() {

		//------------------------------ edit absence
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update absence successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update absence failed]]");
		});
	}
}
 ?>