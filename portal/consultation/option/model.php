<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		return $this->sql()->tableConsultation()
				->setGroup(post::group())
				->setName(post::name())
				->setExpert(post::expert())
				->setBranch_id(post::branch_id());
	}

	public function post_add_consultation(){
		$sql = $this->makeQuery()->insert();

		$this->commit(function() {
			debug_lib::true("[[insert consultation successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert consultation failed]]");
		});
	}

	public function post_edit_consultation(){
		$sql = $this->makeQuery()
				->whereId($this->uId())
				->update();
		$this->commit(function() {
			debug_lib::true("[[update consultation successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update consultation failed]]");
		});
	}
}
?>