<?php
class query_plan_cls extends query_cls
{
	public function maxPerson($classesid = false){

		//------------------ check branch
		// $this->sql(".branch.classes", $classesid);

		$plan_max = $this->sql()->tableClasses()->whereId($classesid)->fieldId();
		$plan_max->joinPlan()->whereId("#classes.plan_id")->fieldMax_person();
		$plan_max = $plan_max->select()->assoc("max_person");

		$classes_count = $this->sql()->tableClassification()->whereClasses_id($classesid);
		$classes_count = $this->classification_finde_active_list($classes_count);
		$classes_count = $classes_count->select()->num();

		if(intval($plan_max) >= intval($classes_count) || global_cls::superclassification()){
			return true;
		}
		return false;
	}
}
?>