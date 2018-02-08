<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model
{

	public function makeQuery()
	{
		$branch_plan = $this->sql(".branch.plan", post::plan_id());

		// var_dump($branch_plan);exit();
		//------------------------------ make sql object
		$x = $this->sql()->tableScore_type()
						   ->setPlan_id(post::plan_id())
						   ->setTitle(post::title())
						   ->setType(post::type())
						   ->setStatus(post::status())
						   ->setMin(post::min())
						   ->setMax(post::max())
						   ->setDescription(post::description());
		return $x;
	}

	public function post_add_score_type()
	{
		//------------------------------ insert score_type
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function()
		{
			debug_lib::true("[[insert score_type successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function()
		{
			debug_lib::fatal("[[insert score_type failed]]");
		});
	}

	public function post_edit_score_type()
	{

		//--------------- check branch
		$this->sql(".branch.score_type", $this->xuId());

		//------------------------------ update score_type
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function()
		{
			debug_lib::true("[[update score_type successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function()
		{
			debug_lib::fatal("[[update score_type failed]]");
		});
	}
}
?>