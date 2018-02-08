<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){

// sdsdfds
		$list = $this->sql("#classesactive");
		$this->global->url = config_lib::$url;
		$list['title'] = "گزارش کلاس های فعال ";
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $list, $list['title'],  "شاره تماس فراگیران فعال");
		}
		$this->data->list = $list;
		
	}

}
?>