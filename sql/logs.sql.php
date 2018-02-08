<?php
/**
 * @author reza mohiti
 */

namespace sql;
class logs {

	public $id 				= array('type' => "int@10" , "label" => "log_id");
	public $user_id 		= array('type' => "int@10" , "label" => "log_user_id");
	public $log_data 		= array('type' => "varchar@200" , "label" => "log_data");
	public $log_meta 		= array('type' => "mediumtext@" , "label" => "log_meta");
	public $log_status 		= array('type' => "enum@enable,disable,expire,deliver!enable", "label" => "log_status");
	public $log_createdate 	= array('type' => "datetime@" , "label" => "log_createdate");
	public $date_modified 	= array('type' => "timestamp" , "label" => "log_date_modified");
	
	public function id() {
	}
	
	public function user_id() {

	}
	public function log_data() {

	}
	public function log_meta() {

	}
	public function log_status() {

	}
	public function log_createdate() {

	}
	public function date_modified() {

	}

}
?>