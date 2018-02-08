<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model {
	public function makeQuery() {
		return $this->sql()->tablePosts_tags()
			->setposts_id(post::posts_id())
			->setTags_id(post::tags_id());
	}
	public function post_add_posts_tags() {
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert posts_tags successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert posts_tags failed]]");
		});
	}

	public function post_edit_posts_tags() {
		$sql = $this->makeQuery()->whereId($this->uId(3))->update();
		$this->commit(function() {
			debug_lib::true("[[update posts_tags successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update posts_tags failed]]");
		});
	}
}
?>