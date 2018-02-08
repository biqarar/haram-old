<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class view extends main_view{
	public function config(){

		//------------------------------ set global
		$this->global->page_title ="DEVELOPER ADMIN :)";
	
		$this->sql("#admin");
		
	}
}
?>