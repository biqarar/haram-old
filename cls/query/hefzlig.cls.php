<?php
class query_hefzlig_cls extends query_cls
{
// 4

	/**
	 * Sets the running.
	 *
	 * @param      boolean  $race_id  The race identifier
	 */
	public function set_running($race_id = false)
	{
		$check = $this->sql()->tableHefz_race()->whereId($race_id);
		if($check->select()->assoc("status") == "ready")
		{
			$check->setStatus("running")->update();
		}
	}



	/**
	 * the race result
	 *
	 * @param      boolean  $race_id  The race identifier
	 *
	 * @return     array    ( description_of_the_return_value )
	 */
	public function race_result($race_id = false)
	{

		//-----------------------------------------------
		$query = $this->sql()->tableHefz_race_result()->whereHefz_race_id($race_id);
		$query->joinHefz_teamuser()->whereId("#hefz_race_result.hefz_teamuser_id");
		$query->joinPerson()->whereUsers_id("#hefz_teamuser.users_id")->fieldName("personname")->fieldFamily("family");
		$query = $query->select()->allAssoc();


		//-----------------------------------------------
		$team_count_query = $this->sql()->tableHefz_race()->whereId($race_id)->select()->assoc();

		$manfi = array();
		$manfi[$team_count_query['hefz_team_id_1']] = $team_count_query['manfi1'];
		$manfi[$team_count_query['hefz_team_id_2']] = $team_count_query['manfi2'];


		$team_count = array();
		$team_count[$team_count_query['hefz_team_id_1']] = count(preg_split("/\,/",$team_count_query['presence1'])) - 1;
		$team_count[$team_count_query['hefz_team_id_2']] = count(preg_split("/\,/",$team_count_query['presence2'])) - 1;
		// $team_count[$team_count_query['hefz_team_id_1']] =
		// 	$this->sql()->tableHefz_teamuser()->whereHefz_team_id($team_count_query['hefz_team_id_1'])->select()->num();

		// $team_count[$team_count_query['hefz_team_id_2']] =
		// 	$this->sql()->tableHefz_teamuser()->whereHefz_team_id($team_count_query['hefz_team_id_2'])->select()->num();
		// var_dump($team_count);
		// var_dump($team_count_query);exit();

		//-----------------------------------------------
		$result = array();

		foreach ($query as $key => $value)
		{
			$result[$value['hefz_team_id']]['teamid'] = $value['hefz_team_id'];

			if(isset($result[$value['hefz_team_id']]['value']))
			{
				$result[$value['hefz_team_id']]['value'] += intval($value['value']);
			}
			else
			{
				$result[$value['hefz_team_id']]['value'] = intval($value['value']);
			}
		}

		//-----------------------------------------------
		foreach ($result as $key => $value)
		{

			if($team_count[$key] == 0 )
			{
				$team_count[$key] = 1;
			}

			// $d = (intval($value['value']) * 100) / (5 * 2 * intval($team_count[$key]));
			$d = (intval($value['value']) * 100) / ((4 + 7) * intval($team_count[$key]));

			switch ($d)
			{
				case ($d < 20) :
					$result[$key]['rate'] = 0;
					break;
				case ($d >= 40 && $d < 60) :
					$result[$key]['rate'] = 1;
					break;
				case ($d >= 60 && $d < 100) :
					$result[$key]['rate'] = 2;
					break;
				case ($d == 100) :
					$result[$key]['rate'] = 3;
					break;
				default :
					$result[$key]['rate'] = 0;
					break;

			}

			$result[$key]['main_result'] = round(floatval($d), 2);
			$result[$key]['result'] =" ٪ " . round(floatval($d), 2);
		}

		foreach ($result as $key => $value)
		{

			$result[$key]['main_result'] = $result[$key]['main_result'] - (intval($manfi[$key]) * 1);
			$result[$key]['result'] = " ٪ " .  $result[$key]['main_result'];
			// $result[$key]['value'] = $result[$key]['value'] - $manfi[$key];
			$result[$key]['manfi'] = $manfi[$key];
		}

		//-----------------------------------------------
		$rate = array();
		$id = 0;
		foreach ($result as $key => $value)
		{
			// print_r($result);
			$rate[$id]['rate'] = $result[$key]['rate'];
			$rate[$id]['id'] = $key;
			$id++;
		}

		//-----------------------------------------------
		if(isset($rate[0]))
		{
			$this->rate($result);
		}

		//-----------------------------------------------
		return  $result;
		var_dump($result);
		exit();
	}


	/**
	 * rating
	 *
	 * @param      <type>  $result  The result
	 */
	public function rate(&$result)
	{
		if(count($result) < 2)
		{
			return ;
		}
		$team = array();
		$team[] =  $result[array_keys($result)[0]];
		$team[] =  $result[array_keys($result)[1]];

		$r1 = $team[0]['main_result'];
		$r2 = $team[1]['main_result'];
		$s1 = 0;
		$s2 = 0;

		if($r1 == $r2)
		{

			if($r1 >= 20 && $r1 < 60)
			{
				$s1 = 1;
				$s2 = 1;
			}

			if($r1 >= 60 && $r1 < 100)
			{
				$s1 = 2;
				$s2 = 2;
			}

			if($r1 == 100 )
			{
				$s1 = 3;
				$s2 = 3;
			}
		}

		if($r1 < $r2)
		{
			if($r1 < 20 && $r2 > 20)
			{
				$s1 = 0;
				$s2 = 3;
			}
			if($r1 >= 20 && $r1 < 40 )
			{
				$s1 = 0;
				$s2 = 3;
			}
			if($r1 >= 40 && $r1 < 60 )
			{
				$s1 = 1;
				$s2 = 3;
			}
			if($r1 >= 60 && $r1 < 100 )
			{
				$s1 = 2;
				$s2 = 3;
			}

		}

		if($r2 < $r1)
		{
			if($r2 < 20 && $r1 > 20)
			{
				$s2 = 0;
				$s1 = 3;
			}
			if($r2 >= 20 && $r2 < 40 )
			{
				$s2 = 0;
				$s1 = 3;
			}
			if($r2 >= 40 && $r2 < 60 )
			{
				$s2 = 1;
				$s1 = 3;
			}
			if($r2 >= 60 && $r2 < 100 )
			{
				$s2 = 2;
				$s1 = 3;
			}
		}
		$result[array_keys($result)[0]]['rate'] = $s1;
		$result[array_keys($result)[1]]['rate'] = $s2;
	}
}
?>