<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
class model extends main_model{
	public function sql_find_teacher_name($users_id = 0)
	{

		//----------------- check branch
		$this->sql(".branch.person", $users_id);

		$return = $this->sql()->tablePerson()->whereUsers_id($users_id)->fieldName()->fieldFamily()->limit(1)->select()->assoc();
		return $return['name'] . ' ' . $return['family'];
	}

	public function makeQuery()
	{

		$week_days = post::week_days();
		if(!empty($week_days) && is_array($week_days))
		{
			$week_days = join($week_days, ",");
		}

		//------------------------check branch
		$branch_id = $this->sql(".branch.plan",post::plan_id());
		// echo $branch_id . "\n";
		// echo $this->sql(".branch.place",post::place_id()) . "\n";
		// echo $this->sql(".branch.users",post::teacher(), $branch_id) . "\n";
		// exit();
		if($branch_id == $this->sql(".branch.place",post::place_id()))
		{
			$this->sql(".branch.users",post::teacher(), $branch_id);
		}
		else
		{
			debug_lib::fatal("branch, place and teacher branch not mathc");
		}


		//------------------------------ make sql object
		return $this->sql()->tableClasses()
				// ->setCourse_id(post::course_id())
				->setPlan_id(post::plan_id())
				->setMeeting_no(post::meeting_no())
				->setAge_range(post::age_range())
				->setQuality(post::quality())
				->setPlace_id(post::place_id())
				->setName(post::name())
				->setStart_time("#'".post::start_time() . "'")
				->setEnd_time("#'".post::end_time(). "'")
				->setTeacher(post::teacher())
				->setStart_date(post::start_date())
				->setEnd_date(post::end_date())
				->setType(post::type())
				->setWeek_days($week_days)
				->setStatus("ready");
	}

	public function post_add_classes()
	{
		//------------------------------ check duplicate classes
		$this->check_duplication("insert");

		//------------------------------ insert classes
		$sql = $this->makeQuery()->insert();
		$insert_id = $sql->LAST_INSERT_ID();
		//------------------------------ commit code
		$this->commit(function($insert_id)
		{
			$link = $this->tag("a")
				->href("classification/class/classesid=".$insert_id)
				->vtext("اطلاعات کلاس با موفقیت ثبت شد.\n کد کلاس : $insert_id ")
				->style("color:blue")
				->render();
			debug_lib::true($link);
		},$insert_id);

		//------------------------------ rollback code
		$this->rollback(function()
		{
			debug_lib::fatal("[[insert classes failed]]");
		});
	}

	public function post_edit_classes()
	{

		//---------------------- chekc branch
		$this->sql(".branch.classes", $this->xuId());

		//------------------------------ check duplicate classes
		$this->check_duplication("update");

		//------------------------------ update classes
		$sql = $this->makeQuery()->whereId($this->xuId())->update();


		//------------------------------ commit code
		$this->commit(function()
		{
			debug_lib::true("[[update classes successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function()
		{
			debug_lib::fatal("[[update classes failed]]");
		});
	}

	public function check_duplication($type = false)
	{
		//------------------------------ duplicate key
		$duplicate = false;

		//------------------------------  start tiem request
		$start_time = post::start_time();
		$start_time = $this->convert_time($start_time);

		//------------------------------  end time request
		$end_time   = post::end_time();
		$end_time = $this->convert_time($end_time);

		//------------------------------  start date request
		$start_date = post::start_date();
		$start_date = $this->convert_date($start_date);

		//------------------------------ end date request
		$end_date   = post::end_date();
		$end_date = $this->convert_date($end_date);


		//--------------- check branch
		$this->sql(".branch.place", post::place_id());

		//------------------------------  place id
		$place      = post::place_id();

		//------------------------------  week days
		$week_days  = post::week_days();
		$week_days  = is_array($week_days) ? $week_days : array();
		//------------------------------ status
		$status     = post::status();

		//------------------------------ save duplicate detail to show
		$classes_detail = array();

		//------------------------------ sql result for status && place_id query
		$class = $this->sql()->tableClasses()
				->groupOpen()
				->whereStatus("ready")->orStatus("running")
				->groupClose()
				->groupOpen()
				->condition("and", "#start_date", "<=" , $end_date)
				->condition("and", "#end_date", ">=" , $start_date)
				->groupClose()
				->andPlace_id($place);


		$class->joinPlan()->whereId("#classes.plan_id");
		//---------- get branch id in the list
		foreach ($this->branch() as $key => $value)
		{
			if($key == 0)
			{
				$class->condition("and", "plan.branch_id","=",$value);
			}
			else
			{
				$class->condition("or","plan.branch_id","=",$value);
			}
		}
		$class->joinPlace()->whereId("#classes.place_id")->fieldMulticlass();
		$class = $class->select();

		//------------------------------  if in this place classes and ready or running
		if($class->num() > 0 )
		{

			$allClass = $class->allAssoc();

			foreach ($allClass as $key => $value)
			{

				//------------------------------ save duplicate detail to show
				$classes_detail = $value;
					//------------------------------ check week days of exist classes and request classes
					$week_days_exist = (preg_match("/\,/", $value['week_days'])) ? preg_split("/\,/", $value['week_days']) : array();

					foreach ($week_days as $k => $v)
					{

						if(preg_grep("/" . $v . "/", $week_days_exist))
						{

							//------------------------------ check time of exist classes and request classes
							$start_time_exist = $this->convert_time($value['start_time']);
							$end_time_exist = $this->convert_time($value['end_time']);

							if ($end_time_exist > $start_time && $start_time_exist < $end_time)
							{

								//------------------------------ duplicate item here !!!
								//------------------------------ can not insert or update classes
								if($type == "update" && $this->xuId() == $value['id'])
								{
									//------------------------------ himself
									$duplicate = false;
								}
								elseif
									($value['multiclass'] == 'yes')
								{
									//------------------------------ multiclass
									$duplicate = false;
								}
								else
								{
									//------------------------------ duplicate
									$duplicate = true;
								}
							}
						}
						if($duplicate) break;
					}
				if($duplicate) break;
			}
		}

		if($duplicate)
		{
			debug_lib::fatal(
				" اطلاعات این کلاس با کلاس شماره "
				. $classes_detail['id'] .
				" تداخل دارد، لطفا بررسی کنید "
				);
		}
		else
		{
			$new_classes = array();
			$new_classes['start_date'] = $start_date;
			$new_classes['end_date']   = $end_date;
			$new_classes['start_time'] = $start_time;
			$new_classes['end_time']   = $end_time;
			$new_classes['week_days']  = $week_days;
			$new_classes['classes_id'] = $this->xuId("id");
			list($duplicate, $msg) = $this->sql(".duplicateUsersClasses.teacher" , post::teacher() , $new_classes);
			if($duplicate)
			{
				debug_lib::fatal(
				" استاد در این ساعت در کلاس شماره "
				. $msg .
				" تدریس دارد "
				);
			}

		}
	}

	public function convert_time($time = false)
	{
		$nTime = preg_replace("/\:|\s|\-/", "", $time);
		if(strlen($nTime) < 6)
		{
			$nTime = $nTime . "00";
		}
		return intval($nTime);
	}

	public function convert_date($date = false)
	{
		if (!preg_match("/^(\d{4})(\-|\/|)(\d{1,2})(\-|\/|)(\d{1,2})$/", $date, $nDate))
		{
			return false;
		}
		else
		{
			$date = $nDate[1]
			.
			((intval($nDate[3]) < 10) ? "0".intval($nDate[3]) : intval($nDate[3]))
			.
			((intval($nDate[5]) < 10) ? "0".intval($nDate[5]) : intval($nDate[5]));
		}
		return $date;
	}

	/**
	* @return array (liset of users whit teacher type)
	*/
	function sql_users_name_family()
	{
// var_dump("fuc");exit();
		$x = $this->sql()->tableUsers();
		$x->whereType('teacher');
		// ->andStatus("enable"); // fase 2
		$x->joinPerson()->whereUsers_id("#users.id");

		$x->joinUsers_branch()->whereUsers_id("#users.id");

		//---------- get branch id in the list
		foreach ($this->branch() as $key => $value)
		{
			if($key == 0)
			{
				$x->condition("and", "users_branch.branch_id","=",$value);
			}
			else
			{
				$x->condition("or","users_branch.branch_id","=",$value);
			}
		}

		$y = $x->select()->allAssoc();

		$ret = array();
		foreach ($y as $key => $value)
		{
			$ret[$value['users_id']] = $value;
		}
		return $ret;
	}
}
 ?>