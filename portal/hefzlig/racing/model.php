<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model
{

	/**
	 * { function_description }
	 *
	 * @param      boolean  $race_id  The race identifier
	 *
	 * @return     <type>   ( description_of_the_return_value )
	 */
	public function sql_telavat($race_id = false)
	{
		$check = $this->sql()->tableLogs()->whereLog_meta("telavat_hefz_race=$race_id")->limit(1)->select();
		if($check->num() == 1)
		{
			return $check->assoc("log_data");
		}else{
			$insert = $this->sql()->tableLogs()
				->setLog_meta("telavat_hefz_race=$race_id")
				->setLog_data("telavat1")
				->insert()->result();
				// var_dump($race_id, $insert);	exit();
			return ($insert) ? "telavat1" : "-";
		}
	}

	public $team_count = false;
	public $teamid = false;
	public $j;
	public function team_count($teamid = false)
	{
		if($this->teamid != $teamid)
		{

			$this->teamid = $teamid;

			$this->team_count = $this->sql()
									 ->tableHefz_teamuser()
									 ->whereHefz_team_id($teamid)
									 ->select()
									 ->num();
			$this->j = $this->team_count;
		}
		return $this->team_count;

	}

	public function post_setsettings()
	{

		if(post::sort())
		{
			$sort = post::sort();

			foreach ($sort as $key => $value)
			{

				$ligid = $value[0];

				$teamid = $value[1];

				$teamusersid = $value[2];

				$team_count = $this->team_count($teamid);

				$update = $this->sql()
								->tableHefz_teamuser()
								->whereId($teamusersid)
								->setSort(intval($team_count) - $this->j--)
								->update();

			}

			$this->commit(function()
			{
				debug_lib::true("ترتیب تیم ها ذخیره شد");
			});
			$this->rollback(function()
			{
				debug_lib::fatal("خطا در ثبت ترتیب تیم");
			});

			// var_dump(1);exit();
			return ;
		}
		$race_id = $this->xuId("raceid");
		$type = $this->xuId("type");
		switch ($type)
		{
			case 'telavat1':
			case 'telavat2':
				$check = $this->sql()->tableLogs()->whereLog_meta("telavat_hefz_race=$race_id");
				if($check->select()->num() == 1)
				{
					$check->setLog_data($type)->update();
				}else{
					$insert = $this->sql()->tableLogs()
						->setLog_meta("telavat_hefz_race=$race_id")
						->setLog_data($type)
						->insert();
				}

				break;

			case 'setdone':
				$this->set_done($race_id);
				break;

		}

		$this->commit(function()
		{
			debug_lib::true("تغییرت تنظیمات انجام شد");
		});
		$this->rollback(function()
		{
			debug_lib::fatal("خطا در تغییر تنظیمات");
		});

	}

	public function set_done($race_id = false)
	{
		//----------------------------- save result

		$result = $this->sql(".hefzlig.race_result", $race_id);

		$query = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select()->assoc();

		//----------------------------- set result
		$setdone = $this->sql()->tableHefz_race()->whereId($race_id)

								->setResult1($result[$query['hefz_team_id_1']]['main_result'])
								->setResult2($result[$query['hefz_team_id_2']]['main_result'])

								->setRate1($result[$query['hefz_team_id_1']]['rate'])
								->setRate2($result[$query['hefz_team_id_2']]['rate'])

								->setStatus("done")

								->update();


	}

	public function sql_presence_list($race_id = false)
	{
		$check = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select()->assoc();
		return array($check['presence1'], $check['presence2']);
	}

	public function post_setpresence()
	{
		$race_id = $this->xuId("raceid");
		$team_id = $this->xuId("teamid");
		$teamusersid = $this->xuId("teamusersid");
		$checked = $this->xuId("checked");

		$check = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select()->assoc();

		if($check['hefz_team_id_1'] == $team_id)
		{
			$presence_field = "presence1";
		}elseif($check['hefz_team_id_2'] == $team_id)
		{
			$presence_field = "presence2";
		}else{
			debug_lib::fatal("خطا در تطابق شناسه تیم و مسابقه");
		}
		$field = "set"  . ucfirst($presence_field);
		if($checked == "true")
		{

			if(preg_match("/".$teamusersid."\,/", $check[$presence_field]))
			{
				//---------------------------------
				debug_lib::true("فراگیر در مسابقه فعال است");
			}else{

				$this->sql()->tableHefz_race()
					 ->$field($check[$presence_field] . $teamusersid . ",")
					 ->whereId($race_id)
					 ->update();
				debug_lib::true("فراگیر در مسابقه فعال شد");
			}

		}elseif($checked == "false")
		{

			if(preg_match("/".$teamusersid."\,/", $check[$presence_field]))
			{
				//---------------------------------
				$new_value = preg_replace("/".$teamusersid."\,/", "", $check[$presence_field]);
				$this->sql()->tableHefz_race()
					 ->$field($new_value)
					 ->whereId($race_id)
					 ->update();
				debug_lib::true("فراگیر در مسابقه غیر فعال شد");
			}else{
				debug_lib::true("فراگیر در مسابقه فعالیت ندارد");
			}

		}


		// var_dump($race_id,$team_id,$teamusersid, $checked);exit();
	}

		public function sql_get_value($teamusersid = false , $type = false , $race_id = false)
		{
			$type = ($type== "race1") ? "تلاوت اول" : "تلاوت دوم";
		return $this->sql()->tableHefz_race_result()
		                   ->whereHefz_teamuser_id($teamusersid)
		                   ->andType($type)
		                   ->andHefz_race_id($race_id)
		                   ->limit(1)
		                   ->select()
		                   ->assoc("value");
	}

	public function sql_insert_default_value($race_id = false )
	{
		$race = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select();
		if($race->num() == 1)
		{
			$race = $race->assoc();
			$this->insert_0($race_id, $race['hefz_team_id_1']);
			$this->insert_0($race_id, $race['hefz_team_id_2']);
		}

	}

	public function insert_0 ($race_id = false, $hefz_team_id = false)
	{
		$team1 = $this->sql()->tableHefz_teamuser()
								 ->whereHefz_team_id($hefz_team_id)
								 ->select()->allAssoc();

			foreach ($team1 as $key => $value)
			{

				$check = $this->sql()->tableHefz_race_result()
						->whereHefz_race_id($race_id)
						->andHefz_teamuser_id($value['id'])
						->select();

				if($check->num() == 0)
				{
					$insert = $this->sql()->tableHefz_race_result()
								->setHefz_race_id($race_id)
								->setHefz_teamuser_id($value['id'])
								->setType("تلاوت اول")->insert()->LAST_INSERT_ID();
					$insert = $this->sql()->tableHefz_race_result()
								->setHefz_race_id($race_id)
								->setHefz_teamuser_id($value['id'])
								->setType("تلاوت دوم")->insert()->LAST_INSERT_ID();
				}else{

					$check = $check->allAssoc();

					if(count($check) == 2)
					{
						continue;
					}elseif(count($check) == 1)
					{

						foreach ($check as $k => $v)
						{
							if($v['type'] == 'تلاوت اول')
							{
								$insert = $this->sql()->tableHefz_race_result()
										->setHefz_race_id($race_id)
										->setHefz_teamuser_id($value['id'])
										->setType("تلاوت دوم")->insert()->LAST_INSERT_ID();
							}else{
									$insert = $this->sql()->tableHefz_race_result()
										->setHefz_race_id($race_id)
										->setHefz_teamuser_id($value['id'])
										->setType("تلاوت اول")->insert()->LAST_INSERT_ID();
							}

						}

					}
				}

			}
	}

	public function post_insertmanfiapi()
	{
		$teamid= $this->xuId("teamid");
		$raceid = $this->xuId("raceid");
		$type = $this->xuId("type");
		$manfi = $this->sql()->tableHefz_race()->whereId($raceid)->select()->assoc();

		if($teamid == $manfi['hefz_team_id_1'])
		{
			$hefz_team_id = "hefz_team_id_1";
			$field = "manfi1";
		}elseif($teamid == $manfi['hefz_team_id_2'])
		{
			$hefz_team_id = "hefz_team_id_2";
			$field = "manfi2";
		}else{
			debug_lib::fatal("خدا در ثبت نمره منفی");
		}

		$value = intval($manfi[$field]);

		if($type == "up") $value++;
		if($type == "down") $value--;

		if($value >= 0)
		{
			$field = "set" . ucfirst($field);
			$set = $this->sql()->tableHefz_race()->whereId($raceid)->$field($value)->update();

		}


		$this->commit(function()
		{
			debug_lib::true("ثبت امتیاز  منفی انجام شد");
		});
		$this->rollback(function()
		{
			debug_lib::fatal("خطا در ثبت امتیاز منفی");
		});

	}

	public function post_resultapi()
	{
		$raceid = $this->xuId("raceid");
		debug_lib::msg("result", $this->sql(".hefzlig.race_result", $raceid));
	}

	public function post_insertapi()
	{

		$teamuserid= $this->xuId("teamuserid");
		$raceid = $this->xuId("raceid");
		$value = $this->xuId("value");
		$type = $this->xuId("type");
		if($type == "race1")
		{
			$type = "تلاوت اول";
		}elseif($type== "race2")
		{
			$type = "تلاوت دوم";
		}else{
			debug_lib::fatal("خطا در نوع تلاوت");
		}
		$check = $this->sql()->tableHefz_race_result()
			->whereHefz_race_id($raceid)
			->andHefz_teamuser_id($teamuserid)
			->andType($type);

		if($check->select()->num() == 0)
		{

			$query = $this->sql()->tableHefz_race_result()
				->setHefz_race_id($raceid)
				->setHefz_teamuser_id($teamuserid)
				->setType($type)
				->setValue($value)
				->insert();

		}else{
			$check = $check->setValue($value)->update();
			// var_dump($check->string());
		}
			// var_dump($query->string());exit();
		$this->commit(function()
		{
			debug_lib::true("ثبت امتیاز  مسابقه انجام شد");
		});
		$this->rollback(function()
		{
			debug_lib::fatal("خطا در ثبت امتیاز مسابقه");
		});
	}

	public function sql_find_team_id ($race_id = false )
	{
		$return = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select()->assoc();
		$team1 = $this->sql()->tableHefz_teams()->whereId($return['hefz_team_id_1'])->limit(1)->select()->assoc("name");
		$team2 = $this->sql()->tableHefz_teams()->whereId($return['hefz_team_id_2'])->limit(1)->select()->assoc("name");
		return array(
			array("id" => $return['hefz_team_id_1'], "name" => $team1),
			array("id" => $return['hefz_team_id_2'], "name" => $team2)
			);

	}

}
?>