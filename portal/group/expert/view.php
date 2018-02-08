<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {
		$this->global->page_title = 'group_expert';
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@group_expert", $this->uStatus(2));

		$this->sql(".edit", "group_expert", $this->uId(3), $f);
	}
}
?>