<?php
namespace sql;
class price {
	public $type    	 = array('type'=> 'enum@common,plan!commod', "label" => "type");

	public $plan_id    	 = array('type'=> 'int@10', "label" => "plan_id");

	public $id           = array('type'=> 'int@10', 'autoI', 'label' => 'price_id');
	public $users_id     = array('type'=> 'int@10', "label" => "users_id");
	public $date		 = array("type" => "int@8", "label" => "date");
	public $title		 = array('type' => "int@10", "price_title");
	public $pay_type     = array('type'=> 'enum@pos_mellat,pos_melli,rule', "label" => "pay_type");
	public $value        = array('type'=> 'int@7', "label" => "price_value");
	public $card         = array('type'=> 'int@7', "label" => "card");
	public $transactions = array('type'=> 'varchar@255', "label" => "price_transactions");
	public $description  = array('type'=> 'text@' , "label" => "description");
	public $status    	 = array('type'=> 'enum@active,void!active', "label" => "status");
	public $visible    	 = array('type'=> 'int@1', "label" => "visible");

	public $foreign = array("title" => "price_change@id!name", "plan_id" => "plan@id!name");

	public function id(){
		$this->validate("id");
	}

	public function users_id(){
		$this->validate("id");
	}

	public function date() {
		$this->form("#date")->name("date")->label("date")->required();
		$this->validate()->date()->form->date("date incorect");
	}

	public function title(){
		$this->form("select")->name("title")->id("title")->addClass("select-title notselect")->label("type");
		$this->setChild(function($q){

		// 	$list = isset($_SESSION['my_user']['branch']['selected']) ?
		// 				  $_SESSION['my_user']['branch']['selected'] : array();
		// 	$q->groupOpen();
		// 	foreach ($list as $key => $value) {
		// 		if($key == 0){
		// 			$q->condition("where", "price_change.branch_id","=",$value);
		// 		}else{
		// 			$q->condition("or","price_change.branch_id","=",$value);
		// 		}
		// 	}
		// 	$q->groupClose();
		// 	// $q->joinPrice_change()->whereId("#price.title")->fieldType('type');
		}, function($child, $value){
			// var_dump($value);exit();
			$child->label(gettext($value['type']) . " > " .  $value['name'])->value($value['id']);
		});
	}

	public function type(){
		$this->form("radio")->name("type")->label("type");
		$this->setChild($this->form);
	}

	public function plan_id(){
		$this->form("select")->name("plan_id")->label("plan_id")->class("notselect");

		$this->setChild(function($q){
			$list = isset($_SESSION['my_user']['branch']['selected']) ?
						  $_SESSION['my_user']['branch']['selected'] : array();
			$q->groupOpen();
			foreach ($list as $key => $value) {
				if($key == 0){
					$q->condition("where", "plan.branch_id","=",$value);
				}else{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}
			$q->groupClose();
		}, function($child,$value){
			$child->label($value['name'])->value($value['id']);
		});
	}

	public function card(){
		$this->form("text")->name("card")->label("card")->pl("4 رقم آخر شماره کارت")->required();
		$this->validate()->number()->form->number("price card is not valid");
	}

	public function value(){
		$this->form("text")->name("value")->label("مبلغ")->pl("به ریال")->required();
		$this->validate()->price()->form->price("price value is not valid");
	}

	public function pay_type(){
		$this->form("radio")->name("pay_type")->label("pay_type");
		$this->setChild($this->form);
	}

	public function transactions(){
		$this->form("text")->name("transactions")->label("transactions")->required();
		$this->validate()->transactions()->form->transactions("transactions is not valid");
	}

	public function description() {
		$this->form("#text_desc")->name("description");
		$this->validate()->description(-1)->form->description("description must be between 3 and 255 charset");
	}

	public function status(){
		$this->form("radio")->name("status")->label("status");
		$this->setChild($this->form);
	}

	public function visible() {
		// $this->form("text")->name("card")->label("card")->pl("4 رقم آخر شماره کارت");
		$this->validate()->number()->form->number("price visible is not valid");
	}

}
?>