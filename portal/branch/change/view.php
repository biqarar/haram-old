<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'تغییر شعبه کابر';


		$usersid = $this->xuId("usersid");

		$this->sql(".branch.users", $usersid);

		$this->topLinks(array(
				array("title" => "آموزش", "url" =>"users/learn/id=$usersid"),
				array("title" => "مشخصات", "url" =>"users/status=detail/id=$usersid"),
				array("title" => "شعبه", "url" =>"branch/status=change/usersid=$usersid")

			));

		$this->data->name_family = $this->sql(".userNameFamily", $usersid);
		$this->data->usersid = $usersid;
		//------------------------------ make chane password form

		$submit = $this->form("#submitadd")->value("add");


		$branch_list = $this->sql(".branch.allBranch");

		$hidden = $this->form("#hidden")->value("branch_change");

		$branch = $this->form("select")->name("branch_id")->classname("select-branch")->label("شعبه جدید");
		foreach ($branch_list as $key => $value) {
			$branch->child()->name($value['name'])->label($value['name'])->value($value["id"]);
		}
		$type = $this->form("select")->name("type")->classname("select-type")->label("نوع کاربر");
		
		$type->child()->name(_("operator"))->label(_("operator"))->value("operator");
		$type->child()->name(_("teacher"))->label(_("teacher"))->value("teacher");
		$type->child()->name(_("student"))->label(_("student"))->value("student")->selected("selected");
		

		$list = $this->sql(".list", "users_branch", function($q){
			$q->whereUsers_id($this->xuId("usersid"));
			$q->joinBranch()->whereId("#users_branch.branch_id")->fieldName();
		});
		$list->addCol("teacher","teacher");
		$list->addCol("operator","operator");
		$list->addCol("student","student");
		$list->addCol("waiting","waiting");
		$list->addCol("enable","enable");
		$list->addCol("block","block");
		$list->addCol("delete","delete");
		$list = $list->compile();
		// var_dump($list);exit();
		foreach ($list['list'] as $key => $value) {
			$id = $list['list'][$key]["id"];
			if($list['list'][$key]['status'] != ""){

				$list['list'][$key]['status'] = $this->tag("a")->vtext(_($list['list'][$key]['status']))->usersbranchid($id)->class("users-branch-status")->render();

			}
			if($list['list'][$key]['type'] != "") {

				$list['list'][$key]['type'] = $this->tag("a")->vtext(_($list['list'][$key]['type']))->usersbranchid($id)->class("users-branch-type")->render();
				

			}

			$list['list'][$key]["waiting"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icomore")->type("waiting")->usersbranchid($id)->render();
			$list['list'][$key]["enable"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icouser")->type("enable")->usersbranchid($id)->render();
			$list['list'][$key]["block"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icoredclose")->type("block")->usersbranchid($id)->render();
			$list['list'][$key]["delete"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icoredclose")->type("delete")->usersbranchid($id)->render();

			$list['list'][$key]["student"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icoduser")->type("student")->usersbranchid($id)->render();$list['list'][$key]["operator"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icosettings")->type("operator")->usersbranchid($id)->render();$list['list'][$key]["teacher"] = $this->tag("a")
			->style("cursor: pointer")->class("branch-change icoteacher")->type("teacher")->usersbranchid($id)->render();

		}
		
		$this->data->list= $list;
		

		$branch_cash[] = $hidden->compile();
		$branch_cash[] = $branch->compile();
		// $branch_cash[] = $type->compile();
		$branch_cash[] = $submit->compile();
		
		$this->data->branch_cash = $branch_cash;
	}
}
?>