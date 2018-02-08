<?php
/**
 * @author reza mohiti
 */

namespace sql;
class file_post {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'file_paln_id');
	public $files_id    = array('type'=> 'int@10', 'label' => 'files_id');
	public $post_id        = array('type'=> 'float@', 'label' => 'post_id');

	public $foreign = array("files_id" => "files@id!title", "post_id" => "post@id!name");

	public function id() {
		$this->validate("id");
	}
	
	public function files_id(){
		$this->form("select")->name("files_id")->label("files_id");
		$this->setChild();
	}
	public function post_id(){
		$this->form("select")->name("post_id")->label("post_id");
		$this->setChild();
	}
	
}
?>