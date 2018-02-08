<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function makeQuery() {
		return $this->sql()->tableGroup_expert()
			->setPosts_id(post::posts_id())
			->setUsers_id(post::users_id())
			->setGroup_id(post::group_id())
			->setStart_date(post::start_date())
			->setEnd_date(post::end_date())
			->setStatus(post::status());
	}
	public function post_add_group_expert() {
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert group_expert successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert group_expert failed]]");
		});
	}

	public function post_edit_group_expert() {
		$sql = $this->makeQuery()->whereId($this->uId(3))->update();
		$this->commit(function() {
			debug_lib::true("[[update group_expert successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update group_expert failed]]");
		});
	}

}
?>