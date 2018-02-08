<?php
/**
 * @author reza mohiti
 */

namespace sql;
class files {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'files_id');
	public $title       = array('type'=> 'varchar@32', 'label' => 'files_title');
	public $size        = array('type'=> 'float@', 'label' => 'files_size');
	public $type        = array('type'=> 'varchar@6', 'label' => 'files_type');
	public $folder      = array('type'=> 'int@10', 'label' => 'files_folder');
	public $file_tag_id = array('type' => 'int@10', 'label'=> "file_tag");
	public $description = array('type'=> 'varchar@255', 'label' => 'files_description');
	
	// public $unique      = array("id");
	// public $index       = array("folder_id");
	public $foreign = array("file_tag_id" => "file_tag@id!tag");

	public function id() {
		$this->validate("id");
	}
	
	public function title() {
		$x = $this->form("text")->name("title");
		// $x->validate()->rename(function (){
		// 	$this->value = preg_replace("/[^a-zA-Z0-9".FACHR."]/", '-', $this->value);
		// });
	}

	public function size(){
		$this->validate("id");
	}
	
	public function type(){
		$this->validate("id");
	}

	public function folder() {
		$this->validate("id");
	}

	public function file_tag_id(){
		$this->validate("id");
	}

	public function description() {
		$this->form("textarea")->name("description");
	}
}
?>