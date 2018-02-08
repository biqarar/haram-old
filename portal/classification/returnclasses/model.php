<?php
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class model extends main_model {

	public function sql_classification_detail($field = false , $id = false) {
		//------------- check branch
		$this->sql(".branch.classification", $id);
		
		return $this->sql()->tableClassification()->whereId($id)->limit(1)->select()->assoc($field);
	}

	public function post_add_classification() {

	}

	public function post_edit_classification() {
		
	}
}
?>