<?php
/**
* @author reza mohiti
*/
class model extends main_model {

	public function makeQuery() {
		 return $this->sql()->tableTables()
		 		->setName(post::name())
		 		->setFaname(post::faname());
	}

	public function post_add_tables(){
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert tables successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert tables failed]]");
		});
	}

	public function post_edit_tables() {
		$sql = $this->makeQuery()->whereId($this->uId())->update();
		$this->commit(function() {
			debug_lib::true("[[update tables successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update tables failed]]");
		});
	}
}
 ?>