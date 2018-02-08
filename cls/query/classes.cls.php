<?php
class query_classes_cls extends query_cls
{
	
	public function detail($classes_id = false) {
		//---------------- check branch
		$this->sql(".branch.classes", $classes_id);
		
		$classes = $this->sql()->tableClasses()->whereId($classes_id)->limit(1);
		$classes->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
		$classes->joinPerson()->whereUsers_id("#classes.teacher")
				->fieldName("teacherName")->fieldFamily("teacherFamily");
		$classes->joinPlace()->whereId("#classes.place_id")->fieldName("place");
		$classes = $classes->select()->assoc();
		return $classes;
	}

	public function count($classes_id = false){
		///------------------- check branch
		// $this->sql(".branch.classes", $classes_id);

		$count = $this->sql()->tableClassification()->whereClasses_id($classes_id);

		$classes_count = $this->classification_finde_active_list($count)->select()->num();

		$min_person = $this->sql()->tableClasses()->whereId($classes_id);
		$min_person->joinPlan()->whereId("#classes.plan_id")->fieldMin_person();
		$min_person = $min_person->select()->assoc();

		$update = $this->sql()
			->tableClasses()
			->setCount($classes_count)
			->whereId($classes_id);

		if($min_person['status'] == "ready" AND intval($min_person['min_person']) < intval($classes_count)){
			$update->setStatus("running");	
		}
		
		$update->update();
	}


}
?>