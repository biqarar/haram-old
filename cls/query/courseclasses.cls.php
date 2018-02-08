<?php
class query_courseclasses_cls extends query_cls
{
	public function config($classes_id = false, $users_id = false){

		///------------------- check branch
		$this->sql(".branch.classes", $classes_id);

		$course = $this->sql()->tableCourseclasses()->whereClasses_id($classes_id);
		$course->joinCourse()->whereId("#courseclasses.course_id");
				// ->andBegin_time("<", $this->dateNow())
				// ->andEnd_time(">", $this->dateNow());
		$course = $course->limit(1)->select();
	
		if($course->num() == 1) {
			$return_msg = array();
			
			$insert = true;

			$list = $this->sql()->tableCourseclasses()->whereCourse_id($course->assoc("course_id"))->select()->allAssoc();
			
			foreach ($list as $key => $value) {
				
				$classes_id = $value['classes_id'];
				
				$check = $this->sql()->tableClassification()->whereUsers_id($users_id)->andClasses_id($classes_id)->limit(1)->select()->num();
				
				if($check == 0) {

					list($duplicate, $msg) = $this->sql(".duplicateUsersClasses.classification", $users_id, $classes_id);
					
					if($duplicate){
						$return_msg[] = $this->return_msg($classes_id, $msg);
						$insert = false;
					}

					if(!$this->sql(".plan.maxPerson", $classes_id)){
						$return_msg[] = $this->return_msg($classes_id, "ظرفیت این کلاس تکمیل است و امکان ثبت فراگیر وجود ندارد.");
						$insert = false;
					}

					if(!$this->sql(".price.checkClasses", $users_id , $classes_id)){
						$return_msg[] = $this->return_msg($classes_id, "شهریه کافی نیست لفطا نسبت به شارژ حساب این فراگیر اقدام فرمایید.");
						$insert = false;
					}
			
				}else{
					$return_msg[] = $this->return_msg($classes_id, 'این فراگیر قبلا در کلاس ثبت شده است');
					$insert = false;
				}
			}
			if($insert) {

				foreach ($list as $key => $value) {
					
					$classes_id = $value['classes_id'];
					//------------------------------ insert classification
					$classification = $this->sql()->tableClassification()
						->setUsers_id($users_id)
						->setClasses_id($classes_id)
						->setDate_entry($this->dateNow())
						->insert();
						$this->sql(".classes.count", $classes_id);

					$return_msg[] = $this->return_msg($classes_id, "فراگیر در کلاس اضافه شد", 'true');
				}

			}
			// var_dump($this->debug_msg($return_msg));
			return ($this->debug_msg($return_msg));
			
		}else{
			return false;
		}
	}
	
	function return_msg( $classes_id, $msg,$status = 'fatal') {
		return array( 'status' => $status,'classes_id' => $classes_id, "msg" => $msg);
	}

	function debug_msg($array) {
		$msg = "";
		$fatal = false;
		foreach ($array as $key => $value) {
			$msg .= $value['classes_id'] . ":" . $value['msg'] . " \n ";
			if($value['status'] == 'fatal'){
				$fatal = true;
			}
		}
		if($fatal){
			return array("type" => "fatal", "msg" => $msg);
		}else{
			return array("type" => "true", "msg" => $msg);
		
		}
	}
}
?>