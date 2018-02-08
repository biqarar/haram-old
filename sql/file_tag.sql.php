<?php
/**
 * @author reza mohiti
 */

namespace sql;
class file_tag {
	public $id         		= array('type'=> 'int@10', 'autoI', 'label' => 'file_paln_id');
	public $tag    		= array('type'=> 'varchar@64', 'label' => 'tag');
	public $table_name       = array('type'=> 'enum@users,posts,plan', 'label' => 'table_name');
	public $type         	= array('type'=> 'enum@image, multimedia, doc, zip, binary', 'label' => 'type');
	public $max_size     	= array('type'=> 'int@10', 'label' => 'max_size');
	public $condition		= array('type'=> 'varchar@255', 'label' => 'condition');


	
	public function id() {
		$this->validate("id");
	}
	
	public function tag(){
		$this->form("text")->name("tag")->label("tag");
	}
	public function table_name(){
		$this->form("select")->name("table_name")->label("table_name");
		$this->setChild();
	}

	public function type(){
		$this->form("select")->name("type")->label("type")->multiple("multiple");
		$this->setChild();
	}
	public function max_size(){
		$this->form("text")->name("max_size")->label("max_size");
	}
	public function condition(){
		$this->form("text")->name("condition")->label("condition");
	}
	
}
?>