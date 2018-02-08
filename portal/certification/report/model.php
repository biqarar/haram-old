<?php

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_reportCertification($type = false) {

		$reprot = $this->sql()->tableCertification()->fieldId()->fieldDate_request()->fieldDate_print()->fieldDate_deliver();
		$reprot->joinClassification()->whereId("#certification.classification_id")->fieldMark();
		$reprot->joinPerson()->whereUsers_id("#classification.users_id")
		->fieldName()
		->fieldFamily()
		->fieldFather()
		->fieldBirthday()
		->fieldGender()
		->fieldNationality()
		->fieldFrom()
		->fieldNationalcode();
		$reprot->joinUsers()->whereId("#classification.users_id")->fieldUsername();
		$reprot->joinClasses()->whereId("#classification.classes_id")->fieldId("classes")->fieldMeeting_no("meeting_no");
		$reprot->joinPlan()->whereId("#classes.plan_id")->fieldName("plan");
			foreach ($this->branch() as $key => $value) {
				if($key == 0){
					$reprot->condition("and", "plan.branch_id","=",$value);
				}else{
					$reprot->condition("or","plan.branch_id","=",$value);
				}
			}
		// echo($reprot->select()->string());exit();
		if($type == "print") {
			$reprot->andDate_print("is", "#null");
		}elseif($type == "request"){
			$reprot->andDate_print("is", "#null")->andDate_deliver("is", "#null");
		}elseif($type == "all"){
			// all
		}elseif($type == "deliver"){
			$reprot->andDate_deliver("is", "#null");
		}
		// echo "string";($reprot->select()->string());exit();
		$reprot = $reprot->select()->allAssoc();
		foreach ($reprot as $key => $value)
		{

			if($value['nationality'] != "")
			{
				if($reprot[$key]['nationality'] === '97')
				{
					$reprot[$key]['عنوان شماره ملیت'] = "کد ملی";
				}
				else
				{
					$reprot[$key]['عنوان شماره ملیت'] = "گذر نامه";
				}

				$reprot[$key]['nationality'] = $this->sql()->tableCountry()->whereId($value['nationality'])->limit(1)->select()->assoc("name");
			}
			else
			{
				$reprot[$key]['عنوان شماره ملیت'] = "";
			}

			if($value['from'] != "")
			{
				$reprot[$key]['from'] = $this->sql()->tableCity()->whereId($value['from'])->limit(1)->select()->assoc("name");
			}
			// var_dump($value['gender']);exit();
			if($value['gender'] == "female") {
				$reprot[$key]['gender'] = "خواهر";
			}else{
				$reprot[$key]['gender'] = "برادر";
			}
			if($value['birthday'] != "") {
				$x = preg_match("/^(?P<year>\d{4})(\d{4})$/", $value['birthday'], $year);
				// var_dump($x);
				$reprot[$key]['birthday'] = $year['year'];
			}

			// get string time of meeting no of class
			$numtostr = new numtostr_lib;
			$reprot[$key]['string_time'] = @$numtostr->convert($value['meeting_no']);

			$reprot[$key]['status'] = _($this->sql(".certification",$value['mark']));
		}
		// var_dump($reprot);exit();
		return $reprot;

	}
}
?>