<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	
	public function sql_bridge_list($classesid = false) {
	$classesid = preg_split("/\,/", $classesid);
		$return = array();
		foreach ($classesid as $key => $value) {
			if(preg_match("/(\d+)\-(\d+)/", $value,$c)){
				for ($i=$c[1]; $i <= $c[2] ; $i++) { 
					$return[] = $i;
				}
			}else{
				$return[] = $value;
			}
		}
		$classesid = $return;

		if(!$classesid || empty($classesid)) return false;
		
		
		$classification = $this->sql()->tableClassification();
		
		$classification->groupOpen();
		foreach ($classesid as $key => $value) {
			$classification->orClasses_id($value);
		}
		$classification->groupClose()->fieldId();
		
		$this->classification_finde_active_list($classification);
		
		$classification->joinUsers()->whereId("#classification.users_id")->fieldUsername();

		$classification->joinPerson()->whereUsers_id("#classification.users_id");

		$classification->joinClasses()->whereId("#classification.classes_id")
			->fieldStart_time()
			->fieldEnd_time()
			->fieldStart_date()
			->fieldEnd_date()
			->fieldWeek_days();

		$classification->joinPlan()->whereId("#classes.plan_id")->fieldName("plan");
		$classification->groupOpen();
		//---------- get branch id in the list
		foreach ($this->branch() as $key => $value) {
			if($key == 0){
				$classification->condition("and", "plan.branch_id","=",$value);
			}else{
				$classification->condition("or","plan.branch_id","=",$value);
			}
		}
		$classification->groupClose();
		
		$classification->joinPlace()->whereId("#classes.place_id")->fieldName("place");
		
		$classification->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile")->fieldValue("mobile");

		$x = $classification->groupValue()->select();
		// echo $x->string(); exit();
		$x = $x->allAssoc();

		return $x;
	}
	public function post_report(){
	
	}	
}
?>