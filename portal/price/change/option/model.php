<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()->tablePrice_change()
		->setName(post::name())
		->setType(post::type());
		// ->setBranch_id($this->post_branch());
	}

	public function post_add_price_change(){
		//------------------------------ insert price_change
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert price_change successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert price_change failed]]");
		});
	}

	public function post_edit_price_change(){

		//--------------- check branch
		// $this->sql(".branch.price_change", $this->xuId());
		
		//------------------------------ update price_change
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update price_change successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update price_change failed]]");
		});
	}
}
?>