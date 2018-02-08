<?php
namespace sql;
class courseclasses {
	public $id           = array('type'=> 'int@10', 'autoI', 'label' => 'courseclasses_id');
	public $course_id      = array('type'=> 'int@10', 'label' => 'course_id');
	public $classes_id      = array('type'=> 'int@10', 'label' => 'classes_id');

	public $foreign = array("course_id" => "course@id!name");

	public function id() {
		$this->validate("id");
	}

	public function course_id() {
		$this->form("select")->name("course_id")->class("notselect");
		$this->setChild(function($q){
			if(!isset($_SESSION['supervisor'])){
				$list = isset($_SESSION['my_user']['branch']['selected']) ?
							  $_SESSION['my_user']['branch']['selected'] : array();
				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "course.branch_id","=",$value);
					}else{
						$q->condition("or","course.branch_id","=",$value);
					}
				}
				$q->groupClose();
			}
		});
	}

	public function classes_id() {
		// $this->form("select")->name("classes_id");
		// $this->setChild();
	}
}
?>