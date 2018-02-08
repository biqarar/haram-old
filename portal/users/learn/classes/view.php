<?php 
/**
 * 
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("پرونده تحصیلی") . " " . _("student");

		//------------------------------  set users_id
		$users_id  = $this->xuId();

			//----------------------- check banch
		$this->sql(".branch.users",$users_id);

		//------------------------------ check users (if teacher , can not be display another users by id)
		$this->check_users_type($users_id);


		$this->global->users_datail = " اطلاعات تحصیلی فراگیر " . 
					$this->sql(".assoc.foreign" , "person", $users_id , "name" , "users_id")
					. '  ' . 
					$this->sql(".assoc.foreign" , "person", $users_id , "family" , "users_id");
				

		$allClassification = $this->sql(".list", "classification", function ($query, $id) {
			$query->whereUsers_id($id);
			$query->joinClasses()->whereId("#classification.classes_id")
										->fieldStart_time()
										->fieldEnd_time()
										->fieldWeek_days();

			$query->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
			$query->joinPlace()->whereId("#classes.place_id")->fieldName("placename");
			$query->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teachername")->fieldFamily("teacherfamily");
		}, $users_id)


		->removeCol("id,users_id,plan_section_id,mark,classes_id")

		->addColFirst("teachername", "teachername")
		->addColAfter("teachername", "teacherfamily", "teacherfamily")
		->addColAfter("teacherfamily","planname", "planname")
		->addColAfter("planname", "placename", "placename")

		->addColEnd("date_entry", "date_entry");

		$this->data->list = $allClassification->compile();
	}
} 
?>