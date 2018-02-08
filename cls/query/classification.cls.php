<?php
class query_classification_cls extends query_cls
{

	public function detail($field = false , $id = false)
	{
		//------------- check branch
		$this->sql(".branch.classification", $id);

		return $this->sql()->tableClassification()->whereId($id)->limit(1)->select()->assoc($field);
	}

	public function insert($users_id, $classes_id, $add_or_returnclasses = "add")
	{

		//------------------------------ insert courseclasses
		$courseclasses = $this->sql(".courseclasses", $classes_id, $users_id);
		if(!$courseclasses)
		{
		//------------------------------ key for check duplicate
		$duplicate = false;
		//------------------------------ check for duplicate this classes inserted
			$check = $this->sql()->tableClassification()->whereUsers_id($users_id)->andClasses_id($classes_id)->limit(1)->select();

			if($check->num() == 0 || $add_or_returnclasses == "returnclasses")
			{

				//------------------------------ check duplicate other classes as time for this users
				list($duplicate, $msg) = $this->sql(".duplicateUsersClasses.classification", $users_id, $classes_id);

				// var_dump($duplicate,$msg);
			// var_dump($duplicate,$msg);exit();
				if($duplicate)
				{
					debug_lib::fatal($msg);
				}
				else
				{
					//------------------------------ check price
					if(!$this->sql(".plan.maxPerson", $classes_id))
					{
						// return array(false, "ظرفیت این کلاس تکمیل است و امکان ثبت فراگیر وجود ندارد.");
						debug_lib::fatal("ظرفیت این کلاس تکمیل است و امکان ثبت فراگیر وجود ندارد.");

					}
					elseif(!$this->sql(".price.checkClasses", $users_id , $classes_id))
					{

						// return array(false, "شهریه کافی نیست لفطا نسبت به شارژ حساب این فراگیر اقدام نمایید.");
						debug_lib::fatal("شهریه کافی نیست لفطا نسبت به شارژ حساب این فراگیر اقدام نمایید.");

					}
					elseif(!$this->sql(".pasportCheck", $users_id))
					{

						// return array(false, "اعتبار گذرنامه این فراگیر به اتمام رسیده است");
						debug_lib::fatal("اعتبار گذرنامه این فراگیر به اتمام رسیده است");

					}
					elseif(!$this->sql(".checkAge", $users_id, $classes_id))
					{
						// check age and classes age
						debug_lib::fatal("سن این فراگیر با مقطع سنی این کلاس تطابق ندارد");
					}
					else
					{

						//------------------------------ insert classification  or return classes
						if($add_or_returnclasses == "add")
						{
							//------------------------------ insert classification
							$classification = $this->sql()->tableClassification()
									->setUsers_id($users_id)
									->setClasses_id($classes_id)
									->setDate_entry($this->dateNow())
									->insert();

						}
						elseif($add_or_returnclasses == "returnclasses")
						{
							//------------------------------ return classification
							$classification = $this->sql()->tableClassification()
									->setDate_delete("")
									->setBecause("")
									->whereUsers_id($users_id)->andClasses_id($classes_id)
									->update()->string();
						}

						//------------------------------- set classification count in to classes table
						$this->sql(".classes.count", $classes_id);

							//------------------------------ commit code
						if(!$duplicate)
						{
							debug_lib::true("فراگیر به کلاس اضافه شد");
							// return true;
						}

					}
				}
			}
			else
			{

				$duplicate = true;
				// return array(false, "این فراگیر قبلا در کلاس ثبت شده است");

				debug_lib::fatal("این فراگیر قبلا در کلاس ثبت شده است");
			}
		}
		else
		{
			$type = $courseclasses['type'];
			if($type == 'fatal')
			{
				debug_lib::fatal($courseclasses['msg']);
			}
			else
			{
				debug_lib::true($courseclasses['msg']);
			}
		}
		// debug_lib::true("به روز رسانی اطلاعات با موفقیت انجام شد");

	}

	public function remove($usersid, $classesid,$classification_id = false, $because = false , $date = false)

	{

		if(!$date) $date = $this->dateNow();
		if(!$because) $because = "";
		//----------------------- check branch
		$x = $this->sql(".branch.users", $usersid, $this->sql(".branch.classes", $classesid));
		// var_dump($x);exit();
		// $this->remove_payment_count($usersid, $classesid);

		$price = $this->sql()->tablePrice()
			->whereUsers_id($usersid)
			->andPay_type("rule")
			->andStatus("active")
			->andTitle($this->sql(".price.get_price_change","شرکت در کلاس"))
			->andTransactions($classesid)
			->setStatus("void")->update();

		$classes_id = $this->sql()->tableClassification()->whereId($classification_id)->limit(1)->select()->assoc("classes_id");

		//----------------------- check branch
		$this->sql(".branch.classification", $classification_id);


		$classification =  $this->sql()->tableClassification()
			->setDate_delete($date)
			->setBecause($because)
			->whereId($classification_id)->update();

		$this->sql(".classes.count", $classes_id);

	}

	public function remove_payment_count($usersid = false, $classesid = false)
	{
		var_dump($usersid, $classesid);
		// //----------------------- check branch
		// $this->sql(".branch.users", $usersid, $this->sql(".branch.classes", $classesid));

		$del = $this->sql()->tablePrice()
			->whereUsers_id($usersid)
			->andVisible(0)
			->andTransactions($classesid)
			->andTitle($this->sql(".price.get_price_change","پرداخت دوره ای"))
			->delete();
			// var_dump($del->string());

	}

	public function move($oldclasses, $newclasses)
	{
		var_dump("f");exit();
		$old_list = $this->sql()->tableClassification()->whereClasses_id($oldclasses);
		$old_list = $this->classification_finde_active_list($old_list);
		$old_list = $old_list->select();
		// var_dump($old_list->num(), $old_list->allAssoc());exit();
		foreach ($old_list->allAssoc() as $key => $value)
		{
			// var_dump($value);
			$this->remove($value['users_id'], $value['classes_id'], $value['id']);
			$this->insert($value['users_id'], $value['classes_id']);
		}
		debug_lib::true("عملیات انتاق انجام شد");

		// var_dump($oldclasses,$newclasses);exit();
	}
}
?>