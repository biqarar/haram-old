<?php
namespace sql;
class group {
	public $id   = array('type'=> 'int@10', 'autoI', 'label' => 'group_id');
	public $name = array('type'=> 'varchar@32', 'label' => 'group_name');
	// public $branch_id = array('type'=> 'int@10', 'label' => 'branch_id');

	public $unique = array("name");
	// public $foreign = array("branch_id"=>"branch@id!name");

	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->farsi()->form->farsi("group name should be persian");
	}

	// public function branch_id() {
	// 	$this->form("select")->name("branch_id")->required();
	// 	$this->validate("id");
	// 	$this->setChild();
	// 	// $this->validate()->farsi()->form->farsi("group branch_id should be persian");
	// }
}
?>