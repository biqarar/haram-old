<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'hefz_ligs';
		if($this->xuId("status")){
			$this->data->dataTable = $this->dtable(
				"hefzlig/ligs/status=listapi/type=result/", 
				array("id", "start_date", "end_date", "name", "آقای حفظ",  "جدول نتایج"));
		}else{

			//------------------------------ load form
			$f = $this->form("@hefz_ligs", $this->urlStatus());
			$this->listBranch($f);
			//------------------------------ list of hefz_ligs
			$this->data->dataTable = $this->dtable(
				"hefzlig/ligs/status=listapi/type=manage/", 
				array("id", "start_date", "end_date", "name", "edit"));

			// $this->data->list = $list->compile();
			//------------------------------ edit form
			$this->sql(".edit", "hefz_ligs", $this->xuId(), $f);

		}
	}

}
?>