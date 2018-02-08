<?php
/**
* @author reza mohiti
*/
class model extends main_model {
	public function sql_regulation() {
		return $this->sql()->tableRegulation()->whereStatus("active")->select()->assoc("text");
	}
}
?>