<?php 
class query_price_cls extends query_cls {

	public function checkClasses($users_id = false, $classes_id = false) {
		
			$plan_id = $this->sql()->tableClasses()->whereId($classes_id)->limit(1)
			->fieldPlan_id()
			->fieldWeek_days()
			->select()
			->assoc();

		$price = $this->sql()->tablePlan()->whereId($plan_id['plan_id'])->limit(1)->select();
		$price = $price->assoc();
		if($price['price'] == null) return true;
		$user_active_price = $this->sum_price($users_id);
		$user_active_price = $user_active_price['sum_active'];
		
		if(intval($user_active_price) >= intval($price['price']) || global_cls::superprice("classification")){
			$this->price_low($users_id, $classes_id, $price['price']);
			// $this->payment_coutn_check($price, $users_id, $classes_id);
			return true;
		}else{
			return false;
		}
	}

	public function price_low($users_id = false, $classes_id = false, $price = false) {
		

		$x = $this->sql()->tablePrice()
			->setUsers_id($users_id)
			->setDate($this->dateNow("Ymd"))
			->setValue($price)
			->setPay_type("rule")
			->setCard("000")
			->setTitle($this->get_price_change("شرکت در کلاس"))
			->setTransactions($classes_id)
			->insert();
	}

	public function payment_coutn_check($price, $users_id, $classes_id) {
		$payment_count	= intval($price['payment_count']);
		$expired_price	= intval($price['expired_price']);
		$price	= $price['price'];
		$date = $this->dateNow("Ymd");

		$week_days = $this->sql()->tableClasses()->whereId($classes_id)->fieldWeek_days()->select()->assoc("week_days");
		$week_days = count(preg_split("/\,/", $week_days));

		for($i = 1; $i <= $payment_count; $i++) {
			
			$date = $this->changeDate($date, ((intval($expired_price)/$week_days) * 7), "+");

			$x = $this->sql()->tablePrice()
				->setUsers_id($users_id)
				->setValue($price)
				->setPay_type("rule")
				->setTitle($this->get_price_change("پرداخت دوره ای"))
				->setTransactions($classes_id)
				->setVisible("#0")
				->setDate($date)
				->insert()->LAST_INSERT_ID();
				// $this->commit();
				// var_dump($x);
				
		}
		
		// var_dump($price);exit();
	}

	public function sum_price($users_id) {
		
		$price = $this->sql()->tablePrice()->whereUsers_id($users_id)->andVisible(1);
		$price->joinPrice_change()->whereId("#price.title");
		$price = $price->select()->allAssoc();
		
		$sum_active = 0;
		$sum_low = 0;
		$sum_all = 0;
		$count = 0;
		foreach ($price as $key => $value) {
			if($value['type'] == "price_add"){
				$sum_active = $sum_active + intval($value['value']);
				$sum_all = $sum_all + intval($value['value']);
			}elseif($value['type'] == "price_low"){
				$sum_active = $sum_active - intval($value['value']);
				$sum_low = $sum_low + intval($value['value']);
			}
			$count++;
		}
		$ret = array();
		$ret['sum_all'] = $sum_all;
		$ret['sum_low'] = $sum_low;
		$ret['sum_active'] = $sum_active;
		$ret['count_transaction'] = $count;
		
		return $ret;
	}
	public function voidClasses($classesid = false){
		
		$classification = $this->sql()->tableClassification()->whereClasses_id($classesid)
			->groupOpen()
			->condition("and", "#date_delete" , "is", "#null")
			->condition("or", "#because", "is", "#null")
			->groupClose();

		$classification->joinPrice()->whereUsers_id("#classification.users_id")
			->andPay_type("rule")
			->andStatus("active")
			->andTitle($this->get_price_change("شرکت در کلاس"))
			->andTransactions($classesid)->fieldId("priceid");
		$classification = $classification->select()->allAssoc();
		foreach ($classification as $key => $value) {
			$this->sql()->tablePrice()->whereId($value['priceid'])->setStatus("void")->update();
		}
	}

	public function runningClasses($classesid = false){
		$classification = $this->sql()->tableClassification()->whereClasses_id($classesid)
			->groupOpen()
			->condition("and", "#date_delete" , "is", "#null")
			->condition("or", "#because", "is", "#null")
			->groupClose();

		$classification->joinPrice()->whereUsers_id("#classification.users_id")
			->andPay_type("rule")
			->andStatus("void")
			->andTitle($this->get_price_change("شرکت در کلاس"))
			->andTransactions($classesid)->fieldId("priceid");
		$classification = $classification->select()->allAssoc();
		foreach ($classification as $key => $value) {
			$this->sql()->tablePrice()->whereId($value['priceid'])->setStatus("active")->update();
		}
	}

	public function get_price_change($name = false) {
		return $this->sql()->tablePrice_change()->whereName($name)->select()->assoc("id");
	}
}
?>