<?php
namespace sql;
/**
* reza mohiti
*/
class regulation {

	public $id     = array('type'=> 'int@10', 'autoI', 'label' => 'regulation_id');
	public $status = array('type'=> 'enum@active,inactive!active', 'label' => 'regulation_status');
	public $text   = array('type'=> 'text@', 'label' => 'regulation_text');

	public function id() {
		$this->validate("id");
	}
	
	public function status() {
		$this->form("select")->name("status");
		$this->setChild($this->form);
	}
	
	public function text() {
		$this->form("#text_desc")->name("text");
		$this->validate()->description();
	}
}
?>