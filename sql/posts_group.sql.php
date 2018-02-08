<?php
namespace sql;
class posts_group {
	public $id    = array('type'=> 'int@10', 'autoI', 'label' => 'posts_group_id');
	public $group = array('type'=> 'varchar@64', 'label' => 'posts_group_group');

	public $unique = array("group");

	public function id() {
		$this->validate("id");
	}

	public function group() {
		$this->form("#fatext")->name("group");
		$this->validate()->farsi(3,64);
	}
}
?>