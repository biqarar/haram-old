<?php
/**
 *
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("لیست غیبت هایی") . " " . _("student");

		//------------------------------  set users_id
		$users_id  = $this->xuId();
		$classesid = $this->xuId("classesid");

		$this->data->deleted_absence = $this->sql("#deleted_absence", $users_id, $classesid);

			//----------------------- check banch
		$this->sql(".branch.users",$users_id);
		$this->sql(".branch.classes", $classesid);

		$this->topLinks(array(
			array("title" => "نمایش", "url" => "users/learn/absence/id=$users_id/classesid=0"),
			array("title" => "ثبت", "url" => "absence/status=add/usersid=$users_id"),

			));
		//------------------------------ check users (if teacher , can not be display another users by id)
		$this->check_users_type($users_id);


		$this->global->users_datail = " لیست غیبت های ثبت شده برای فراگیر " .
					$this->sql(".assoc.foreign" , "person", $users_id , "name" , "users_id")
					. '  ' .
					$this->sql(".assoc.foreign" , "person", $users_id , "family" , "users_id")
					. ' در کلاس شماره  ' . $classesid
					;

		$this->data->all_absence_link = "users/learn/absence/id=$users_id/classesid=0/";

		$this->data->dataTable = $this->dtable("users/learn/listabsence/status=xapi/usersid=$users_id/classesid=$classesid/"
					, array("plan","شماره کلاس", "teacher", 'date', 'type',"because",  "edit" , "delete"));
	}
}
?>