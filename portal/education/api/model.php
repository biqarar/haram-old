<?php
class model extends main_model{
	function post_list(){
		$sql = $this->sql()->tableEducation()->whereGroup(config_lib::$aurl[1])->select();
		debug_lib::msg("education", $sql->allAssoc());
	}
}
?>