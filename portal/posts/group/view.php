<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class view extends main_view
{
	function config(){
		$this->global->page_title  = "posts_group";
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@posts_group", $this->uStatus(2));
		
		if(is_int($this->uId(3))) {
			$this->sql(".edit", "posts_group", $this->uId(3), $f);
		}
	}
}
?>