<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_classes($start_date = false, $end_date = false) {
	

	$classes = $this->sql()
		->tableClasses()
		->condition("where", "#start_date" , "<=", $this->sql(".duplicateUsersClasses.convert_date",$end_date))
		->condition("and", "#end_date", ">=", $this->sql(".duplicateUsersClasses.convert_date",$start_date))
		->fieldId("classes_id");

	$classes->joinClassification()->whereClasses_id("#classes.id")
		->condition("and", "because", "is", "null")
		->condition("and", "date_delete" , "is", "null")
		->fieldId("classification_id");

	$classes->joinPlan()->whereId("#classes.plan_id")->fieldId("classification_id");
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

	$classes->joinBridge()->whereUsers_id("#classification.users_id")
		->andTitle("mobile")
		->fieldValue("mobile");

	$classes = $classes->select()->allAssoc();
	
	return $classes;
	

	}
}
?>