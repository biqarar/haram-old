<?php
namespace sql;
class users_description {
	public $id       = array('type'=> 'int@10', 'autoI', 'label' => 'users_description_id');
	public $users_id = array('type'=> 'int@10', 'label' => 'users_id');
	public $text     = array('type'=> 'text@', 'label' => 'users_description_text');

	public $foreign = array("users_id" => "users@id!id");

	public function id() {
		$this->validate("id");
	}

	public function users_id() {
		$this->form("select")->name("users_id");
		$this->setChild();
	}

	public function text() {
		$this->form("#text_desc")->name("text");
	}
}
?>