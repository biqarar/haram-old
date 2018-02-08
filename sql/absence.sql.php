<?php

namespace sql;
/**
* reza mohiti
*/
class absence {

	public $id                = array('type'=> 'int@10', 'autoI', 'label' => 'absence_id');
	public $classification_id = array('type'=> 'int@10', 'label' => 'classification_id');
	public $type              = array('type'=> 'enum@delay,leave,unjustified absence,justified absence', 'label' => 'absence_type');
	public $date              = array('type'=> 'int@8', 'label' => 'absence_date');
	public $because           = array('type'=> 'varchar@255', 'label' => 'absence_because');
	
	public $unique            = array("classification_id");
	public $foreign           = array("classification_id" => "classification@id!id");

	public function id() {
		$this->validate("id");
	}

	public function classification_id() {
		$this->form("text")->name("classification_id")->disabled("disabled");
		// $this->setChild();
		$this->validate("id");
	}
	
	public function type() {
		$this->form("select")->id("type")->name("type");
		$this->setChild($this->form);
	}
	
	public function date() {
		$this->form("#date")->name("date");
	}
	
	public function because() {
		$this->form("#text_desc")->name("because");
	}
}
?>