<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */

class view extends main_view{

	public function config(){
		//------------------------------ global
		$this->global->page_title = "certification";
		
		$this->data->list = $this->sql("#reportCertification", $this->xuId("type"));

		$this->data->list['title'] = "لیست گواهی نامه ها";
		
		if($this->xuId("xlsx") == 1) {
			$this->sql(".xlsx", $this->data->list, $this->data->list['title']);
		}

}
}
?>