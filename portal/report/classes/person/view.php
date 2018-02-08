<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){
		
		$list = $this->sql("#bridge_list", $this->xuId("classesid"));
		$list['title'] = "لیست مشخصات فراگیران";
		$this->data->list = $list;
		$this->global->url = config_lib::$url;
		
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $list, $list['title']);
		}
		// ------------------------------ global
		
	}
}
?>