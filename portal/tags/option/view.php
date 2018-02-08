<?php
/**
* @author reza mohitit rm.biqarar@gmail.com
*/
class view extends main_view{
	public function config(){
		$this->global->page_title = 'tags';
		$this->global->url = $this->uStatus(true);
		$f = $this->form("@tags", $this->uStatus());
		if(is_int($this->uId())) {
			$this->sql(".edit", "Tags", $this->uId(), $f);
		}
	}
}
?>