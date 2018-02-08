<?php
namespace sql;
class permission {
	public $id      		 = array('type'=> 'int@10', 'autoI', 'label' => 'permission_id');
	public $tables    		 = array('type'=> 'varchar@64', 'label' => 'permission_tables');
	public $users_branch_id  = array('type'=> 'int@10', 'label' => 'users_branch_id');
	public $select   		 = array('type'=> 'enum@private,public!private', 'label' => 'permission_select');
	public $update   		 = array('type'=> 'enum@private,public!private', 'label' => 'permission_update');
	public $insert   		 = array('type'=> 'enum@private,public!private', 'label' => 'permission_insert');
	public $delete   		 = array('type'=> 'enum@private,public!private', 'label' => 'permission_delete');
	public $condition  		 = array('type'=> 'text@', 'label' => 'condition');


	public $unique = array("table");
	public $foreign = array("users_branch_id" => "users_branch@id!id");
	public $index	= array("users_branch_id");

	public function id() {
		$this->validate("id");
	}
	
	public function tables() {
		$this->form("checkbox")->name("tables")->required();
	}
	
	public function users_branch_id() {
		$this->form("text")->name("users_branch_id")->addClass("notselect")->required();
		// $this->setChild();
	}

	public function branch_id() {
		$this->form("select")->name("branch_id")->addClass("notselect")->required();
		$this->setChild();
	}
	
	public function select() {
		$this->form("radio")->name("select");
		$this->setChild($this->form);
		// $this->form("checkbox")->name("select");
		// $this->validate()->number(1);
	}
	
	public function update() {
		$this->form("radio")->name("update");
		$this->setChild($this->form);
		// $this->validate()->number(1);
	}
	
	public function insert() {
		$this->form("radio")->name("insert");
		$this->setChild($this->form);
		// $this->validate()->number(1);	
	}
	
	public function delete() {
		$this->form("radio")->name("delete");
		$this->setChild($this->form);
		// $this->validate()->number(1);
	}

	public function condition() {
		$this->form("text")->name("condition");
	}
}
?>