<?php
class model extends main_model
{
	public function post_api()
	{
		$dtable = $this->dtable->table("score_type")
			->fields("id","name","title", "min","max","type", "status", "id edit")
			->search_fields("name plan.name")
			->order(function($q, $n, $b)
			{
			if($n === 'orderName')
			{
				$q->join->plan->orderName($b);
			}
			else
			{
				$q->orderId("DESC");
			}
		})
			->query(function($q)
			{
				$q->joinPlan()->whereId("#score_type.plan_id")->fieldName();
				//---------- get branch id in the list
				$q->groupOpen();
				foreach ($this->branch() as $key => $value)
				{
					if($key == 0)
					{
						$q->condition("where", "plan.branch_id","=",$value);
					}
					else
					{
						$q->condition("or","plan.branch_id","=",$value);
					}
				}
				$q->groupClose();
			})
			->search_result(function($result)
			{

				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->groupOpen();
				$result->condition("and", "score_type.title", "LIKE", "'%$vsearch%'");
				$result->condition("or", "plan.name", "LIKE", "'%$vsearch%'");
				$result->groupClose();
				// echo $result->select()->string();exit();

			})
			->result(function($r)
			{
				$r->edit = '<a href="score/type/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>