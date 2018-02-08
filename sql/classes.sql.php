<?php
/**
 * @author reza mohiti
 */
namespace sql;
class classes {
	public $id         = array('type'=> 'int@10', 'autoI', 'label' => 'classes_id');
	// public $course_id  = array('type'=> 'int@10', 'label' => 'course_id');
	public $plan_id    = array('type'=> 'int@10', 'label' => 'plan_id');
	public $meeting_no = array('type'=> 'int@5', 'label' => 'classes_meeting_no');
	public $teacher    = array('type'=> 'int@10', 'label' => 'classes_teacher');
	public $age_range  = array('type'=> 'enum@child,teen,young,adult!young', 'label' => 'classes_age_range');
	public $quality    = array('type'=> 'enum@level one,level two,level three,begginer level,medium,advanced!level one', 'label' => 'classes_quality');
	public $place_id   = array('type'=> 'int@10', 'label' => 'place_id');
	public $name       = array('type'=> 'varchar@64', 'label' => 'classes_name');
	public $start_time = array('type'=> 'int@4', 'label' => 'classes_start_time');
	public $end_time   = array('type'=> 'int@4', 'label' => 'classes_end_time');
	public $start_date = array('type'=> 'int@8', 'label' => 'classes_start_date');
	public $end_date   = array('type'=> 'int@8', 'label' => 'classes_end_date');
	public $week_days  = array('type'=> 'set@saturday,sunday,monday,tuesday,wednesday,thursday,friday', 'label' => 'classes_week_days');
	public $status     = array('type'=> 'enum@ready,running,done!ready', 'label' => 'classes_status');
	public $type	    = array('type'=> 'enum@physical,virtual!physical', 'label' => 'classes_type');
	public $count	    = array('type'=> 'int@7', 'label' => 'classes_count');

	public $index      = array("course_id", "plan_id", "teacher", "place_id");
	public $unique     = array("id");
	public $foreign    = array(
		"course_id"   => "course@id!name",
		"plan_id"     => "plan@id!name",
		"teacher"     => "users@id!id",
		"place_id"    => "place@id!name"
		);

	public function id() {
		$this->validate("id");
	}

	// public function course_id() {
	// 	$this->form("select")->name("course_id");
	// 	$this->setChild();
	// 	$this->validate("id");
	// }


	public function plan_id() {
		$this->form("select")->name("plan_id")->addClass("select-plan notselect")->required();
		$this->setChild(function($q){
			$list = isset($_SESSION['my_user']['branch']['selected']) ?
						  $_SESSION['my_user']['branch']['selected'] : array();
			$q->groupOpen();
			foreach ($list as $key => $value) {
				if($key == 0){
					$q->condition("where", "plan.branch_id","=",$value);
				}else{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}
			$q->groupClose();

		}, function($child, $value){
			$child->label(gettext($value['name']))->value($value['id']);
		});
		$this->validate("id");
	}

	public function meeting_no() {
		$this->form("#number")->name("meeting_no")->title("تعداد ساعات آموزشی این کلاس \n جهت ثبت در گواهی نامه فراگیران")->required();
		$this->validate()->number(1, 5)->form->number("meetings number should be between 1 and 999");
	}

	public function teacher() {
		 $this->form("text")->name("teacher")->required()->id("teachername")->addClass("select-teacher")->data_url("teacher/api/");
		// $this->form("select")->name("teacher")->addClass("select-teacher notselect");
		// $this->setChild(function($q){
		// 	$q->whereType("teacher");
		// 	$q->joinPerson()->whereUsers_id("#users.id")->fieldName()->fieldFamily();
		// 	$q->groupbyId();
		// }, function($child, $value){
		// 	$child->label($value['name'] .  '  ' . $value['family'])->value($value['id']);
		// });
	}

	public function age_range() {
		$this->form("select")->name("age_range");
		$this->setChild($this->form);
		// $this->validate();
	}

	public function quality() {
		$this->form("select")->name("quality");
		$this->setChild($this->form);
		// $this->validate();
	}

	public function place_id() {
		$this->form("select")->name("place_id")->addClass("select-place notselect")->required();
		$this->setChild(function($q){
			$q->whereStatus("enable");
			$list = isset($_SESSION['my_user']['branch']['selected']) ?
						  $_SESSION['my_user']['branch']['selected'] : array();
			$q->groupOpen();
			foreach ($list as $key => $value) {
				if($key == 0){
					$q->condition("and", "place.branch_id","=",$value);
				}else{
					$q->condition("or","place.branch_id","=",$value);
				}
			}
			$q->groupClose();
		}, function($child, $value){
			$child->label($value['name'])->value($value['id']);
		});
		$this->validate("id");
	}

	public function start_time() {
		$this->form("#number")->name("start_time")->time('time');
		$this->validate()->time()->form->time("start time is not valid");
	}

	public function end_time() {
		$this->form("#number")->name("end_time")->time('time');
		$this->validate()->time()->form->time("end time is not valid");
	}

	public function start_date() {
		$this->form("#date")->name("start_date")->required();
		$this->validate()->date()->form->date("start date is not valid");
	}

	public function end_date() {
		$this->form("#date")->name("end_date")->required();
		$this->validate()->date()->form->date("end date is not valid");
	}

	public function week_days() {
		$this->form("checkbox")->name("week_days")->required();
		$this->setChild();
	}

	public function name() {
		$this->form("#fatext")->name("name");
		$this->validate()->farsi()->form->farsi("name should be persian");
	}

	public function status() {
		$this->form("select")->name("status");
		$this->setChild($this->form);
		// $this->validate();
	}

	public function type() {
		$this->form("select")->name("type");
		$this->setChild($this->form);
	}

	public function count() {
		$this->validate()->number(1,7);
	}
}
?>