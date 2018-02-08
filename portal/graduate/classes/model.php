<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function makeQuery() {
		return $this->sql()->tableGraduate_classes()
			->setGraduate_id(post::graduate_id())
			->setClasses_id(post::classes_id());
	}

	public function post_add_graduate_classes() {
		$sql = $this->makeQuery()->insert();
		$this->commit(function() {
			debug_lib::true("[[insert graduate_classes successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert graduate_classes failed]]");
		});
	}

	public function post_edit_graduate_classes() {
		$sql = $this->makeQuery()->whereId($this->uId(3))->update();
		$this->commit(function() {
			debug_lib::true("[[update graduate_classes successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update graduate_classes failed]]");
		});
	}
}
?>