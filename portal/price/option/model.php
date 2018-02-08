<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function sql_find_usersid($score_id = false) {
		return $this->sql()->tablePrice()->whereId($score_id)->limit(1)->fieldUsers_id()->select()->assoc("users_id");
	}

	public function makeQuery() {
		// var_dump(post::title());exit();
		$value = preg_replace("/\,/", "", post::value());
		//--------------- check branch
		// $this->sql(".branch.price_change", post::title());

		$return = $this->sql()->tablePrice()
				->setDate(post::date())			
				->setValue($value)
				->setPay_type(post::pay_type())
				->setCard(post::card())
				->setTransactions(post::transactions())
				->setDescription(post::description());
				
			if(global_cls::superprice("rule")){
				$return->setTitle(post::title());
			}else{
				if(post::pay_type() == "rule"){
					debug_lib::fatal("شما مجوز لازم جهت ثبت شهریه از طریق آیین نامه را ندارید");
				}
				$return->setTitle($this->sql(".price.get_price_change","پرداخت شهریه"));
			}
			return $return;
	}

	public function check_duplicate_transactions($_users_id, $_transactions)
	{
		$sql = $this->sql()->tablePrice()->whereUsers_id($_users_id)->andTransactions($_transactions)
				->limit(1)->select()->num();
		if($sql > 0)
		{
			debug_lib::fatal("این تراکنش قبلا برای این فراگیر ثبت شده است");
		}
	}

	public function post_add_price(){
		
		

		//---------------- check branch
		$this->sql(".branch.users",$this->xuId("usersid"));
	
		$this->check_duplicate_transactions($this->xuId("usersid"), post::transactions());		

		$sql = $this->makeQuery();

		$sql->setUsers_id($this->xuId("usersid"));


		if(post::type() == 'plan' && post::plan_id() == '') {
			debug_lib::fatal("در حالت رزرو شهریه برای طرح حتما باید نام طرح ثبت شود.");
		}elseif(post::type() == "common" && post::plan_id() != ""){
			$sql->setDescription(post::description());
		}elseif(post::type() == 'plan' && post::plan_id() != '') {
			//---------------- check branch
			// $this->sql(".branch.users",$this->xuId("usersid") , $this->sql(".branch.plan", post::plan_id()));
			$x = $this->sql(".branch.users",$this->xuId("usersid"));
			$y = $this->sql(".branch.plan", post::plan_id());
			if($x == $y){
				$sql->setDescription($this->plan_name(post::plan_id()) . " - " . post::description());
			}
		}
		$sql = $sql->insert();
	
	
		$this->commit(function() {
			debug_lib::true("[[insert price successful]]");
		});
		
		$this->rollback(function() {
			debug_lib::fatal("[[insert price failed]]");
		});
	}

	public function plan_name($plan_id = false) {
		return $this->sql()->tablePlan()->whereId($plan_id)->limit(1)->fieldName()->select()->assoc("name");
	}

	public function post_edit_price(){
		$sql = $this->makeQuery();

		//----------------- check branch
		$this->sql(".branch.price" ,$this->xuId());

		if(post::type() == 'plan' && post::plan_id() == '') {
			debug_lib::fatal("در حالت رزرو شهریه برای طرح حتما باید نام طرح ثبت شود.");
		}elseif(post::type() == "common" && post::plan_id() != ""){
			$sql->setDescription(post::description());
		}elseif(post::type() == 'plan' && post::plan_id() != '') {
			//---------------- check branch
			$this->sql(".branch.plan", post::plan_id());
			
			$sql->setDescription($this->plan_name(post::plan_id()) . " - " . post::description());
		}
		$sql->whereId($this->xuId())->update();

		$this->commit(function() {
			debug_lib::true("[[update price successful]]");
		});
		
		$this->rollback(function() {
			debug_lib::fatal("[[update price failed]]");
		});
	}

	public function check_price() {

		$usersid  = $this->xuId("usersid");
		//----------------- check branch
		$this->sql(".branch.users" ,$this->xuId("usersid"));

		$value    = post::value();

		$title = post::title();

		$type = $this->sql()->tablePrice_change()->whereId($title)->limit(1)->select()->assoc("type");
		if($type == "price_low") {
			$sum = $this->sql(".price.sum_price", $usersid);
			if(intval($sum) < intval($value)){
				return false;
			}
		}
		return true;
	}
}
?>