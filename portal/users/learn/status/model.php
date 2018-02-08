<?php
/**
*
*/
class model extends main_model
{

	public function post_api()
	{

		$usersid = $this->xuId();

			//----------------------- check banch
		$this->sql(".branch.users",$usersid);


		$dtable = $this->dtable->table("classification")
		->fields("classesid class"
		, "plan"
		, "teachername"
		,"teacherfamily"
		 ,"date_entry"
		,"date_delete"
		,"because"
		, "id classroom"
		, "id absence"
		, "mark mark"
		, "id certification")
		->search_fields("plan", "teacher")
		->query(function($q)
		{

			$q->whereUsers_id($this->xuId());

			$q->joinClasses()->whereId("#classification.classes_id")->fieldId("classesid")
			  ->fieldStatus("classesstatus");
			$q->joinPlan()->whereId("#classes.plan_id")->fieldName("plan");
			$q->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teachername")->fieldFamily("teacherfamily");
		})
		->order(function($q, $n, $b)
		{

			if($n === 'orderTeachername')
			{
				$q->join->person->orderName($b);
			}
			elseif($n === 'orderPlan')
			{
				$q->join->plan->orderName($b);
			}
			elseif($n === 'orderTeacherfamily')
			{
				$q->join->person->orderName($b);
			}
			elseif($n === 'orderDate_entry')
			{
				$q->orderDate_entry($b);
			}
			elseif($n === 'orderDate_delete')
			{
				$q->orderDate_delete($b);
			}
			elseif($n === 'orderBecause')
			{
				$q->orderBecause($b);
			}
			elseif($n === 'orderMark mark')
			{
				$q->orderMark($b);
			}
			else
			{
				$q->orderId("DESC");
			}
		})
		->result(function($r) {
			$classesid = $r->class;
			$absence_count = $this->find_count_absence($r->absence);

			if (!$absence_count || $absence_count == 0 || $absence_count == null) {
				$r->absence = $this->tag("a")->href("users/learn/absence/id=" . $this->xuId(). "/classesid=". $classesid)->class("icoattendance")->title("نمایش غیبت های فراگیر")->render();
			}
			else
			{
				$r->absence = $this->tag("a")->href("users/learn/absence/id=" . $this->xuId(). "/classesid=". $classesid)->vtext($this->find_count_absence($r->absence))->title("نمایش غیبت های فراگیر")->render();
			}

			$r->mark = $this->tag("a")->href("users/learn/score/id=". $this->xuId())->vtext($r->mark)->render();

			$r->certification = $this->find_status_certification($r->certification);
			// var_dump($r);exit();
			if($r->classesstatus == "اتمام") {
				$r->class = $this->tag("a")->href("classification/class/classesid=". $r->class)->class("icoredclass")->title("کلاس به اتمام رسیده است " . $r->class)->render();

			}
			else
			{
				$r->class = $this->tag("a")->href("classification/class/classesid=". $r->class)->class("icoclass")->title("کلاس فعال است " . $r->class)->render();
			}

			$r->classroom = $this->tag("a")
						->href("users/learn/progress/id=". $this->xuId(). "/classesid=". $classesid)
						->class("icoscore")->title("نمایش وضعیت نمرات کلاسی ")->render();

		});
		$this->sql(".dataTable", $dtable);
	}

	public function find_status_certification($classificationid = false ) {

		$certification = $this->sql()->tableCertification()->whereClassification_id($classificationid)->limit(1)->select();
		if($certification->num() == 1) {
			return $this->tag("a")
			->href("users/learn/certification/usersid=" . $this->xuId())
			->class("icocertification")->render();

		}
			return $this->tag("a")->class("icocertificationdisable")->disable("disable")->render();
	}


	public function find_count_absence($classificationid = false) {
		$absence = $this->sql()->tableAbsence()->whereClassification_id($classificationid)->select()->num();
		if($absence > 0) {
			return $absence;
		}
		return null;
	}


	public function sql_classification_list($usersid = false) {
		$return = array();
		$return['sum_active'] = 0;
		$return['sum_all'] = 0;
		$return['classes'] = array();
		$sql = $this->sql()->tableClassification()->whereUsers_id($usersid);
		$sql->joinClasses()->whereId("#classification.classes_id");
		$sql->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
		$sql->joinPlace()->whereId("#classes.place_id")->fieldName("placename");
		$sql->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teachername")->fieldFamily("teacherfamily");
		$x = $sql->select()->allAssoc();

		foreach ($x as $key => $value) {

			$return['sum_all']++;

				$return['sum_active']++;

				$return['classes'][$key]['string'] = $x[$key]['planname'] 		. '  ' .
								   _($x[$key]["age_range"]) 		. '  ' .
								   $x[$key]['placename'] 		. ' ساعت ' .
								   $x[$key]['end_time']			. ' استاد ' .
								   $x[$key]["teachername"] 		. '  ' .
								   $x[$key]['teacherfamily'];
				$return['classes'][$key]['id'] = $x[$key]["classes_id"];

		}
		return $return;
	}
}
?>