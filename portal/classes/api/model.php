<?php 
/**
* 
*/
class model extends main_model {
	public function sql_classes_detail($id = false) {

		//------------------- check branch
		$this->sql(".branch.classes", $id);
		
		return $this->sql()->tableClasses()->whereId($id)->limit(1)->select()->assoc();
	}
}
?>