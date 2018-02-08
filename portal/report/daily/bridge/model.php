<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_day_bridge($day = "sat") {

	$classes = $this->sql()->tableClasses()->condition("where","`classes`.`week_days`","like","'%$day%'")
			->groupOpen()
				->andStatus("running")
				->orStatus("ready")
			->groupClose();
		// $classes->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile")->fieldValue("mobile");
			// $classes->joinBridge()
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
	$classes = $classes->select()->allAssoc();

	// var_dump($classes);exit();

	$classification = $this->sql()->tableClassification();
		
		$classification->groupOpen();
		foreach ($classes as $key => $value) {
			$classification->orClasses_id($value['id']);
		}
		$classification->groupClose()->fieldId();
		
		$this->classification_finde_active_list($classification);
		
		$classification->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile")->fieldValue("mobile");
		$classification->joinClasses()->whereId("#classification.classes_id")
		->fieldName()->fieldStart_time()->fieldEnd_time();
		$classification->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");

		$x = $classification->groupValue()->select();
	return $x->allAssoc();
	
	}
}
?>