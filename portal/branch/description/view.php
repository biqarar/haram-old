<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {
		$this->global->page_title = 'branch_description';
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@branch_description", $this->uStatus(2));
		$this->sql(".edit", "branch_description", $this->uId(3), $f);
	}
}
?>