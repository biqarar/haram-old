<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function sql_weekly($startdate = false, $enddate = false) {

		//---------- get branch id in the list
		if(global_cls::supervisor())
		{
			$branch = '';
			$join = '';
		}
		else
		{
			$join = " INNER JOIN `users_branch` ON `users_branch`.`users_id` = person.users_id ";
			$branch = [];
			foreach ($this->branch() as $key => $value) {
				$branch[] = "users_branch.branch_id = $value";
			}
			$branch = ' AND ('. join($branch, " OR ") . ')';
		}
		$query = "
				SELECT 
				@n := @n + 1 id,
				price.date, 
				price.pay_type, 
				price.value, 
				price.card, 
				price.transactions, 
				person.name, 
				person.family
				 
				FROM price
				INNER JOIN person ON person.users_id = price.users_id 
				$join
				WHERE 
					price.date >= '$startdate' AND 
					price.date <= '$enddate' AND 
					price.pay_type LIKE 'pos%'
					$branch 
				 GROUP BY price.id
				 ORDER BY price.date
		";
		
		$db = $this->db($query);
		$price = $db->allAssoc();
		
		$i = 1;
		foreach ($price as $key => $value) {
			$price[$key]['id'] = $i++;
		}

		$numtostr = new numtostr_lib;

		$sum_value = array_sum(array_column($price, 'value'));
		
		$sum = [
				'id' => 'تعداد ' . count($price) . ' مورد ',
				'date' => 'جمع',
				'pay_type' => 'به عدد',
				'value' => $sum_value,
				'card' => 'به حروف',
				'transactions' => @$numtostr->convert((string)$sum_value) . ' ریال ' ,
				'name' => '-',
				'family' => '-'
				];
		array_push($price, $sum);

		return $price;		
	}
	public function post_report(){
	
	}	
}
?>