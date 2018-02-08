<?php
namespace sql;
/**
* reza mohiti
*/
class login_counter {
	public $ip = array('type' => 'varchar@15', 'label' => 'login_counter_id');
	public $time = array('type' => 'varchar@64', 'label' => 'login_counter_time');
	public $count = array('type' => 'int@3', 'label' => 'login_counter_count');
	public $type = array('type' => 'enum@login,register!login', 'label' => 'login_counter_type');
}
?>