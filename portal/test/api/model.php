<?php 
/**
* 
*/
class model extends main_model
{
	public function post_list() {
		
		$data = $this->sql(".dataTable", "person", false, "name, family, father");
	}
}
 ?>