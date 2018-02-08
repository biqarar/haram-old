<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		return $this->sql()->tableCourse_description()
				->setCourse_id(post::course_id())
				->setTitle(post::title())
				->setDescription(post::description());
	}

	public function post_add_course_description() {
		$sql = $this->makeQuery()
				->insert();
		$this->commit(function() {
			debug_lib::true("[[insert course_description 
successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[insert course_description failed]]");
		});
	}

	public function post_edit_course_description() {
		$sql = $this->makeQuery()
				->whereId($this->uId())
				->update();
		$this->commit(function() {
			debug_lib::true("[[update course_description successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update course_description failed]]");
		});
	}
}
?>