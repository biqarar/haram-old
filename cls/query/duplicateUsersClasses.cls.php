<?php 
class query_duplicateUsersClasses_cls extends query_cls {

	public function teacher($teacher_id = false, $new_classes = array()) {
		//------------------------------ duplicate key
		$duplicate = false;

		$error = false;

		$classes_detail = array();

		$new_classes['start_date'] = isset($new_classes['start_date']) ? $new_classes['start_date'] : "" ;
		$new_classes['end_date']   = isset($new_classes['end_date']) ? $new_classes['end_date'] : "" ;
		$new_classes['start_time'] = isset($new_classes['start_time']) ? $new_classes['start_time'] : "" ;
		$new_classes['end_time']   = isset($new_classes['end_time']) ? $new_classes['end_time'] : "" ;
		$new_classes['week_days']  = isset($new_classes['week_days']) ? $new_classes['week_days'] : "" ;
		$new_classes['classes_id']  = isset($new_classes['classes_id']) ? $new_classes['classes_id'] : 0 ;


		$allClasses_teacher  = $this->sql()->tableClasses()->whereTeacher($teacher_id)
			->groupOpen()
			->andStatus("ready")->orStatus("running")
			->groupClose();

		$allClasses_teacher = $allClasses_teacher->select()->allAssoc();

		foreach ($allClasses_teacher as $key => $value) {

			$start_date_exist = $this->convert_date($value['start_date']);
			$end_date_exist   = $this->convert_date($value['end_date']);
			$start_time_exist = $this->convert_time($value['start_time']);
			$end_time_exist   = $this->convert_time($value['end_time']);
			$week_days_exist  = preg_split("/\,/", $value['week_days']);

			$new_start_date   = intval($new_classes['start_date']);
			$new_end_date     = intval($new_classes['end_date']);
			$new_start_time   = intval($new_classes['start_time']);
			$new_end_time     = intval($new_classes['end_time']);
			$new_week_days    = is_array($new_classes['week_days']) ? $new_classes['week_days'] : preg_split("/\,/", $new_classes['week_days']);
			
			//------------------------------ save duplicate detail to show
				$classes_detail = $value;
				//------------------------------  date end of exist classes > start date of request classes
				if($start_date_exist <= $new_end_date && $end_date_exist >= $new_start_date) {

					//------------------------------ check week days of exist classes and request classes
				
					foreach ($new_week_days as $k => $v) {
						
						if(preg_grep("/" . $v . "/", $week_days_exist)) {

							//------------------------------ check time of exist classes and request classes
							if ($end_time_exist > $new_start_time && $start_time_exist < $new_end_time) {

								//------------------------------ duplicate item here !!! 
								//------------------------------ can not insert users in this class
								if($value["id"] != $new_classes['classes_id']){
									$duplicate = true;
								}
							}
						}
						if($duplicate) break;
					}
				}
			if($duplicate) break;
		}

		if($duplicate) {
			// print_r($classes_detail);exit();
			$msg = $classes_detail['id'];
			return array($duplicate , $msg);
		}
		return array($duplicate , "");

	}
	public function classification($users_id = false, $classes_id = false) {

		//--------------- check branch
		$x = $this->sql(".branch.classes", $classes_id);
		$y = $this->sql(".branch.users", $users_id, $x);
		 	
		// var_dump("fuck", $x, $y, $classes_id, $users_id);exit();
		//------------------------------ duplicate key
		$duplicate = false;

		$error = false;

		$classes_detail = array();

		
		$new_classes = $this->sql()->tableClasses()->whereId($classes_id)->limit(1)->select()->assoc();
		if(!isset($new_classes['id']) || $new_classes['status'] == 'done'){
			// var_dump($new_classes);exit();
			// debug_lib::fatal("failed","اطلاعات کلاس یافت نشد");
			return array(true,"این کلاس به اتمام رسیده است" );
		}
	
	
		$allClass_users = $this->sql()->tableClassification()->whereUsers_id($users_id);
		$allClass_users = $this->classification_finde_active_list($allClass_users);

		$allClass_users->joinClasses()->whereId("#classification.classes_id")
			->groupOpen()
			->andStatus("ready")->orStatus("running")
			->groupClose();

		$allClass_users = $allClass_users->select()->allAssoc();

		foreach ($allClass_users as $key => $value) {

			$start_date_exist = $this->convert_date($value['start_date']);
			$end_date_exist   = $this->convert_date($value['end_date']);
			$start_time_exist = $this->convert_time($value['start_time']);
			$end_time_exist   = $this->convert_time($value['end_time']);
			$week_days_exist  = preg_split("/\,/", $value['week_days']);

			$new_start_date   = $this->convert_date($new_classes['start_date']);
			$new_end_date     = $this->convert_date($new_classes['end_date']);
			$new_start_time   = $this->convert_time($new_classes['start_time']);
			$new_end_time     = $this->convert_time($new_classes['end_time']);
			$new_week_days    = preg_split("/\,/", $new_classes['week_days']);
			
			//------------------------------ save duplicate detail to show
				$classes_detail = $value;
				
				//------------------------------  date end of exist classes > start date of request classes
				if($start_date_exist <= $new_end_date && $end_date_exist >= $new_start_date) {

					//------------------------------ check week days of exist classes and request classes
				
					foreach ($new_week_days as $k => $v) {
						
						if(preg_grep("/" . $v . "/", $week_days_exist)) {

							//------------------------------ check time of exist classes and request classes
							
							if ($end_time_exist > $new_start_time && $start_time_exist < $new_end_time) {

								//------------------------------ duplicate item here !!! 
								// //------------------------------ can not insert users in this class
									$duplicate = true;
							}
						}
						if($duplicate) break;
					}
				}
			if($duplicate) break;
		}
		
		if($duplicate) {
			$msg = $classes_detail['classes_id'];
			return array($duplicate , "اطلاعات این کلاس با کلاس شماره" . $msg . " که برای این کاربر ثبت شده است تداخل دارد ");
		}
		return array($duplicate , "");
	}


	public function convert_time($time = false) {
		$nTime = preg_replace("/\:|\s|\-/", "", $time);
		if(strlen($nTime) < 6) {
			$nTime = $nTime . "00";
		}
		return intval($nTime);
	}

	public function convert_date($date = false) {
		if (!preg_match("/^(\d{4})(\-|\/|)(\d{1,2})(\-|\/|)(\d{1,2})$/", $date, $nDate)) {
			return false;
		}else{
			$date = $nDate[1]
			.
			((intval($nDate[3]) < 10) ? "0".intval($nDate[3]) : intval($nDate[3]))
			.
			((intval($nDate[5]) < 10) ? "0".intval($nDate[5]) : intval($nDate[5]));
		}
		return $date;
	
	}
}
?>