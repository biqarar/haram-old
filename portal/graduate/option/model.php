<?php
/**
* @author reza mohiti
*/
class model extends main_model {

	public function makeQuery() {
		 return $this->sql()->tableGraduate()
		 	->setUsers_id(post::users_id())
		 	->setStatus(post::status());
	}

	public function post_add_graduate(){
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert graduate successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert graduate failed]]");
		});
	}

	public function post_edit_graduate() {
		$sql = $this->makeQuery()->whereId($this->uId())->update();
		$this->commit(function() {
			debug_lib::true("[[update graduate successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update graduate failed]]");
		});
	}
}
 ?>