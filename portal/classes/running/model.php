<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	public function post_setrunning(){
		$classes_id = $this->xuId("classesid");


		//---------------- check branch
		$this->sql(".branch.classes", $classes_id);

		if($this->sql()->tableClasses()->whereId($classes_id)->limit(1)->select()->assoc("status") == "running"){
			debug_lib::fatal("این کلاس هم اکنون در حال اجرا می باشد");
		}else{

			$this->sql(".price.runningClasses", $classes_id);
			$this->sql()->tableClasses()->whereId($classes_id)->setStatus("running")->update();
	
			$this->sql(".classes.count", $classes_id);

			debug_lib::true("فعال سازی مجدد کلاس انجام شد");
			
		}	

	}

	public function score($classes_id = false) {

		//---------------- check branch
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