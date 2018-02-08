<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		
		//------------------------------ globals
		$this->global->page_title = 'hefz_teams';
		
		//---------------------- check branch
		$this->sql(".branch.hefz_teams", $this->xuId());
		
		//------------------------------ list of classes
		$team_detail = $this->sql(".list", "hefz_teams", function ($query) {
			$query->joinHefz_ligs()->whereId("#hefz_teams.lig_id")->fieldName("ligname");
			// $query->joinPerson()->whereUsers_id("#hefz_teams.teacher")->fieldFamily("teacherFamily");
			$query->joinHefz_group()->whereId("#hefz_teams.hefz_group_id")->fieldName("groupname");
			$query->whereId($this->xuId());
		});
		// $team_detail->addColFirst("teacherFamily","teacher");
		$team_detail->addColFirst("name","name");
		$team_detail->addColFirst("groupname","groupname");
		$team_detail->addColFirst("ligname", "ligname");
		$team_detail->removeCol("id,lig_id,teacher,teacherFamily,hefz_group_id");
		// var_dump($team_detail);exit();
		$this->data->list = $team_detail->compile();


		$teamlist = $this->sql(".list", "hefz_teamuser", function ($query) {
			$query->whereHefz_team_id($this->xuId());
			$query->joinPerson()->whereUsers_id("#hefz_teamuser.users_id")->fieldName("personname")->fieldFamily("personfamily")->fieldFather("father");
			// $query->joinHefz_ligs()->whereId("#hefz_teams.lig_id")->fieldName("ligname");
		});
		$teamlist->removeCol("id,hefz_team_id,users_id");
		$teamlist->addColFirst("detail","detail")->select(-1, "detail")->html(
				$this->tag("a")->addClass("icomore")->href("users/status=detail/id=%users_id%")->render());
		$teamlist->addColFirst("learn","learn")->select(-1, "learn")->html(
				$this->tag("a")->addClass("icoshare")->href("users/learn/id=%users_id%")->render());

		$teamlist->addColEnd("delete","delete")->select(-1, "delete")->html(
				$this->tag("a")->addClass("icoredclose delete-hefz-teams-users")
					->teamuserid("%id%")
					->title("حذف این فراگیر از تیم")
					->style("cursor:pointer")->render());

		$this->data->teamlist  = $teamlist->compile();


		$type = "hefz_teams";
		//------------------------------ list of hefz_teams
		$this->data->dataTable = $this->dtable(
			"users/status=api/type=".$type."/teamid=" . $this->xuId() . "/",
			array(
				'casecode',
				'name',
				'family',
				'father',
				'birthday',
				// 'gender',
				'nationalcode',
				'code',
				// 'marriage',
				// 'education_id',
				'detail',
				"ثبت در این تیم")
			);
		// $this->data->list = $list->compile();
		//------------------------------ edit form
		
	}

}
?>