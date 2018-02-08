<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){
		
		$list = $this->sql("#bridge_list", $this->xuId("classesid"));
		$list['title'] = "لیست شماره های تماس فراگیران";
		$this->data->list = $list;
		// ------------------------------ global
		
	}
}
?>