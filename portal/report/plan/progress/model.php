<?php 
/**
* 
*/
class model extends main_model {
	public function sql_progress($plan_id = false) {
			
		$query = 
		"
			SELECT
				AVG(score.value) AS 'average' ,
				CONCAT(person.name, ' ', person.family) AS 'name',
				score_type.title as 'title'
			FROM
				score
			INNER JOIN classification ON classification.id = score.classification_id
			INNER JOIN classes ON classes.id = classification.classes_id
			INNER JOIN score_type ON score_type.id = score.score_type_id
			INNER JOIN person ON person.users_id = classes.teacher
			INNER JOIN plan ON plan.id = classes.plan_id  AND plan.id = $plan_id
			
			GROUP BY 
				name,
				title,
				score_type.type
		";
	
		$score_list = $this->db($query)->allAssoc();
		// var_dump($this->high_chart_mod($score_list));exit();
		return $this->high_chart_mod($score_list);
	}


	public function high_chart_mod($result)
	{

		$categories = [];
		$series = [];
		foreach ($result as $key => $value) {
			if(!in_array($value['name'], $categories))
			{
				array_push($categories, $value['name']);
			}

			if(!isset($series[$value['title']]))
			{
				$series[$value['title']] = [];
			}
			array_push($series[$value['title']], intval($value['average']));

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