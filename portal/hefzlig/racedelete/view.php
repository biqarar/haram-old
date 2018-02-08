<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		
		//------------------------------ globals
		$this->global->page_title = 'حذف مسابقه';
		
		
		$this->global->raceid = $this->xuId();

		list($this->data->team1, $this->data->team2) = $this->sql("#hefz_race_detail", $this->xuId());


	}

}
?>