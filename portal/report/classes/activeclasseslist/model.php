<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_activeclasseslist() {
	
	$classes = $this->sql()->tableClasses();
	$classes->groupOpen()->whereStatus("running")->orStatus("ready")->groupClose();
	$classes->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
	$classes->groupOpen();
	//---------- get branch id in the list
	foreach ($this->branch() as $key => $value) {
		if($key == 0){
			$classes->condition("and", "plan.branch_id","=",$value);
		}else{
			$classes->condition("or","plan.branch_id","=",$value);
		}
	}
	$classes->groupClose();

	$classes->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teacherName")->fieldFamily("teacherFamily");
	$classes->joinPlace()->whereId("#classes.place_id")->fieldName("place");
	$classes= $classes->select();
	// echo ($classes->string());exit();

	return $classes->allAssoc();
	}
}
?>