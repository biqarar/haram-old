<?php
namespace sql;
class update_log {
	
	public $id           = array("type" => "int@10" );
	public $users_id     = array("type" => "int@10" );
	public $table 		= array("type" => "varchar@64" );
	public $field         = array("type" => "varchar@64" );
	public $record_id        = array("type" => "int@10" );
	public $old_value      = array("type" => "text@" );
	public $new_value        = array("type" => "text@" );
	


	public function id(){}

	public function users_id(){}

	public function table(){}

	public function field(){}

	public function record_id(){}

	public function old_value(){}

	public function new_value(){}

}
?>