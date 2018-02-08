<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	
	
		public function post_apimanage() {
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
			"count",
			"status",
			"status icostatus",
			"id detail")
		
		->search_fields(
			"id classes.id",
			"planname plan.name" ,
			"teachername person.name",
			"teacherfamily person.family",
			"placename place.name",
			"name classes.name")
		->order(function($q, $n, $b){
			if($n === 'orderPlanname'){
				$q->join->plan->orderName($b);
			}elseif($n === 'orderTeacherfamily'){
				$q->join->person->orderFamily($b);
			}elseif($n === 'orderTeachername'){
				$q->join->person->orderName($b);
			}elseif($n === 'orderPlacename'){
				$q->join->place->orderName($b);
			}elseif($n === 'orderCount'){
				$q->orderCount($b);
			}elseif($n === 'orderStart_time'){
				$q->orderStart_time($b);
			}elseif($n === 'orderEnd_time'){
				$q->orderEnd_time($b);
			}elseif($n === 'orderName'){
				$q->orderName($b);
			}elseif($n === 'orderStatus'){
				$q->orderStatus($b);
			}else{
				$q->orderId("DESC");	
			}
		})
		->query(function($q){
			$q->joinPlan()->whereId("#classes.plan_id")->fieldName('planname')->fieldMax_person("maxp");
			//---------- get branch id in the list
			$q->groupOpen();
			foreach ($this->branch() as $key => $value) {
				if($key == 0){
					$q->condition("and", "plan.branch_id","=",$value);
				}else{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}
			$q->groupClose();
			$q->joinPerson()->whereUsers_id("#classes.teacher")->fieldFamily("teacherfamily")->fieldName("teachername");
			$q->joinPlace()->whereId("#classes.place_id")->fieldName("placename");
			// echo $q->select()->string() ;exit();
		})
		->result(function($r){
			if($r->icostatus == _("running") || $r->icostatus == _("ready")) {
				$r->icostatus = $this->tag("a")->class("icoredclass")->title("ثبت اتمام کلاس")->href("classes/status=done/classesid=". $r->detail)->render();
			}else{
				$r->icostatus = $this->tag("a")->class("icoclass")->title("فعال سازی مجدد کلاس")->href("classes/status=running/classesid=". $r->detail)->render();
			}
			$r->planname = $this->sql(".courseclassesInformation",$r->planname, $r->detail);
			$r->detail = '<a class="icomore" href="classes/status=detail/id='.$r->detail.'" title="'.gettext('detail').' '.$r->detail.'"></a>';


		});

		$this->sql(".dataTable", $dtable);
	}
}
?>