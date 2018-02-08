<?php
namespace sql;
class posts {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'posts_id');
	public $title       = array('type'=> 'varchar@255', 'label' => 'posts_title');
	public $group       = array('type'=> 'int@10', 'label' => 'posts_group_id');
	public $short       = array('type'=> 'text@', 'label' => 'posts_short');
	public $text        = array('type'=> 'text@', 'label' => 'posts_text');
	public $time_spread = array('type'=> 'varchar@16', 'label' => 'posts_time_spread');
	public $end_spread  = array('type'=> 'varchar@16', 'label' => 'posts_end_spread');
	public $curl        = array('type'=> 'varchar@64', 'label' => 'posts_curl');
	public $post        = array('type'=> 'enum@post,page!psot', 'label' => 'posts_posts');


	public $unique = array("id", "curl");
	public $index = array("group");

	public $foreign = array("group" => "posts_group@id!group");

	public function id() {
		$this->validate("id");
	}

	public function title() {
		$this->form("#fatext")->name("title");
		$this->validate()->farsi(3, 255);
	}

	public function group() {
		$this->form("select")->name("group");
		$this->setChild();
		$this->validate("id");
	}

	public function short() {
		$this->form("#text_desc")->name("short");
		$this->validate()->farsi(3, 16);
	}
	
	public function text() {
		$this->form("#text_desc")->name("text");
		// $this->validate()->reg("/^(.*)$/");

	}
	
	public function time_spread() {
		$this->form("#fatext")->name("time_spread");
		$this->validate()->number(16);
	}
	
	public function end_spread() {
		$this->form("#fatext")->name("end_spread");
		$this->validate()->number(16);		
	}
	
	public function curl() {
		$this->form("#fatext")->name("curl");
		$this->validate()->farsi(3, 64);
	}

	public function type() {
		$this->form("select")->name("type");
		$this->setChild($this->form);
	}
}
?>