<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_day_count($day = "sat") {

	$classes = $this->sql()->tableClasses()->condition("where","`classes`.`week_days`","like","'%$day%'")
			->groupOpen()
				->andStatus("running")
				->orStatus("ready")
			->groupClose();
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
	$return  = array();
	$return['count'] = 0;
	$return['num'] = 0;
	foreach ($classes as $key => $value) {
		$return['count']++;
		$return['num'] += $value['count'];
	}
	return $return;
	var_dump($return);exit();
	}

	public function sql_day($day = "sat") {
	

	$classes = $this->sql()->tableClasses()->condition("where","`classes`.`week_days`","like","'%$day%'")
			->groupOpen()
				->andStatus("running")
				->orStatus("ready")
			->groupClose();
			// var_dump($classes->select()->string());exit();
	$classes->joinPlan()->whereId("#classes.plan_id")->fieldName('planname')->fieldMax_person("maxp");
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
	return $classes;
	
	}
}
?>