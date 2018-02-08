<?php
class model extends main_model {
	public function post_api() {
		$dtable = $this->dtable->table("score_calculation")
			->fields("id", "name","calculation", "status", "id edit")
			->search_fields("name")
			->order(function($q, $n, $b){
				if($n === 'orderName'){
					$q->join->plan->orderName($b);
				}else{
					return true;	
				}
			})
			->query(function($q){
				$q->joinPlan()->whereId("#score_calculation.plan_id")->fieldName();
				//---------- get branch id in the list
				$q->groupOpen();
				foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("where", "plan.branch_id","=",$value);
					}else{
						$q->condition("or","plan.branch_id","=",$value);
					}
				}
				$q->groupClose();
			})
			->search_result(function($result){
				
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->groupOpen();
				$result->condition("and", "score_calculation.calculation", "LIKE", "'%$vsearch%'");
				$result->condition("or", "plan.name", "LIKE", "'%$vsearch%'");
				$result->groupClose();
				// echo $resultس->select()->string();exit();

			})
			->result(function($r){
				$r->edit = '<a href="score/calculation/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>