<?php
/**
 * @author reza mohiti
 */

namespace sql;
class file_plan {
	public $id       = array('type'=> 'int@10', 'autoI', 'label' => 'file_paln_id');
	public $files_id = array('type'=> 'int@10', 'label' => 'files_id');
	public $plan_id  = array('type'=> 'float@', 'label' => 'plan_id');

	public $foreign = array("files_id" => "files@id!title", "plan_id" => "plan@id!name");

	public function id() {
		$this->validate("id");
	}
	
	public function files_id(){
		$this->form("select")->name("files_id")->label("files_id");
		$this->setChild();
	}
	public function plan_id(){
		$this->form("select")->name("plan_id")->label("plan_id");
		$this->setChild();
	}
	
}
?>