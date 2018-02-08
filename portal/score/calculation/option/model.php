<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {

		//---------------- check branch
		$this->sql(".branch.plan", post::plan_id());

		//------------------------------ make sql object
		return $this->sql()->tableScore_calculation()
						   ->setPlan_id(post::plan_id())
						   ->setCalculation(post::calculation())
						   ->setStatus(post::status())
						   ->setDescription(post::description());
	}

	public function post_add_score_calculation(){
		//------------------------------ insert score_calculation
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert score_calculation successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert score_calculation failed]]");
		});
	}

	public function post_edit_score_calculation(){

		//---------------- check branch
		$this->sql(".branch.score_calculation", $this->xuId());
		
		//------------------------------ update score_calculation
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update score_calculation successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update score_calculation failed]]");
		});
	}
}
?>