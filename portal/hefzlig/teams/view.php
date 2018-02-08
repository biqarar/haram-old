<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'hefz_teams';
		
		//------------------------------ load form
		$f = $this->form("@hefz_teams", $this->urlStatus());
		// $this->listBranch($f);
		//------------------------------ list of hefz_teams
		$this->data->dataTable = $this->dtable(
			"hefzlig/teams/status=listapi/type=manage/", 
			array('id', 'ligs',"گروه مسابقه",'name','محدوده حفظ','teacher', "edit","مدیریت"));

		// $this->data->list = $list->compile();
		//------------------------------ edit form
		$this->sql(".edit", "hefz_teams", $this->xuId(), $f);
	}

}
?>