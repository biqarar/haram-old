<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {
		$this->global->page_title = 'group_list';
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@group_list", $this->uStatus(2));

		$this->sql(".edit", "group_list", $this->uId(3), $f);
	}
}
?>