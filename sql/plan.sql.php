<?php
namespace sql;
class plan {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'plan_id');
	public $group_id    = array('type'=> 'int@10', 'label' => 'group_id');
	public $name        = array('type'=> 'varchar@32', 'label' => 'plan_name');
	public $price       = array('type'=> 'int@7', 'label' => 'plan_price');
	public $absence     = array('type'=> 'int@2', 'label' => 'plan_absence');
	public $absence_type= array('type'=> 'enum@ترمی,ماهیانه!ترمی', 'label' => 'plan_absence_type');
	public $certificate = array('type'=> 'enum@yes,no!yes', 'label' => 'plan_certificate');
	public $mark        = array('type'=> 'float@', 'label' => 'plan_mark');
	public $rule        = array('type'=> 'int@10', 'label' => 'plan_rule');
	public $min_person  = array('type'=> 'int@3', 'label' => 'plan_min_person');
	public $max_person  = array('type'=> 'int@4', 'label' => 'plan_max_person');
	public $meeting_no 	= array("type" => "int@3", 'label' => "plan_meeting_no");
	public $payment_count = array('type' => 'int@2' , "label" => "payment_count");
	public $status		= array('type'=> 'enum@open,close,full!close', 'label' => 'plan_status');
	public $type = array('type'=> 'enum@free,test,exam!test', 'label' => 'plan_type');

	public $unique = array("group");
	public $index = array("group_id");

	public $foreign = array("group_id" => "group@id!name");

	public function id() {
		$this->validate("id");
	}

	public function group_id() {
		$this->form("select")->name("group_id")->required();
		$this->setChild(function($q){

			if(!isset($_SESSION['supervisor'])){

				$list = isset($_SESSION['my_user']['branch']['selected']) ?
							  $_SESSION['my_user']['branch']['selected'] : array();

				// if(global_cls::supervisor()) exit();
							  // var_dump($_SESSION);exit();
				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "group.branch_id","=",$value);
					}else{
						$q->condition("or","group.branch_id","=",$value);
					}
				}
				$q->groupClose();

			}
		});
	}

	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->farsi()->form->farsi("plan name should be persian");
	}

	public function price() {
		$this->form("#price")->name("price")->id("no-icon-price");
		$this->validate()->number(3, 7)->form->number("entered price is not valid");
	}

	public function absence() {
		$this->form("#number")->name("absence")->required();
		$this->validate()->number(1, 2)->form->number("absences number is not valid");
	}

	public function absence_type() {
		$this->form("radio")->name("absence_type");
		$this->setChild($this->form);
	}

	public function certificate() {
		$this->form("radio")->name("certificate");
		$this->setChild($this->form);
	}

	public function mark() {
		$this->form("#number")->required()->name("mark")->addClass('slider-number')->min(0)->max(100);
		$this->validate()->number(1, 3)->form->number("plan score is not valid");
	}

	public function rule() {
		$this->validate("id");
	}

	public function min_person() {
		$this->form("#number")->name("min_person")->required();
		$this->validate()->number(1, 3)->form->number("minimum persons number is not valid");
	}

	public function max_person() {
		$this->form("#number")->name("max_person")->required();
		$this->validate()->number(1, 4)->form->number("maximum persons number is not valid");
	}

	public function meeting_no() {
		$this->form("#number")->name("meeting_no")->pl("تعداد جلسات");
		$this->validate()->number()->form->number("meeting_no is not valid");
	}

	public function type() {
		$this->form("radio")->name("type");
		$this->setChild($this->form);
	}

	public function status() {
		$this->form("radio")->name("status");
		$this->setChild($this->form);
	}

	// public function payment_count() {
	// 	$this->form("#number")->name("payment_count")->label("دفعات پرداخت");
	// 	$this->validate()->number()->form->number("payment_count is not valid");
	// }
}
?>