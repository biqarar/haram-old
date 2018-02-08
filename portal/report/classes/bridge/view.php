<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){
		
		$list = $this->sql("#bridge_list", $this->xuId("classesid"));
		$list['title'] = "لیست شماره های تماس فراگیران";
		// ------------------------------ global
		$this->global->url = config_lib::$url;
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $list, $list['title']);
		}
		$this->data->list = $list;
		
	}
}
?>