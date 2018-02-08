<?php 
class changeDate_cls {

	public $year         = false;
	public $month        = false;
	public $day          = false;
	public $change_day   = false;
	public $operator     = false;
	public $change_year  = false;
	public $change_month = false;
	public $change_days  = false;
	public $new_date	 = false;


	public function change($date = false, $change_day = 0 , $operator = "+"){
		if (strlen($date) == 8 ){

			$this->change_day = $change_day;
			$this->operator = $operator;
			$this->split_date($date);
			$this->run();
			return $this->new_date;

		}
	}

	/**
	*	get date and split to year, month and days 
	* 	@example : 13940101 
	* 	@return : year : 1394 , month: 01 , day : 01
	*/
	public function split_date($date) {
		$x = preg_match("/^(\d{4})(\d{2})(\d{2})$/", $date, $spDate);
		if($x){
			$this->year  = intval($spDate[1]);
			$this->month = intval($spDate[2]);
			$this->day   = intval($spDate[3]);
		}
	}

	/**
	*	get days to plus or subtraction of date
	* 	and change to year, month and days must be plus or subtraction
	* 	@example : 120 days 
	* 	@return : 0 yar, 4 month, 0 day
	*/
	public function eq(){
		
		$this->change_year =(intval(intval($this->change_day) / 365));
		
		$mod = fmod($this->change_day, 365);

		$this->change_month = intval($mod / 30);

		$this->change_days = intval(fmod($mod, 30));
	} 

	/**
	*	analyze operation to change plus or subtraction
	*/
	public function run(){
		$this->eq();
		if($this->operator == "+") {
			$this->plus();
		}elseif($this->operator == "-"){
			$this->subtraction();
		}
	}
	
	/**
	*	plus processing ... 
	*/
	public function plus() {
		$d = $this->day + $this->change_days;
		if($d > 31){
			$this->change_month++;
			$d = $d - 31;
		}
		$m = $this->month + $this->change_month;
		if($m > 12) {
			$this->change_year++;
			$m = $m - 12;
		}
		$y = $this->year + $this->change_year;
		$this->_return($y, $m, $d);
	}

	/**
	*	subtraction processing ... 
	*/
	public function subtraction() {
		$d = $this->day - $this->change_days;
		if($d <= 0){
			$this->change_month++;
			$d = $d + 30;
		}
		$m = $this->month - $this->change_month;

		if($m <= 0) {
			$this->change_year++;
			$m = $m + 12;
		}
		$y = $this->year - $this->change_year;
		$this->_return($y, $m, $d);

	}

	/**
	*	analyze format fo return
	*/
	private function _return($y, $m, $d) {
		if($y > 0) {
			$m =  ($m < 10) ? '0' . $m : $m ;
			$d =  ($d < 10) ? '0' . $d : $d ;
			$this->new_date = $y . '' . $m  . '' . $d;
		}
	}
}
?>