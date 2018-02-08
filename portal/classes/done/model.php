<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function post_setdone(){
		$classes_id = $this->xuId("classesid");


		//---------------------- check branch
		$x = $this->sql(".branch.classes", $classes_id);
		
		// var_dump($x);

		if($this->sql()->tableClasses()->whereId($classes_id)->limit(1)->select()->assoc("status") == "done"){
			debug_lib::fatal("این کلاس به اتمام رسیده است");
		}

		$scoreCalculation = $this->sql()->tableClasses()->whereId($classes_id);
		$scoreCalculation->joinScore_calculation()->wherePlan_id("#classes.plan_id");
		$scoreCalculation = $scoreCalculation->limit(1)->select()->assoc();
		
		if(!$scoreCalculation) {
			debug_lib::fatal("روش محاسبه امتیاز نهایی ثبت نشده است");
		}else{

			$this->score($classes_id);

			$this->sql(".price.voidClasses", $classes_id);

			// $classification = $this->sql()->tableClassification()->whereClasses_id($classes_id);
			// $classification = $this->classification_finde_active_list($classification);
			// $classification->setDate_delete($this->dateNow())->setBecause("done")->update();

			$classification = $this->sql()->tableClassification()
			->whereClasses_id($classes_id)
			->groupOpen()
			->andBecause("absence")
			->orBecause("cansel")
			->orBecause("error_in_insert")
			->groupClose()
			->setMark(0)->update();
			
			$this->sql(".classes.count", $classes_id);
			
			$this->sql()->tableClasses()->whereId($classes_id)->setStatus("done")->update();

			debug_lib::true("ثبت اتمام کلاس انجام شد");
			
		}

	}

	public function score($classes_id = false) {
		//---------------------- check branch
		$this->sql(".branch.classes", $classes_id);
		
		
		$result = $this->sql(".scoreCalculation.score_classes", $classes_id);

		if($result && is_array($result)){

			foreach ($result as $key => $value) {
				$this->sql()->tableClassification()->whereUsers_id($key)->andClasses_id($classes_id)
					->setMark(
						($value['result'] && 
						$value['result'] != "" && 
						$value['result'] != null) ? $value['result'] : 0)->update();

			}

		}

	}
		
}
?>