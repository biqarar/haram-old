<?php
namespace sql;
class province {
	public $id   = array('type'=> 'int@10', 'autoI', 'label' => 'province_id');
	public $name = array('type'=> 'varchar@32', 'label' => 'province_name');

	public $unique = array("name");

	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("text")->name("name")->required();
		$this->validate()->farsi();
	}
}
?>