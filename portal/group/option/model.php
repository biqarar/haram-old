<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{
	public function makeQuery() {
		return $this->sql()->tableGroup()
							->setName(post::name())
							->setBranch_id($this->post_branch());
	}
	
	public function post_add_group() {

		//------------------------------ check for duplicate entry for group name
		$duplicate = $this->sql()->tableGroup()
						->whereName(post::name())
						->andBranch_id($this->post_branch())
						->limit(1)->select()->num();

		if($duplicate >= 1 ) {
			debug_lib::fatal("duplicate entry for name");
		}else{
			$sql = $this->makeQuery()->insert();
		}
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert group successful]]");
		});

		//------------------------------ roolback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert group failed]]");
		});
	}

	public function post_edit_group() {
		//---------- check branch
		$this->sql(".branch.group", $this->xuId());

		//------------------------------ can not set null for field when update
		if(post::name() != "") {
			$sql = $this->makeQuery()->whereId($this->xuId())->update();
		}
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update group successful]]");
		});
		
		//------------------------------ roolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update group failed]]");
		});
	}
}
?>