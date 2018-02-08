<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		
		//------------------------------ globals
		$this->global->page_title = 'race';
		// var_dump("fuck");exit();
		$f = $this->form("@hefz_race", $this->urlStatus());
		// $this->sql(".edit", "hefz_race", $this->xuId(), $f);
		$f->remove("status");
		$this->data->dataTable = $this->dtable(
			"hefzlig/race/status=listapi/", 
			array('id','ligs', 'تیم اول','نتیجه','امتیاز','تیم دوم','نتیجه','امتیاز','type','status','date','زمان','مکان','description',"delete", "مسابقه"));
	
	}

}
?>