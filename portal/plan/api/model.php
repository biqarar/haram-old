<?php

class model extends main_model {
	public function post_api() {
		
		//-------------- check branch
		$this->sql(".branch.plan", $this->xuId());

		$plan = $this->sql()->tablePlan()->whereId($this->xuId())->limit(1)->select()->assoc("price");
		debug_lib::msg("price", $plan);
	}
}

?>