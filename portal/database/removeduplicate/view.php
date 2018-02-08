<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'تلفیق دو پرونده';

		//------------------------------ make chane password form
		$hidden = $this->form("#hidden")->value("merge");
		$username1 = $this->form("text")->name("username1")->label("نام کاربری");
		$username2 = $this->form("text")->name("username2")->label("نام کار بری");
		$submit = $this->form("#submitedit")->value("تلفیق");
		
		$merge = array();
		
		$merge[] = $hidden->compile();
		$merge[] = $username1->compile();
		$merge[] = $username2->compile();
		$merge[] = $submit->compile();
		
		$this->data->merge = $merge;
	}

}
?>