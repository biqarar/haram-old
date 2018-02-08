<?php
/**
 *
 */
class view extends main_view
{

	/**
	 * config page
	 */
	public function config()
	{
		//------------------------------  global
		$this->global->page_title = _("خلاصه ی وضعیت کلاس");

		//----------------------- check banch
		$this->sql(".branch.classes",$this->xuId());
		//------------------------------  set classes_id
		$classes_id  = $this->xuId("id");
		//------------------------------ list of classes
		$classes_detail = $this->sql(".list", "classes", function ($query)
		{
			$query->whereId($this->xuId("id"));
		})->compile();
		if(isset($classes_detail['list'][0]['teacher']))
		{
			$teacher_id = $classes_detail['list'][0]['teacher'];
		}
		if(isset($classes_detail['list'])){
			foreach ($classes_detail ['list'] as $key => $value)
			{
				$classes_detail ['list'][$key]['plan_id']   = $this->sql(".assoc.foreign", "plan", $value["plan_id"], "name");
				$classes_detail ['list'][$key]['teacher']   =
				$this->sql(".assoc.foreign", "person", $value["teacher"], "name", "users_id") . ' ' .
				$this->sql(".assoc.foreign", "person", $value["teacher"], "family", "users_id");
				$classes_detail ['list'][$key]['place_id']  = $this->sql(".assoc.foreign", "place", $value["place_id"], "name");
			}
		}
		$this->data->classes_detail = $classes_detail;

		if($teacher_id)
		{
			$teacher_detail = $this->sql(".list", "person", function ($query, $teacher_id)
			{
				$query->whereUsers_id($teacher_id);
				$query->joinPerson_extera("LEFT")->whereUsers_id("#person.users_id");

			}, $teacher_id);

			$teacher_detail->removeCol("
			id,
			code,
			from,
			nationality,
			type,
			casecode,
			casecode_old,
			education_id,
			education_howzah_id,
			en_name,
			en_family,
			en_father,
			third_name,
			third_family,
			third_father,
			pasport_date,
			users_id,
			place_birth,
			child_daughter,
			child_son,
			dependents,
			soldiering,
			exemption_type,
			job,
			residence,
			health,
			treated,
			stature,
			weight,
			blood_group,
			disease,
			insurance_type,
			insurance_code,
			good_remember,
			bad_remember,
			tahqiq,
			tartil,
			tajvid");
			$teacher_detail = $teacher_detail->compile();
			$this->data->teacher_detail = $teacher_detail;
		}

		$query = "SELECT COUNT(id) AS 'id' FROM classification WHERE classes_id = $classes_id";
		$this->data->intro = intval($this->db($query)->assoc("id"));

		$query = "
			SELECT
				COUNT(id) AS 'id'
			FROM
				classification
			WHERE
				classes_id = $classes_id AND
				because IS NULL
			";
		$this->data->end_class = intval($this->db($query)->assoc("id"));


		$query = "
			SELECT
				COUNT(id) AS 'id'
			FROM
				classification
			WHERE
				classes_id = $classes_id AND
				because = 'move'
			";
		$this->data->move_classification = intval($this->db($query)->assoc("id"));
		$query = "
			SELECT
				COUNT(id) AS 'id'
			FROM
				classification
			WHERE
				classes_id = $classes_id AND
				because IS NOT NULL
			";
		$this->data->exit_class = intval($this->db($query)->assoc("id"));
		if($this->data->intro > 0)
		{
			$this->data->end_class_100 =
				round(floatval((($this->data->intro - $this->data->exit_class) * 100 / $this->data->intro - $this->data->move_classification)), 2);
		}
		else
		{
			$this->data->end_class_100 = "#";
		}

		$query = "
			SELECT
				COUNT(classification.id) AS 'id'
			FROM
				classification
			INNER JOIN score
				ON score.classification_id = classification.id
			INNER JOIN score_type
				ON score_type.id = score.score_type_id AND
				   score_type.type = 'endofterm'
			INNER JOIN classes
				ON classification.classes_id = classes.id
			INNER JOIN plan
				ON classes.plan_id = plan.id
			WHERE
				classification.mark >= plan.mark AND
				classification.classes_id = $classes_id AND
				classification.because IS NULL
			";
		$this->data->qaboli = intval($this->db($query)->assoc("id"));

		$query = "
			SELECT
				COUNT(classification.id) AS 'id'
			FROM
				classification
			INNER JOIN score
				ON score.classification_id = classification.id
			INNER JOIN score_type
				ON score_type.id = score.score_type_id AND
				   score_type.type = 'endofterm'
			INNER JOIN classes
				ON classification.classes_id = classes.id
			INNER JOIN plan
				ON classes.plan_id = plan.id
			WHERE
				classification.mark < plan.mark AND
				classification.classes_id = $classes_id
			";
		$this->data->mardodi = intval($this->db($query)->assoc("id"));

		$query = "
			SELECT
				meeting_no AS 'id'
			FROM
				classes
			WHERE
				classes.id = $classes_id
			";
		$this->data->aeenname = intval($this->db($query)->assoc("id"));


		$query = "
			SELECT
				AVG(score.value) AS 'id'
			FROM
				score
			INNER JOIN classification
			  	ON classification.id = score.classification_id
			INNER JOIN score_type
				ON score_type.id = score.score_type_id AND
					score_type.type = 'endofterm'
			WHERE
				classification.classes_id = $classes_id
			";
		$this->data->moadele_kol = intval($this->db($query)->assoc("id"));
		$this->data->qaboli_100 = 0;
		if($this->data->end_class != 0)
		{
			$this->data->qaboli_100 = round(floatval((100 * $this->data->qaboli) / $this->data->end_class));
		}
		$query = "
			SELECT
				AVG(score.value) AS 'id'
			FROM
				score
			INNER JOIN classification
			  	ON classification.id = score.classification_id
			INNER JOIN score_type
				ON score_type.id = score.score_type_id AND
					score_type.type != 'endofterm'
			WHERE
				classification.classes_id = $classes_id
			";
		$this->data->moadele_class = intval($this->db($query)->assoc("id"));


		$query = "
			SELECT
				COUNT(score.id) AS 'id'
			FROM
				score
			INNER JOIN classification
			  	ON classification.id = score.classification_id
			WHERE
				classification.classes_id = $classes_id
			GROUP BY score.date
			";

		$this->data->bargozarshode = count($this->db($query)->allAssoc("id"));

		$this->data->bargozarshode_100 = 0;
		if($this->data->bargozarshode != 0)
		{
			$this->data->bargozarshode_100 = round(floatval((100 * $this->data->bargozarshode) / $this->data->aeenname));
			if($this->data->bargozarshode_100 > 100)
			{
				$this->data->bargozarshode_100  = 100;
			}
		}
	}
}
?>