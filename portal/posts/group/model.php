<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model {
	public function makeQuery() {
		return $this->sql()->tablePosts_group()
				->setGroup(post::group());
	}

	public function post_add_posts_group() {
		$sql = $this->makeQuery()
				->insert();
		$this->commit(function() {
			debug_lib::true("[[insert post_group successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert post_group failed]]");
		});
	}

	public function post_edit_posts_group() {
		$sql = $this->makeQuery()
				->whereId($this->uId())
				->update();
		$this->commit(function() {
			debug_lib::true("[[update post_group successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update post_group failed]]");
		});
	}
}
?>