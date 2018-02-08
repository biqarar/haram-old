<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class view extends main_view {
	public function config() {
		$this->global->page_title = "graduate";
		$this->global->url = $this->uStatus(true); // add | edit 
		$f = $this->form("@graduate", $this->uStatus());

		$this->sql(".edit", "graduate", $this->uId(), $f);

	}
}
?>