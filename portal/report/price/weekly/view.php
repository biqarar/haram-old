<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){

		$start_date  = $this->convert_date($this->xuId("start_date"));
		$end_date  = $this->convert_date($this->xuId("end_date"));
		$list = $this->sql("#weekly", $start_date, $end_date);
		// var_dump($_SESSION);
		// var_dump($list);exit();
		$this->global->url = config_lib::$url;
		$list['title'] = "لیست درآمد  - 
							گزارش مالی  -
							از تاریخ $start_date تا تاریخ $end_date  ";
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $list, $list['title'], $start_date .'-'. $end_date);
		}
		$this->data->list = $list;
		// ------------------------------ global
		
	}

	public function convert_date($date = false) {
		if (!preg_match("/^(\d{4})(\-|\/|)(\d{1,2})(\-|\/|)(\d{1,2})$/", $date, $nDate)) {
			return false;
		}else{
			$date = $nDate[1]
			.
			((intval($nDate[3]) < 10) ? "0".intval($nDate[3]) : intval($nDate[3]))
			.
			((intval($nDate[5]) < 10) ? "0".intval($nDate[5]) : intval($nDate[5]));
		}
		return $date;
	
	}
}
?>