<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_classes($start_date = false, $end_date = false) {
	

	$classes = $this->sql()->tableClasses()->whereStatus("running")->orStatus("ready");
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
		
		// var_dump($classes);exit();
		$return = array();
		$count = array();
		// $return = array("طرح","تعداد کلاس های تشکیل شده","تعداد نفرات شرکت کننده","میانگین هر کلاس");
	foreach ($classes as $key => $value) {
		

		$return[$value['plan_id']]['planname'] = $value['planname'];

		isset($return[$value['plan_id']]['classescount']) ? 
			  $return[$value['plan_id']]['classescount']++ :
			  $return[$value['plan_id']]['classescount'] = 1;

		isset($return[$value['plan_id']]['classificationcount']) ? 
			  $return[$value['plan_id']]['classificationcount'] += intval($value['count']) :
			  $return[$value['plan_id']]['classificationcount'] = intval($value['count']);

		// $return[$value['plan_id']]['max_person'] = $value['maxp'];
		$return[$value['plan_id']]['average_classes'] = intval($return[$value['plan_id']]['classificationcount']) /  intval( $return[$value['plan_id']]['classescount']);
	
		isset($count['class_count']) ? $count['class_count']++ : $count['class_count'] = 1;
		isset($count['person_count']) ? $count['person_count'] += intval($value['count']) : $count['person_count'] = intval($value['count']);
	}
	// var_dump($return);exit();
		return array($return, $count);

	}
}
?>