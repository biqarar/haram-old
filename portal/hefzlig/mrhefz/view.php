<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'آقای حفظ';
		$header = array(
			"رتبه",
			"نام",
			"نام خانوادگی",
			"نام تیم",
			"جمع امتیاز",
			// "میانگین"
			);

		$list = $this->sql("#mrhefz", $this->xuId());

		$this->data->lig_name = $this->sql("#lig_name", $this->xuId());

		$this->data->result = array("header" => $header, "list" => $list);
		// var_dump($this->xuId());exit();
		
	}

}
?>