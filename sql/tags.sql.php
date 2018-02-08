<?php
namespace sql;
class tags {
	public $id   = array('type'=> 'int@10', 'autoI', 'label' => 'tags_id');
	public $name = array('type'=> 'varchar@255', 'label' => 'tags_name');

	public $unique = array("name");

	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("#fatext")->name("name");
		$this->validate()->farsi(3, 255); 
	}
}
?>