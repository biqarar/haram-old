<?php
namespace sql;
class posts_tags {
	public $id      = array('type'=> 'int@10', 'label' => 'posts_tags_id');
	public $posts_id = array('type'=> 'int@10', 'label' => 'posts_id');
	public $tags_id = array('type'=> 'int@10', 'label' => 'tags_id');

	public $unique = array("tags");
	public $index = array("tags_id");

	public $foreign = array("posts_id" => "posts@id!short", "tags_id" => "tags@id!name");

	public function id() {
		$this->validate("id");
	}

	public function posts_id() {
		$this->form("select")->name("posts_id");
		$this->setChild();
	}

	public function tags_id() {
		$this->form("select")->name("tags_id");
		$this->setChild();
	}
}
?>