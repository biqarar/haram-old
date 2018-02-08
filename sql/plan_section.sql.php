<?php
namespace sql;
/**
* reza mohiti
*/
class plan_section {

	public $id                = array('type'=> 'int@10', 'autoI', 'label' => 'plan_section_id');
	public $plan_id           = array('type'=> 'int@10', 'label' => 'plan_id');
	public $section           = array('type'=> 'varchar@64', 'label' => 'plan_section_section');
	
	public $unique            = array("section");
	public $foreign           = array("plan_id" => "plan@id!name");

	public function id() {
		$this->validate("id");
	}

	public function plan_id() {
		$this->form("select")->name("plan_id")->class("notselect");;
		$this->setChild();
		$this->validate("id");
	}
	
	public function section() {
		$this->form("#fatext")->name("section")->required();
		$this->validate()->farsi()->form->farsi("text must be persian");
	}
}
?>