<?php 
/**
* 
*/
class model extends main_model {
	public function sql_progress($usersid = false, $classes_id = false, $type = "all") {
		$field = [];
		if($type == "month")
		{
			$group = " MONTH(score.date) ";
			$field[] = " MONTH(score.date) AS 'max' ";
		}
		else
		{
			$group = " score.date ";
			$field[] = " MIN(score.date) AS 'min' ";
			$field[] = " MAX(score.date) AS 'max' ";
		}

		$field[] = " AVG(score.value) AS 'average' ";
		$field[] = " score_type.title ";
		$field = join($field, ",");
		$query = 
		"
			SELECT
				$field
			FROM
				score
			
			INNER JOIN classification 
				  ON classification.id = score.classification_id AND
				     classification.users_id = $usersid
			INNER JOIN classes 
			      ON classes.id = classification.classes_id
			INNER JOIN score_type 
			      ON score_type.id = score.score_type_id
			WHERE 
				score_type.type = 'classroom'
			GROUP BY 
				$group, 
				score.score_type_id


		";
		// var_dump($query);
		$score_list = $this->db($query)->allAssoc();
		return $this->high_chart_mod($score_list);
	}


	public function high_chart_mod($result)
	{

		$categories = [];
		$series = [];
		foreach ($result as $key => $value) {
			if(!in_array($value['max'], $categories))
			{
				array_push($categories, $value['max']);
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