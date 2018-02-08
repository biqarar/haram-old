<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class view extends main_view {
	public function config() {
		$this->global->page_title = "tables";
		$this->global->url = $this->uStatus(true); // add | edit 
		$f = $this->form("@tables", $this->uStatus());

		$this->sql(".edit", "tables", $this->uId(), $f);

	}
}
?>