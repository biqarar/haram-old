<?php
class model extends main_model
{

	public function post_api()
	{

		$array = array("usersid more",
					 "username users.username",
					 "name person.name",
					 "family person.family",
					 "id retest",
					 "id input",
					 "id xmore");
		$dtable = $this->dtable->table("classification")
			->fields($array)
			->search_fields("username users.username","name person.name", "family person.family")
			->order(function($q, $n, $b)
			{
				if($n === 'orderUsername users.username')
				{
					$q->join->users->orderUsername($b);
				}
				elseif($n === 'orderName person.name')
				{
					$q->join->person->orderName($b);
				}
				elseif($n === 'orderFamily person.family')
				{
					$q->join->person->orderFamily($b);
				}
				else
				{
					return true;
				}
			})
			->query(function($q)
			{
				//------------------- check branch
				$y = $this->sql(".branch.classes", $this->xuId("classesid"));

				$q->andClasses_id($this->xuId("classesid"));
				$q = $this->classification_finde_active_list($q);
				$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("name")->fieldFamily("family");
				$q->joinUsers()->whereId("#classification.users_id")->fieldUsername("username")->fieldId("usersid");
				// echo $q->select()->string();exit();

			})
			->search_result(function($result)
			{
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})
			->result(function($r)
			{

				$xMore = $this->tag("a")->class("icomore")
				->tabindex(-1)->href("users/learn/score/id=".$r->more."/classificationid=". $r->retest)
				->render();

				// var_dump($r->show);exit();
				$xInput ="<div class='form-element' >" .  $this->tag("input")
									  ->type("text")
									  ->classificationid($r->input)
									  ->scoretypeid($this->xuId("scoretypeid"))
									  ->addClass('score-mark')
									  ->value($this->get_value($r->input, $this->xuId("scoretypeid")))
									  ->render() . "</div>";
				$xCheckbox ="<label class='label-custom'>آزمون مجدد<input type='checkbox' classificationid='".$r->input."' class='score-retest' name='retest' placeholder='آزمون مجدد'><span class='brk-form-custom'><span></span><span></span></span></label>";
				// $r->input = $xInput . $xCheckbox;
				$r->input =  $xInput;
				$r->retest =  $xCheckbox;
				$r->xmore = $xMore;

				$r->more = $this->tag("a")->class("icomore")
				->tabindex(-1)->href("users/status=detail/id=". $r->more)->render();
			});
			$this->sql(".dataTable", $dtable);
	}

	public function get_value($classificationid = false, $scoretypeid = false)
	{
		$check = $this->sql()->tableScore()
					->whereClassification_id($classificationid)
					->andScore_type_id($scoretypeid)->limit(1)->select();
					// var_dump($check->assoc("value")); exit();
			return $check->assoc("value");
		if($check->num() == 1)
		{
		}
		else
		{
			return;
		}
	}
}
?>