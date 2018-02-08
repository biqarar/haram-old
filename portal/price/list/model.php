<?php
class model extends main_model {
	public function post_api()
	{

		$dtable = $this->dtable->table("price")
		->fields(
			'id',
			'name',
			'family',
			'username',
			'date',
			'pay_type',
			'value' ,
			'pricechangename' ,
			'transactions' ,
			'card' ,
			'description',
			'id edit')
		->search_fields("username", "date", "transactions", 'description')
		->query(function($q)
		{
			$q->joinPrice_change()->whereId("#price.title")->fieldName("pricechangename");
			$q->joinUsers()->whereId("#price.users_id")->fieldUsername("username");
			$q->joinPerson()->whereUsers_id("#users.id")->fieldName()->fieldFamily();
			$q->joinUsers_branch()->whereUsers_id("users.id");
			$q->groupOpen();
			foreach ($this->branch() as $key => $value)
			{
				if($key == 0)
				{
					$q->condition("and", "users_branch.branch_id","=",$value);
				}
				else
				{
					$q->condition("or","users_branch.branch_id","=",$value);
				}
			}
			$q->groupClose();

		})
		->order(function($q, $n, $b)
		{

			if($n === 'orderPricechangename')
			{
				$q->join->price_change->orderName($b);
			}
			elseif($n === 'orderUsername')
			{
				$q->join->users->orderUsername($b);
			}
			elseif($n === 'orderDate')
			{
				$q->orderDate($b);
			}
			elseif($n === 'orderPay_type')
			{
				$q->orderPay_type($b);
			}
			elseif($n === 'orderValue')
			{
				$q->orderValue($b);
			}
			elseif($n === 'orderTransactions')
			{
				$q->orderTransactions($b);
			}
			elseif($n === 'orderCard')
			{
				$q->orderCard($b);
			}
			elseif($n === 'orderFamily')
			{
				$q->join->person->orderFamily($b);
			}
			elseif($n === 'orderName')
			{
				$q->join->person->orderName($b);
			}
			elseif($n === 'orderDescription')
			{
				$q->orderDescription($b);
			}
			else
			{
				$q->orderId("DESC");
			}
		})
		->search_result(function($result)
		{

				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->groupOpen();
				$result->condition("and", "users.username", "LIKE", "'%$vsearch%'");
				$result->condition("or", "price.transactions", "LIKE", "'%$vsearch%'");
				$result->condition("or", "price.date", "LIKE", "'%$vsearch%'");
				$result->condition("or", "price.description", "LIKE", "'%$vsearch%'");
				$result->groupClose();
		})
		->result(function($r)
		{
			$r->edit = '<a class="icoedit" href="price/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
		});
		$this->sql(".dataTable", $dtable);
	}

}
?>
