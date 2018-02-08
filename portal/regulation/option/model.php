<?php
/**
* @author reza mohiti
*/
class model extends main_model {

	public function makeQuery() {
		 return $this->sql()->tableRegulation()
		 		->setText(post::text())
				->setStatus(post::status());
	}

	public function post_add_regulation(){
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert regulation successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert regulation failed]]");
		});
	}

	public function post_edit_regulation() {
		$sql = $this->makeQuery()->whereId($this->uId())->update();
		$this->commit(function() {
			debug_lib::true("[[update regulation successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update regulation failed]]");
		});
	}
}
?>