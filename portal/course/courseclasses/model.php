<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function makeQuery() {
		
		$branch_course  = $this->sql(".branch.course", post::course_id());
		$branch_classes = $this->sql(".branch.classes", post::classes_id());
		//----------------- check branch
		if($branch_classes != $branch_course){
			debug_lib::fatal("شعبه دوره با شعبه کلاس مطابقت ندارد");
		}
		//------------------------------ make sql object to insert and update fields
		return $this->sql()->tableCourseclasses()
				->setCourse_id(post::course_id())
				->setClasses_id(post::classes_id());
	}

	public function post_add_courseclasses() {

		//------------------------------ insert courseclasses
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert courseclasses successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert courseclasses failed]]");
		});
	}

	public function post_edit_courseclasses() {


		//---------------- check branch
		$this->sql(".branch.courseclasses", $this->xuId());

		//------------------------------ update courseclasses
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update courseclasses successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update courseclasses failed]]");
		});
	}

	public function post_apilist(){

		//----------------- check branch
		$x = $this->sql(".branch.course", $this->xuId("courseid"));

		$q = $this->sql()->tableCourseclasses()->whereCourse_id($this->xuId("courseid"));
		$q->joinClasses()->whereId("#courseclasses.classes_id")->fieldTeacher()->fieldPlan_id();
		$q->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
		$q->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teacherName")->fieldFamily("teacherFamily");
		$q = $q->select()->allAssoc();
		debug_lib::msg($q);
		// var_dump($q);exit();
	}

	public function post_apiadd(){
		//----------------- check branch
		$branch_course = $this->sql(".branch.course", $this->xuId("courseid"));
		$branch_classes = $this->sql(".branch.classes", $this->xuId("classesid"));

		if($branch_classes != $branch_course){
			debug_lib::fatal("branch not match");
		}

		$classes_id = $this->xuId("classesid");
		$course_id = $this->xuId("courseid");
		$sql = $this->sql()->tableCourseclasses()->setClasses_id($classes_id)->setCourse_id($course_id)->insert();
		$this->post_apilist();
	}

	public function post_apidelete() {
		//----------------- check branch
		$branch_course = $this->sql(".branch.course", $this->xuId("courseid"));
		$branch_classes = $this->sql(".branch.classes", $this->xuId("classesid"));

		if($branch_classes != $branch_course){
			debug_lib::fatal("branch not match");
		}

		$classes_id = $this->xuId("classesid");
		$course_id = $this->xuId("courseid");
		$course_qury = $this->sql()->tableCourseclasses()->whereCourse_id($course_id)->andClasses_id($classes_id)->delete();
		$this->post_apilist();
		// var_dump($course_qury->string());exit();
	}
}
?>