<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class view extends main_view
{
	function config(){
		$this->global->page_title  = "posts_tags";
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@posts_tags", $this->uStatus());
		
		if(is_int($this->uId(3))){
			$this->sql(".edit", "posts_tags", $this->uId(3), $f);
		}
	}
} 
?>