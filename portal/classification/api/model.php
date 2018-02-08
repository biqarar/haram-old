<?php
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class model extends main_model {


	public function post_priceback() {
		$usersid = $this->xuId("usersid");
		$classesid = $this->xuId("classesid");

		//----------------------- check branch
		$x =$this->sql(".branch.users", $usersid);

		$y =  $this->sql(".branch.classes", $classesid);

		if($x != $y) {
			debug_lib::fatal("branch not match");
		}
			
		
		$price = $this->sql()->tablePrice()
			->whereUsers_id($usersid)
			->andPay_type("rule")
			->andStatus("active")
			->andTitle($this->sql(".price.get_price_change", "شرکت در کلاس"))
			->andTransactions($classesid)
			->select();

		if($price->num() >= 1) {
			
			$sum_price = 0;
			foreach ($price->allAssoc() as $key => $value) {
			
				$back = $this->sql()->tablePrice()
					->setUsers_id($usersid)
					->setDate($this->dateNow())
					->setValue($value['value'])
					->setPay_type("rule")
					->setCard("000")
					->setTitle($this->sql(".price.get_price_change","انتقال از دوره قبل به دوره جدید"))
					->setTransactions($value['id'])
					// ->setStatus("void")
					->insert()->LAST_INSERT_ID();
				$this->commit(function(){
					debug_lib::msg("msg","شهریه به دوره جدید منتقل شد");
				});
			}
		}else{
			debug_lib::msg("msg", "هیچ شهریه ای برای این کلاس ثبت نشده است.");
		}
		debug_lib::msg("msg", "هیچ شهریه ای برای این کلاس ثبت نشده است.");

		
	}




	public function post_classificationapi() {

		
		$id = $this->xuId("id");
		$date = $this->xuId("date");
		$because = $this->xuId("because");
		$usersid = $this->xuId("usersid");
		$classesid = $this->xuId("classesid");
		
		$this->sql(".classification.remove", $usersid, $classesid, $id, $because, $date);
		

		if(debug_lib::$status) {
			debug_lib::true("به روز رسانی اطلاعات انجام شد");
		}else{
			debug_lib::fatal("خطا در حذف از کلاس");
		}


	}

	public function post_price() {
		$usersid = $this->xuId("usersid");
		$classesid = $this->xuId("classesid");


		//----------------------- check branch
		$this->sql(".branch.users", $usersid, $this->sql(".branch.classes", $classesid));
		
		$price = $this->sql()->tablePrice()
			->whereUsers_id($usersid)
			->andPay_type("rule")
			->andStatus("active")
			->andTitle($this->sql(".price.get_price_change","شرکت در کلاس"))
			->andTransactions($classesid)
			->select();

		$sum_price = 0;
		if($price->num() >= 1) {
			foreach ($price->allAssoc() as $key => $value) {
				$sum_price = $sum_price + intval($value['value']);
			}
		}
		debug_lib::msg("sum_price", $sum_price);
	}

	public function post_insert() {
		
		//------------------------------ set users id and classes id
		$users_id   = config_lib::$surl["usersid"];
		$classes_id = config_lib::$surl["classesid"];

		$add_or_returnclasses = $this->xuId("type");
		// $name_famil 

		$y = $this->sql(".branch.classes", $classes_id);
		$x = $this->sql(".branch.users", $users_id, $y);
		list($status, $msg) = $this->sql(".classification.insert", $users_id, $classes_id, $add_or_returnclasses);
		
		// var_dump($x, $y);exit();

		if($status) {
			$this->commit(function() {
				debug_lib::true("فراگیر به کلاس اضافه شد");	
			});
		}else{
			$this->rollback(function() {
				debug_lib::fatal("ثبت اطلاعات ناموفق");	
			});
		}


	}
}
?>