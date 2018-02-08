<?php 
/**
* 
*/
class model extends main_model {
	public function sql_classification($group_id = false)
	{
		$query = "
			SELECT
				COUNT(classification.id) AS 'average' ,
				CONCAT(person.name, ' ', person.family) AS 'name'
			FROM
				classification
			
			INNER JOIN classes ON classes.id = classification.classes_id
			INNER JOIN person ON person.users_id = classes.teacher
			INNER JOIN plan ON plan.id = classes.plan_id 
			INNER JOIN `group` ON `group`.`id` = plan.group_id AND `group`.`id` = $group_id
			
			GROUP BY 
				name
		";
		// var_dump($query);exit();
		$score_list = $this->db($query)->allAssoc();
		// var_dump($this->high_chart_mod($score_list));exit();
		return $this->high_chart_mod($score_list);
		
	}

	public function sql_progress($group_id = false) {
			
		$query = 
		"
			SELECT
				AVG(score.value) AS 'average' ,
				CONCAT(person.name, ' ', person.family) AS 'name'
			FROM
				score
			INNER JOIN classification ON classification.id = score.classification_id
			INNER JOIN classes ON classes.id = classification.classes_id
			INNER JOIN person ON person.users_id = classes.teacher
			INNER JOIN plan ON plan.id = classes.plan_id 
			INNER JOIN `group` ON `group`.`id` = plan.group_id AND `group`.`id` = $group_id
			
			GROUP BY 
				name
		";
	
		$score_list = $this->db($query)->allAssoc();
		// var_dump($this->high_chart_mod($score_list));exit();
		return $this->high_chart_mod($score_list);
	}


	public function high_chart_mod($result)
	{
		// var_dump($result)
		// ;exit();/
		$categories = [];
		$series = [];
		foreach ($result as $key => $value) {
			if(!in_array($value['name'], $categories))
			{
				array_push($categories, $value['name']);
			}

			if(!isset($series[$value['name']]))
			{
				$series[$value['name']] = [];
			}
			array_push($series[$value['name']], intval($value['average']));

		}
		$json = [];
		foreach ($series as $key => $value) {
			
			$json[] = 
			[
				'name' => $key,
				'data' => $value
			];
		}

		$return = [];
		$categories = json_encode($categories, JSON_UNESCAPED_UNICODE);
		$return['categories'] = $categories;
		$return['series'] = json_encode($json, JSON_UNESCAPED_UNICODE);
		return $return;
	}
}
?>