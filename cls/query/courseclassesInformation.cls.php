<?php 
class query_courseclassesInformation_cls extends query_cls {

	function config($planname = false, $classesid = false) {
	
		$courseclasses = $this->sql()->tableCourseclasses()->whereClasses_id($classesid);
		$courseclasses->joinCourse()->whereId("#courseclasses.course_id")->fieldName("coursename");
		$courseclasses = $courseclasses->select();
		if($courseclasses->num() >= 1) {
			$title = "ثبت شده در دوره \n ";

			$course = "";
			$list = $courseclasses->allAssoc();
			// var_dump($list);
			// var_dump($courseclasses->assoc("coursename"));exit();
			foreach ($list as $key => $value) {
				$course .= $value['coursename'] . "\n";
			}

			return  $planname .' <a class="courseclasses-information" title="'.$title. $course . '"></a>';

		}else{
			return $planname;
		}
	}
}
?>