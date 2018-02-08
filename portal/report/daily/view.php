<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){
		$day  = $this->xuId("day") ? $this->xuId("day") : "sat";
		$list = $this->sql("#day", $day);
		$day_count = $this->sql("#day_count", $day);
		// var_dump($list);exit();
		$this->global->url = config_lib::$url;
		$fa_day = _($day);
		$list['title'] = " گزارش روزانه - کلاس های فعال در روز $fa_day ";
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $list, $list['title'], $start_date .'-'. $end_date);
		}
		$this->data->list = $list;	
		$this->data->day = $day;	
		$this->data->day_count = $day_count;	
	}
}
?>