<?php

/**
* @auther reza mohiti
*/
class model extends main_model {
	public $assoc = array();
	public $allclasses = false;

		public function post_api()
		{
		$type = $this->xuId("type");

		switch ($type)
		{

			case 'classification':
			case '':
				$url = "classification/class/";
				$ico = "icoclass";
				break;

			case 'absence':
				$url = "absence/classes/";
				$ico = "icoattendance";
				break;

			case 'move':
				$this->allclasses = true;
				$url = "classes/status=move/";
				$ico = "icosettings";
				break;


			case 'score':
				$this->allclasses = true;
				$url = "score/classes/";
				$ico = "icoscore";
				break;

			case 'price':
				$url = "price/classes/";
				$ico = "icoprice";
				break;

			case 'courseclasses':
				$url = "courseclasses/apiadd/";
				$ico = "icodadd";
				break;

			case 'presence':
				$url = "presence/";
				$ico = "icodattendance";
				break;

			case 'allclasses':
				$this->allclasses = true;
				$url = "classification/class/";
				$ico = "icoclass";
				break;

			default:
				$url = "classification/class/";
				$ico = "icoclass";
				break;
		}

		$dtable = $this->dtable->table("classes")
		->fields(
			"id",
			"planname planname",
			"teachername",
			"teacherfamily",
			"placename",
			"age_range",
			"start_time",
			"end_time",
			"name",
			"count count",
			"id classification",
			"id detail",
			"maxp maxp",
			"plan_id plan_id")

		->search_fields(
			"id classes.id",
			"planname plan.name" ,
			"teachername person.name",
			"teacherfamily person.family",
			"placename place.name",
			"name classes.name",
			"maxp plan.max_person")
		->order(function($q, $n, $b)
		{
			// var_dump($n,$b);exit();
			if($n === 'orderPlanname')
			{
				$q->join->plan->orderName($b);
			}
			elseif($n === 'orderTeacherfamily')
			{
				$q->join->person->orderFamily($b);
			}
			elseif($n === 'orderTeachername')
			{
				$q->join->person->orderName($b);
			}
			elseif($n === 'orderPlacename')
			{
				$q->join->place->orderName($b);
			}
			elseif($n === 'orderCount')
			{
				$q->orderCount($b);
			}
			elseif($n === 'orderStart_time')
			{
				$q->orderStart_time($b);
			}
			elseif($n === 'orderEnd_time')
			{
				$q->orderEnd_time($b);
			}
			elseif($n === 'orderName')
			{
				$q->orderName($b);
			}
			else
			{
				$q->orderId("DESC");
			}
		})
		->query(function($q)
		{

			if(!$this->allclasses)
			{
				$q->whereStatus("<>", "done");
			}

			$q->joinPlan()->whereId("#classes.plan_id")->fieldName('planname')->fieldMax_person("maxp");

			//---------- get branch id in the list
			$q->groupOpen();
			foreach ($this->branch() as $key => $value)
			{
				if($key == 0)
				{
					$q->condition("and", "plan.branch_id","=",$value);
				}
				else
				{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}
			$q->groupClose();
			// $q->joinCourseclasses()->whereClasses_id("#classes.id");
			// $q->joinCourse()->whereId("#courseclasses.course_id")->fieldName("coursename");
			$q->joinPerson()->whereUsers_id("#classes.teacher")->fieldFamily("teacherfamily")->fieldName("teachername");
			$q->joinPlace()->whereId("#classes.place_id")->fieldName("placename");


		})
		->search_result(function($result)
		{
				$search = array(
					"id classes.id",
					"planname plan.name" ,
					"teachername person.name",
					"teacherfamily person.family",
					"placename place.name",
					"name classes.name",
					"maxp plan.max_person");

				foreach ($search as $key => $value)
				{
					if(preg_match("/^[^\s]*\s(.*)$/", $value, $nvalue))
					{
						$search[$key] = $nvalue[1];
					}
				}
				$vsearch = $_POST['search']['value'];
				$ssearch = preg_split("[ ]", $vsearch);
				$vsearch = str_replace(" ", "_", $vsearch);
				$csearch = $search;
				foreach ($search as $key => $value)
				{
					$search[$key] = "IFNULL($value, '')";
				}
				$search  = join($search, ', ');
				$result->groupOpen();
				$result->condition("and", "##concat($search)", "LIKE", "%$vsearch%");
				foreach ($csearch as $key => $value)
				{
					if(isset($ssearch[$key]))
					{
						$sssearch = $ssearch[$key];
						if($key === 0)
						{
							$result->condition("OR", "##$value", "LIKE", "%$sssearch%");
						}
						else
						{
							$result->condition("AND", "##$value", "LIKE", "%$sssearch%");
						}
					}
				}
				$result->groupClose();
				// $vsearch = $_POST['search']['value'];
				// // var_dump($_POST['search']['value']);exit();
				// $vsearch = str_replace(" ", "", $vsearch);
				// $result->condition("and", "##concat(IFNULL(person.name, ''), IFNULL(person.family, ''), IFNULL(person.father, ''))", "LIKE", "'%$vsearch%'");
				// // $result->groupOpen();
				// $result->condition("or", "users.username", "LIKE", "'%$vsearch%'");
				// $result->condition("or", "person.nationalcode", "LIKE", "'%$vsearch%'");
				// // $result->groupClose();
				// print_r($result->select()->string());exit();
				// // $result->condition("or" "#person.s", "LIKE" "%$vsearch%");
			})
		->result(function($r, $ico, $url)
		{
			$r->planname =  $this->tag("a")
						->href("report/plan/progress/id=". $r->plan_id)
						->style("cursor:pointer")
						->vtext($r->planname)
						->render();

			if($r->count == "") $r->count = 0;

			if(intval($r->count) >= intval($r->maxp))
			{
				$r->count = $this->tag("a")->style("color:red")->vtext($r->count . " از " . $r->maxp)->render();
			}
			else
			{
				$r->count = $this->tag("a")->vtext($r->count . " از " . $r->maxp)->render();
			}

			$r->planname = $this->sql(".courseclassesInformation",$r->planname, $r->detail);
			if($ico == "icodadd")
			{

				$r->classification = $this->tag("a")
						->class("icodadd courseclasses-apiadd")
						->id($r->classification)
						->style("cursor:pointer")
						->render();

			}
			else
			{
				$r->classification = '<a class="'. $ico . '" href="'.$url.'classesid='.$r->classification.'" title="'.gettext('classification').' '.$r->classification.'"></a>';
			}
			if($ico == "icoattendance")
			{
				$r->detail = '<a class="icoletters a-undefault" href="classification/printlist/classesid=' . $r->detail. '/type=absence" title="ثبت غیبت با لیست کلاسی"></a>';

			}
			else
			{
				$r->detail = '<a class="icomore" href="classes/status=detail/id='.$r->detail.'" title="'.gettext('detail').' '.$r->detail.'"></a>';

			}


		}, $ico , $url);

		$this->sql(".dataTable", $dtable);
	}


}
?>