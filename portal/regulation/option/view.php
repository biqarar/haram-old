<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class view extends main_view {
	public function config() {
		$this->global->page_title = "regulation";
		$this->global->url = $this->uStatus(true); // add | edit 
		$f = $this->form("@regulation", $this->uStatus());

		$this->sql(".edit", "regulation", $this->uId(), $f);

	}
}
?>