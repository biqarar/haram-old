<?php
namespace sql;
class users_branch {
	public $id        = array('type'=> 'int@10', 'autoI', 'label' => 'users_branch_id');
	public $users_id  = array('type'=> 'int@10', 'label' => 'users_id');
	public $branch_id = array('type'=> 'int@10', 'label' => 'branch_id');
	public $type = array("type" => "enum@student,teacher,operator!student");
	public $status = array("type" => "enum@waiting,block,delete,enable!waiting");
	// public $unique = array("users");

	// public $foreign = array("users_id" => "users@id!id", "branch_id" => "branch@id!name");
// 
	public function id() {
		// $this->validate("id");
	}

	public function users_id() {
		// $this->form("select")->name("users_id");
		// $this->setChild();
	}

	public function branch_id() {
		// $this->form("radio")->name("branch_id");
		// $this->setChild();
	}

	public function type() {
		// $this->form("radio")->name("type")->label("type");
		// $this->setChild($this->form);
	}
}
?>