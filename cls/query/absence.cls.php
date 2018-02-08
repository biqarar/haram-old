<?php
class query_absence_cls extends query_cls
{
	public $users_id,$classes_id,$classification_id;

	public function insert($classification = false, $type = false, $date = false)
	{
		$check = $this->sql()->tableAbsence()->whereClassification_id($classification)->andDate($date)->limit(1)->select()->num();

		if($check == 1)
		{
			debug_lib::fatal("برای این فراگیر در این تاریخ غیبت ثبت شده است");
		}
		else
		{
			//--------------- check if this classification id in the users branch or no.
			$this->sql(".branch.classification", $classification);

			$x = $this->sql()->tableAbsence()
						->setClassification_id($classification)
						->setType($type)
						->setDate($date)
						->insert();

			$this->autoremove($classification, $date);

			debug_lib::true(_($type) .  " ثبت شد");
		}
	}

	/**
	 * procces the absence code
	 *
	 * @param      array|boolean  $array  The array
	 *
	 * @return     integer        ( description_of_the_return_value )
	 */
	public function absence_count($array = false)
	{
		$c = 0;
		foreach ($array as $key => $value)
		{
			if(isset($value['status']) && isset($value['type']) && $value['status'] == 'enable')
			{
				switch ($value['type'])
				{
					case 'delay':
						$c = $c + 0;
						break;

					case 'leave':
						$c = $c + 0.5;
						break;

					case 'justified absence':
						$c = $c + 0.5;
						break;

					case 'unjustified absence':
						$c = $c + 1;
						break;
				}
			}
		}
		return $c;
	}


	/**
	 * auto remove
	 *
	 * @param      boolean  $classification_id  The classification identifier
	 * @param      boolean  $date               The date
	 */
	public function autoremove($classification_id = false, $date = false)
	{
		// return;
		$default_date = $date;
		if(preg_match("/^\d{8}$/", $date))
		{
			$date = substr($date,0,4). '-'. substr($date,4,2). '-'. substr($date, 6,2);
		}

		$this->classification_id = $classification_id;

		$max_absence = $this->sql()->tableClassification()->whereId($this->classification_id);
		$max_absence->joinClasses()->whereId("#classification.classes_id")->fieldPlan_id("planid");
		$max_absence->joinPlan()->whereId("#classes.plan_id")
								->fieldAbsence("max_absence")
								->fieldAbsence_type("absence_type");

		$max_absence = $max_absence->select()->assoc();

		$this->users_id   = $max_absence['users_id'];
		$this->classes_id = $max_absence['classes_id'];
		$absence_type     = $max_absence['absence_type'];

		if($absence_type == "ماهیانه")
		{
			$x = preg_split("/\-/", $date);
			if(!isset($x[0]) || !isset($x[1]))
			{
				return false;
			}

			$y = $x[0];
			$m = (int) $x[1];

			$m = $m < 10 ? '0'. $m : $m;

			$start_day = "{$y}{$m}01";
			$end_day   = "{$y}{$m}31";

			$absenc_list = $this->sql()->tableAbsence()
										->whereClassification_id($this->classification_id)
										->condition("and", "absence.date", ">=", "$start_day")
										->condition("and", "absence.date", "<=", "$end_day")
										->select();

			$c = $this->absence_count($absenc_list->allAssoc());

			$this->remove($c, $max_absence['max_absence']);
		}
		else
		{

			$absence_count = $this->sql()->tableAbsence()->whereClassification_id($this->classification_id)->select();

			$c = $this->absence_count($absence_count->allAssoc());

			$this->remove($c, $max_absence['max_absence']);
		}
	}


	/**
	 * remove users from classes
	 *
	 * @param      boolean  $c      { parameter_description }
	 * @param      boolean  $max    The maximum
	 */
	public function remove($c =false, $max = false)
	{
		if(intval($c) > intval($max))
		{
				$this->sql(".classification.remove",
							$this->users_id,
							$this->classes_id,
							$this->classification_id ,
							"absence" ,
							$this->dateNow());
				$query =
				"
					UPDATE absence
					INNER JOIN classification ON classification.id = absence.classification_id AND
					classification.classes_id = '$this->classes_id' AND
					classification.users_id   = '$this->users_id'
					SET absence.status = 'disable'
				";
				$this->db($query)->result();


			debug_lib::true("<a style='color:red'>فراگیر به دلیل غیبت بیش از حد مجاز از کلاس حذف شد</a>");

		}
	}

}
?>