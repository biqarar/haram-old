<?php
class model extends main_model {
	public function post_api() {

		$dtable = $this->dtable->table("classification")

			->fields(
			"username users.username",
			"name person.name",
			"family person.family",
			"date_entry",
			"date_delete",
			"because",
			"users_id cash",
			"users_id more")

			->search_fields("username", "name", "family")
			->query(function($q){

				//------------------ check branch
				$this->sql(".branch.classes",$this->xuId("classesid"));

				$q->andClasses_id($this->xuId("classesid"));
				$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("name")->fieldFamily("family");
				$q->joinUsers()->whereId("#classification.users_id")->fieldUsername("username");

			})
			->search_result(function($result){
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})
			->result(function($r){
				$r->more = $this->tag("a")->addClass("icomore")->href("price/status=detail/usersid=". $r->more)->render();
				$cash = $this->sql(".price.sum_price", $r->cash);
				$cash = $cash['sum_active'];
				if($cash < 0) {
					$r->cash = $this->tag("a")->vtext($cash)->style("color :red;")->render();
				}else{
					$r->cash = $this->tag("a")->vtext($cash)->style("color :green;")->render();
				}
			});
			$this->sql(".dataTable", $dtable);
	}

	public function post_add_price()
	{
		if(global_cls::superprice("rule"))
		{
			$classes_id  = $this->xuId("classesid");
			$date        = post::date();
			$title       = post::title();
			$value       = post::value();
			$description = post::description();
			$pay_type    = 'rule';

			if(!preg_match("/\d{4}\-\d{2}\-\d{2}/", $date))
			{
				debug_lib::true("تاریخ اشتباه است");
				return false;
			}

			if(!is_numeric($value))
			{
				debug_lib::true("مبلغ اشتباه وارد شده است");
				return false;
			}

			if(!is_numeric($title))
			{
				debug_lib::true("خطا در نوع شهریه");
				return false;
			}

			$query =
			"
				INSERT INTO price
				(
					`users_id`,
					`date`,
					`value`,
					`pay_type`,
					`title`,
					`card`,
					`transactions`,
					`description`
				)
				SELECT
					classification.users_id,
					'$date',
					'$value',
					'$pay_type',
					'$title',
					'000',
					'000',
					'$description'
				FROM classification
				INNER JOIN classes ON classes.id = classification.classes_id
				WHERE
					classification.classes_id = '$classes_id' AND
					classification.date_delete IS NULL

			";
			$result = $this->db($query)->result();

			$this->commit(function() {
				debug_lib::true("شهریه مورد نظر برای تمامی افراد فعال این کلاس ثبت شد");
			});

			$this->rollback(function() {
				debug_lib::fatal("[[insert price failed]]");
			});


		}
	}
}
?>