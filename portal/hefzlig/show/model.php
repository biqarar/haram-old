<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model
{

	public $lig_id, $mr = array();
	public $p = 0;


	/**
	 * get the result
	 *
	 * @param      boolean  $lig_id  The lig identifier
	 *
	 * @return     array    ( description_of_the_return_value )
	 */
	public function sql_result($lig_id = false)
	{

		$this->lig_id = $lig_id;
		$lig_name = $this->sql()->tableHefz_ligs()->whereId($lig_id)->limit(1)->select()->assoc("name");
		$teams = $this->sql()->tableHefz_teams()->whereLig_id($lig_id)->groupbyHefz_group_id();
		$teams->joinHefz_group()->whereId("#hefz_teams.hefz_group_id")->fieldName("groupname");
		$teams = $teams->select()->allAssoc();
		$return = array();
		foreach ($teams as $key => $value)
		{
			$return[] = $this->team_result($value['hefz_group_id'], $value['groupname']);
		}

		return $return;
		var_dump($return);
		exit();
	}


	/**
	 * { function_description }
	 *
	 * @param      boolean  $group_id   The group identifier
	 * @param      boolean  $groupname  The groupname
	 *
	 * @return     array    ( description_of_the_return_value )
	 */
	public function team_result($group_id = false, $groupname = false)
	{
		$teams = $this->sql()
					  ->tableHefz_teams()
					  ->whereHefz_group_id($group_id)
					  ->select()
					  ->allAssoc();

		$result = array();
		$ar1    = array();
		$j      = 0;
		foreach ($teams as $key => $value)
		{
			$race_count = $this->sql()->tableHefz_race()
									->whereType("دوره ای")
									->andStatus("done")
									->groupOpen()
									->andHefz_team_id_1($value['id'])
									->orHefz_team_id_2($value['id'])
									->groupClose()
									->select()->num();

			$result[$j]['name']       = $value['name'];
			$result[$j]['race_count'] = $race_count;

			$r = $this->race_win($value['id'], $race_count);

			$result[$j]['race_win']  = $r['win'];
			$result[$j]['race_ower'] = $r['ower'];
			$result[$j]['race_req']  = $r['req'];

			if($race_count == 0) $race_count = 1;

			$result[$j]['average']   = round(floatval($r['average'] / $race_count),3);
			$result[$j]['race_rate'] = $r['rate'];
			$result[$j]['more']      = $this->tag("a")
											->href("hefzlig/raceteam/id=" . $value['id'])
											->class("icomore")
											->render();

			$result[$j]['id']    = $value['id'];
			$result[$j]['xrate'] = $r['rate'];
			$j++;

			$ar1[] = $r['rate'];
		}
		array_multisort($ar1,SORT_DESC,$result);

		// $this->fix($result);

		$ar1 = array();
		$finaly_result = array();
		foreach ($result as $key => $value)
		{
			$ar1[]                             = $value['xrate'];
			$finaly_result[$key]['name']       = $value['name'] ;
			$finaly_result[$key]['race_count'] = $value['race_count'] ;
			$finaly_result[$key]['race_win']   = $value['race_win'] ;
			$finaly_result[$key]['race_ower']  = $value['race_ower'] ;
			$finaly_result[$key]['race_req']   = $value['race_req'] ;
			$finaly_result[$key]['average']    = $value['average'] ;
			$finaly_result[$key]['race_rate']  = $value['race_rate'] ;
			$finaly_result[$key]['more']       = $value['more'] ;
		}

		array_multisort($ar1,SORT_DESC,$finaly_result);

		$return = array();
		$return['group_id'] = $group_id;
		$return['groupname'] = $groupname;
		$return['result'] = $finaly_result;
		ob_flush();
		flush();

		return $return;
		var_dump($return);
		exit();
	}

	public function fix(&$result)
	{
		// return;
		ob_flush();
		flush();
		$index = $result[0]['race_rate'];

		$to_fix = array();

		foreach ($result as $key => $value)
		{
			if($value['race_rate'] == 0) continue;

			if($value['race_rate'] == $index)
			{
				// 2 team ba ham barabaran
				$to_fix[$index][] = $value['id'];
			}
			else
			{
				$index = $value['race_rate'];
			}
		}


		$xto_fix = array();
		foreach ($to_fix as $key => $value)
		{
			if(is_array($value) AND count($value) < 2) continue;
			$xto_fix[] = $value;
		}

		if(empty($to_fix))
		{
			return;
		}
		$to_fix = $xto_fix;

		$ro_dar_ro = $this->sql()->tableHefz_race()->whereType("دوره ای")->andStatus("done");
		$ro_dar_ro->joinHefz_teams()->whereId("#hefz_race.hefz_team_id_1")->andLig_id($this->lig_id);

		for ($i=0; $i < count($to_fix) ; $i++)
		{

			$ro_dar_ro->groupOpen();
			if($i==0)
			{
				$ro_dar_ro->andHefz_team_id_1($to_fix[$i][0])->andHefz_team_id_2($to_fix[$i][1]);
			}
			else
			{
				$ro_dar_ro->orHefz_team_id_1($to_fix[$i][0])->andHefz_team_id_2($to_fix[$i][1]);
			}
			$ro_dar_ro->groupClose();
			$ro_dar_ro->groupOpen();
			$ro_dar_ro->orHefz_team_id_2($to_fix[$i][0])->andHefz_team_id_1($to_fix[$i][1]);
			$ro_dar_ro->groupClose();

		}

		$ro_dar_ro = $ro_dar_ro->select();

		if($ro_dar_ro->num() == 0)
		{
			// var_dump($to_fix);
			// var_dump($result);
			// miyangin darsan
			// $ar2 = array();
			foreach ($result as $key => $value)
			{
				foreach ($to_fix as $i => $teamid)
				{
					if($value['id'] == $teamid)
					{
						$result[$key]['xrate'] = $value['average'];
					}
					else
					{
						// $result[$key]['xrate'] = $value['average'];
					}
				}
				// $ar2[] = $value['average'];
			}
			// array_multisort($ar2,SORT_DESC,$result);

		}
		else
		{
			// // get race result
			$res = array();

			foreach ($ro_dar_ro->allAssoc() as $key => $value)
			{
				$a = $this->sql(".hefzlig.race_result", $value['id']);
				foreach ($a as $teamid => $teamresult)
				{
					if(isset($res[$teamid]))
					{
						$res[$teamid] = $res[$teamid]+ $teamresult['rate'];
					}
					else
					{
						$res[$teamid] =  $teamresult['rate'];

					}
				}
			}

			arsort($res);
			foreach ($result as $key => $value)
			{
				$i = count($result);
				foreach ($res as $f => $xrate)
				{
					if($value['id'] == $xrate)
					{
						$result[$key]['xrate'] = $result[$key]['xrate']  + $i;
						$i--;
					}
				}
			}
		}
	}

	public function race_win($team_id = false)
	{
		ob_flush();
		flush();
		$race = $this->sql()->tableHefz_race()
							 ->groupOpen()
							 ->whereHefz_team_id_1($team_id)
							 ->orHefz_team_id_2($team_id)
							 ->groupClose()
							 ->andType("دوره ای")
							 ->andStatus("done")
							 ->select()->allAssoc();
		$return            = array();
		$return['win']     = 0;
		$return['ower']    = 0;
		$return['req']     = 0;
		$return['average'] = 0;
		$return['rate']    = 0;

		foreach ($race as $key => $value)
		{

			$r = $this->sql(".hefzlig.race_result", $value['id']);
			if(count($r) < 2) continue;

			$return['average'] = $return['average'] + $r[$team_id]['main_result'];
			$return['rate'] = $return['rate'] + $r[$team_id]['rate'];

			if($r[$team_id]['rate'] == 3)
			{
				$return['win']++;
			}

			if($r[$team_id]['rate'] < 3)
			{
				$return['ower']++ ;
			}

			$t = array();
			$t[] =  $r[array_keys($r)[0]];
			$t[] =  $r[array_keys($r)[1]];

			$r1 = $t[0]['rate'];
			$r2 = $t[1]['rate'];
			if($r1 == $r2)
			{
				if($r1 == 3)
				{ // bord bord
					$return['win']++;
				}
				else
				{
					if($r1 == 0)
					{
						// اگر دو تیم زیر 30٪ خوانده باشند فقط باخت نمایش دهد نه هم باخت و هم مساوری
					}
					else
					{
						$return['req']++;
					}
				}
			}
		}
		return $return;
	}
}
?>