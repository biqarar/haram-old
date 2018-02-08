<?php
namespace sql;
/**
* reza mohiti
*/
class branch_cash_ {
	public $id                = array('type'=> 'int@10', 'autoI', 'label' => 'absence_id');
	public $table			  = array('type'=> 'varchar@64', 'label' => 'classification_id');
	public $branch_id         = array('type'=> 'int@10', 'label' => 'absence_type');
	public $record_id         = array('type'=> 'int@10', 'label' => 'absence_date');
}
?>