<?php
/**
 * @author reza mohiti
 */
namespace sql;
class course {
	public $id         = array('type'=> 'int@10', 'autoI', 'label' => 'course_id');
	public $begin_time = array('type'=> 'int@10', 'label' => 'course_begin_time');
	public $end_time   = array('type'=> 'int@10', 'label' => 'course_end_time');
	public $name       = array('type'=> 'varchar@64', 'label' => 'course_name');
	// public $expert     = array('type'=> 'int@10', 'label' => 'course_expert');
	public $branch_id  = array('type'=> 'int@10', 'label' => 'branch_id');
	
	// public $unique    = array("id");
	// public $foreign    = array("expert"=> "group_expert@id!id", "branch_id"=> "branch@id!name");
	// public $index     = array("expert", "branch_id");

	public function id() {
		$this->validate("id");
	}

	public function begin_time() {
		$this->form("#date")->name("begin_time")->required();
		$this->validate()->number(8);
	}
	
	public function end_time() {
		$this->form("#date")->name("end_time")->required();
		$this->validate()->number(8);
	}
	
	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->description();
	}
	
	// public function expert() {
	// 	$this->form("select")->name("expert");
	// 	// $this->setChild();
	// 	$this->validate("id");
	// }
	
	public function branch_id() {
		// $this->form("select")->name("branch_id");
		// // $this->setChild();
		// $this->validate("id");
	}
}
?>