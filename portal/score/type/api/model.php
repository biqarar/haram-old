<?php
class model extends main_model {
	public function post_api() {
		//----------------- check branch
		$this->sql(".branch.plan",$this->xuId());
		
		debug_lib::msg($this->sql()->tableScore_type()->wherePlan_id($this->xuId())->select()->allAssoc());
	}
}
?>