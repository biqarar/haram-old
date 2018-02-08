<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {
		$this->global->page_title = 'graduate_classes';
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@graduate_classes", $this->uStatus(2));
		$this->sql(".edit", "graduate_classes", $this->uId(3), $f);
	}
}
?>