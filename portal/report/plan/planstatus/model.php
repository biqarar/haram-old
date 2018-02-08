<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_classes($start_date = false, $end_date = false) {
	
	// $query = 
	// "
	// 	SELECT 
	// 		plan.name AS planname,
	// 		COUNT(classes.id) AS classescount,
	// 		COUNT(classification.id) AS classificationcount
	// 	FROM
	// 		classes
	// 	INNER JOIN classification ON classification.classes_id = classes.id
	// 	INNER JOIN plan ON plan.id = classes.plan_id
	// 	WHERE
	// 		classes.start_date >= '$start_date' AND classes.end_date <= '$end_date'
	// 	GROUP BY planname
	// ";

	// $result = $this->db($query)->allAssoc();
	// var_dump($result);
	// exit();
	$classes = $this->sql()->tableClasses()
			->condition("where", "#start_date" , "<=", $this->sql(".duplicateUsersClasses.convert_date",$end_date))
			->condition("and", "#end_date", ">=", $this->sql(".duplicateUsersClasses.convert_date",$start_date));
	$classes->joinPlan()
		->whereId("#classes.plan_id")
		->fieldName('planname')
		->fieldMax_person("maxp")
		->fieldPrice("price");
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
	$classes->joinGroup()->whereId("#plan.group_id")->fieldName("group_name");
	$classes = $classes->select()->allAssoc();
	
		// var_dump($classes->string());exit();
		$return = array();
		// $return = array("طرح","تعداد کلاس های تشکیل شده","تعداد نفرات شرکت کننده","میانگین هر کلاس");
	foreach ($classes as $key => $value) {
		// var_dump($value);exit();
		$return[$value['plan_id']]['planname'] = $value['planname'];
		$return[$value['plan_id']]['group_name'] = $value['group_name'];

		isset($return[$value['plan_id']]['classescount']) ? 
			  $return[$value['plan_id']]['classescount']++ :
			  $return[$value['plan_id']]['classescount'] = 1;

		isset($return[$value['plan_id']]['classificationcount']) ? 
			  $return[$value['plan_id']]['classificationcount'] += intval($value['count']) :
			  $return[$value['plan_id']]['classificationcount'] = intval($value['count']);

		// $return[$value['plan_id']]['max_person'] = $value['maxp'];
		$return[$value['plan_id']]['average_classes'] = round(intval($return[$value['plan_id']]['classificationcount']) /  intval( $return[$value['plan_id']]['classescount']));
		$return[$value['plan_id']]['price'] = $value['price'];
	}
	// var_dump($return);exit();
		return $return;

	}
}
?>