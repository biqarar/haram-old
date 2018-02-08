<?php
class model extends main_model {

	public function post_api(){



		$classificationid = $this->xuId("classificationid");
		$scoretypeid      = $this->xuId("scoretypeid");
		$retest           = $this->xuId("retest");

		$date = $this->xuId("date");

		if($date === "00000000")
		{
			debug_lib::fatal("تاریخ نامعتبر است");
		}

		//----------------- check branch
		$branch_score_type = $this->sql(".branch.score_type", $scoretypeid);
		$branch_classification_id = $this->sql(".branch.classification", $classificationid);

		if($branch_score_type != $branch_classification_id)
		{
			debug_lib::fatal("score_type and classification branch not match");
		}

		if($classificationid == 0 || $scoretypeid == 0 )
		{
			debug_lib::fatal("خطا در اطلاعات");
		}

		$value = $this->xuId("value");

		if($value == '--')
		{
			$this->sql(".absence.insert", $classificationid, "unjustified absence", $date);
			return;
		}

		$scoretype = $this->sql()->tableScore_type()->whereId($scoretypeid)->limit(1)->select()->assoc();

		if(intval($value) < intval($scoretype['min']))
		{
			debug_lib::fatal("حد اقل امتیاز " . $scoretype['min']);
		}elseif(intval($value) > intval($scoretype['max']))
		{
			debug_lib::fatal("حد اکثر امتیاز " . $scoretype['max']);
		}
		if($scoretype['type'] == 'classroom')
		{

			$check = $this->sql()->tableScore()
						->whereClassification_id($classificationid)
						->andScore_type_id($scoretypeid)
						->andDate($date)
						->limit(1)->select();//->num();

			if($check->num() == 1)
			{
				$x = $this->sql()->tableScore()
								->whereClassification_id($classificationid)
								->andScore_type_id($scoretypeid)
								->andDate($date)
								->setValue($value)
								->update();

				$this->commit(function(){
					debug_lib::true("اطلاعات به روز رسانی شد");
				});
			}
			else
			{
				$x = $this->sql()->tableScore()
								->setClassification_id($classificationid)
								->setScore_type_id($scoretypeid)
								->setValue($value)
								->setDate($date)
								->insert();

				$this->commit(function(){
					debug_lib::true("اطلاعات ثبت شد");
				});
			}
		}
		else
		{
			// endofterm mode
			$check = $this->sql()->tableScore()
						->whereClassification_id($classificationid)
						->andScore_type_id($scoretypeid)->limit(1)->select();//->num();

			if($check->num() == 1)
			{
				$x = $this->sql()->tableScore()
								->whereClassification_id($classificationid)
								->andScore_type_id($scoretypeid)
								->setValue($value)
								->update();

				if($retest == 'true')
				{
					$insert_log = $this->sql()->tableLogs()
									->setLog_data($check->assoc("value"))
									->setLog_meta("scoreretest/classificationid=$classificationid/scoretypeid=$scoretypeid")
									->setLog_status("enable")
									->insert();
				}

				$this->commit(function(){
					debug_lib::true("اطلاعات به روز رسانی شد");
				});
			}
			else
			{
				$x = $this->sql()->tableScore()
								->setClassification_id($classificationid)
								->setScore_type_id($scoretypeid)
								->setValue($value)
								->insert();

				$this->commit(function(){
					debug_lib::true("اطلاعات ثبت شد");
				});
			}

		}

	}


	public function post_get()
	{
		$classesid = ($this->xuId("classesid"));
		$date = ($this->xuId("date"));

		$query =
		"
			SELECT
				classification.id AS 'classificationid',
				score_type.id AS 'scoretypeid',
				score.value AS 'scorevalue',
				IFNULL(absence.type,'present') AS 'absence',
				score.date AS 'date'
			FROM
				classification
			INNER JOIN score ON classification.id = score.classification_id
			INNER JOIN score_type ON score_type.id = score.score_type_id
			LEFT JOIN absence ON
					absence.classification_id = classification.id AND
					DATE(absence.date) = '$date'

			WHERE
				score.date = '$date' AND
				classification.classes_id = $classesid AND
				score_type.type = 'classroom'
		";
		$list = $this->db($query)->allAssoc();
		debug_lib::msg("list", $list);

	}
}
?>