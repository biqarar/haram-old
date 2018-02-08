<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){
		$day  = $this->xuId("day") ? $this->xuId("day") : "sat";
		// $day_bridge = $this->sql("#day", $day);
		$day_bridge = $this->sql("#day_bridge", $day);
		// var_dump($day_bridge);exit();
		$this->global->url = config_lib::$url;
		$fa_day = _($day);
		$day_bridge['title'] = " گزارش روزانه - دریافت شماره تماس شرکت کنندگان در روز  $fa_day ";
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $day_bridge, $day_bridge['title'], $day );
		}
		$this->data->list = $day_bridge;	
		$this->data->day = $day;	
		// $this->data->day_bridge = $day_bridge;	
	}
}
?>