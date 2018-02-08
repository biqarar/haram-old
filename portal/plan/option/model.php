<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class model extends main_model{
	
	public function makeQuery() {
		//------------------------------ make sql object
		if($this->sql(".branch.group",post::group_id()) != $this->post_branch()){
			debug_lib::fatal("branch not match");
		}

		return $this->sql()->tablePlan()
				->setGroup_id(post::group_id())
				->setName(post::name())
				->setPrice(post::price())
				->setAbsence(post::absence())
				->setAbsence_type(post::absence_type())
				->setCertificate(post::certificate())
				->setMark(post::mark())
				->setBranch_id($this->post_branch())
				->setMin_person(post::min_person())
				->setMax_person(post::max_person())
				->setStatus(post::status())
				->setType(post::type())
				->setMeeting_no(post::meeting_no());
				// ->setExpired_price(post::expired_price());
	}

	public function post_add_plan() {
		//------------------------------ insert plan
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert plan successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert plan failed]]");
		});
	}

	public function post_edit_plan() {

		//-------------------- check branch
		$this->sql(".branch.plan", $this->xuId());
		
		//------------------------------ update paln
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update plan successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update plan failed]]");
		});
	}
}
?>