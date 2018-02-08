<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'مسابقات تیم';
		$header = array(
			
			"مسابقه با",
			"نوع دیدار",
			"درصد",
			"جمع امتیاز",
			"وضعیت"
			);

		$list = $this->sql("#team", $this->xuId());

		$this->data->team = $this->sql("#team_name", $this->xuId());

		$this->data->result = array("header" => $header, "list" => $list);
		// // var_dump($this->xuId());exit();
		
	}

}
?>