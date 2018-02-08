<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{
	public function config(){
		$this->global->page_title='prerequisite';
		$this->global->url = $this->uStatus(true);
		$f = $this->form("@prerequisite", $this->uStatus());

		$this->sql(".edit", "prerequisite", $this->uId(), $f);
	}
}
?>