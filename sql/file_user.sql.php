<?php
/**
 * @author reza mohiti
 */

namespace sql;
class file_user {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'file_paln_id');
	public $file_id    = array('type'=> 'int@10', 'label' => 'files_id');
	public $users_id        = array('type'=> 'float@', 'label' => 'users_id');

	// public $foreign = array("files_id" => "files@id!title", "users_id" => "users@id!username");

	public function id() {
		$this->validate("id");
	}
	
	public function file_id(){
		$this->form("select")->name("files_id")->label("files_id");
		$this->setChild();
	}
	public function users_id(){
		$this->form("select")->name("users_id")->label("user_id");
		$this->setChild();
	}
	
}
?>