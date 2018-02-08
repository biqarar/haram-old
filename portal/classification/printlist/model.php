<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model {

	public function sql_bridge_list($users_id = false) {
		//------------------- check branch
		// $this->sql(".branch.users",$users_id);

		$bridge = $this->sql()->tableBridge()->whereUsers_id($users_id)->select()->allAssoc();
		$return  = array();
		foreach ($bridge as $key => $value) {
			if($value['title'] == "phone" && !isset($return['phone'])){
				$return['phone'] = $value['value'];
			}elseif($value['title'] == "mobile" && !isset($return['mobile'])){
				$return['mobile'] = $value['value'];
			}else{
				if(isset($return['moreBridge'])) {
					$return['moreBridge'] = $return['moreBridge'] . " # " . _($value['title']) ." : " .  $value['value'] . "  " . $value['description'];
				}else{
					$return['moreBridge'] = _($value['title']) ." : " . $value['value']. "  " . $value['description'];
				}
			}
		}
		return $return;
	}

	public function sql_classes_list($classes_id = false) {

		//---------------- check branch
		// $this->sql(".branch.classes", $classes_id);

		$q =  $this->sql()->tableClassification()->whereClasses_id($classes_id)->fieldId("classificationid")->fieldUsers_id("users_id");

		$q = $this->classification_finde_active_list($q);
		$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName()->fieldFamily()->orderFamily();
		// $q->joinBridge()->whereUsers_id("#classification.users_id")->fieldValue();
		$q->joinUsers()->whereId("#classification.users_id")->fieldUsername();
		$x = $q->fieldUsers_id()->select()->allAssoc();
		foreach ($x as $key => $value) {
			$x[$key]['price'] = $this->find_sum_price($value['users_id']);
		}
		return $x;
	}

	public function find_sum_price($users_id = false) {
		$price_list = $this->sql(".price.sum_price" , $users_id);
		if(intval($price_list['sum_active'])  < 0 ) {
			$return = "نیازمند پرداخت شهریه" . $price_list['sum_active'];
		}else{
			$return = "";
			// $return = $price_list['sum_active'];
		}
		return $return;
	}


	public function sql_score_type($plan_id = false) {
		//---------------- check branch
		// $this->sql(".branch.plan", $plan_id);

		return $this->sql()->tableScore_type()->wherePlan_id($plan_id)->andStatus('enable')->select()->allAssoc();
	}


}
?>