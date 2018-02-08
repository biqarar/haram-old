<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_activeclasses() {
		$classification = $this->sql()->tableClassification();
		// $list = $this->sql()->tableClasses()->whereStatus("running");
		// $list->joinClassification()->whereClasses_id("#classes.id");
		// $list->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile");
		// $list->join

		$this->classification_finde_active_list($classification);
		
		$classification->joinUsers()->whereId("#classification.users_id")->fieldUsername();

		$classification->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("name")->fieldFamily("family");

		$classification->joinClasses()->whereId("#classification.classes_id")->andStatus("running")
			->fieldStart_time()
			->fieldEnd_time()
			->fieldStart_date()
			->fieldEnd_date()
			->fieldWeek_days();

		$classification->joinPlan()->whereId("#classes.plan_id")->fieldName("plan");
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
		
		$classification->joinPlace()->whereId("#classes.place_id")->fieldName("place");
		
		$classification->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile")->fieldValue("mobile");

		$classification = $classification->select()->allAssoc();
		$all = array();
		if (ob_get_level() == 0) ob_start();
		echo("tel to 09109610612 :|");die();
		foreach ($classification as $key => $value) {
			ob_flush();
			flush();
			// var_dump($value);exit();
			$all[$key] = $value;
		}
		ob_end_flush();
		return $all;
	}
	public function post_report(){
	
	}	
}
?>