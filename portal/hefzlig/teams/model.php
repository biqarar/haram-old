<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_apidelete(){
		$id = $this->xuId("teamuserid");

		$this->sql(".branch.hefz_teamuser", $id);
		
		$query = $this->sql()->tableHefz_teamuser()->whereId($id)->delete();
		$this->commit(function(){
				debug_lib::true("فرد از این تیم حذف شد");
			});
			$this->rollback(function(){
				debug_lib::fatal("خطا در حذف فرد از تیم");
			});
	}

	public function post_apiadd() {
		$usersid = $this->xuId("usersid");
		$teamid = $this->xuId("teamid");

		$x = $this->sql(".branch.users", $usersid);
		$y = $this->sql(".branch.hefz_teams", $teamid);
		if($x != $y) {
			debug_lib::fatal("خطا در تطابق شناسه شعبه کاربر و شعبه لیگ");
		}
		$check = $this->sql()
			->tableHefz_teamuser()
			->whereUsers_id($usersid)
			->andHefz_team_id($teamid)
			->select()
			->num();

		if($check > 0) {
			debug_lib::fatal("این فراگیر در این تیم ثبت شده است");
		}else{

			$query = $this->sql()->tableHefz_teamuser()->setHefz_team_id($teamid)->setUsers_id($usersid)->insert();
			$this->commit(function(){
				debug_lib::true("فرد در تیم ثبت شد");
			});
			$this->rollback(function(){
				debug_lib::fatal("خطا در ثبت فرد در تیم");
			});

		}
		
		// var_dump($this->xuId("usersid"), $this->xuId("teamid"));exit();
	}

	public function post_listapi(){

		$dtable = $this->dtable->table("hefz_teams")
		->fields('id', 'ligname','hefzgroup', 'name','hefz','teachefamily', "id edit", "id manage")
		->search_fields("name" , "ligname hefz_ligs.name" , "teacher person.teachefamily", 'hefzgroup hefz_group.name')
		->query(function($q){
			$q->joinPerson()->whereUsers_id("#hefz_teams.teacher")->fieldName("teachername")->fieldFamily("teachefamily");

			// $q->joinHefz
			$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id")->fieldId("lig_id")->fieldName("ligname");
			$q->joinHefz_group()->whereId("#hefz_teams.hefz_group_id")->fieldName("hefzgroup");
			
			$q->groupOpen();
			foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}
			$q->groupClose();

		})
		->search_result(function($result){
			$vsearch = $_POST['search']['value'];
			$vsearch = str_replace(" ", "_", $vsearch);
			$result->groupOpen();
			$result->condition("and", "hefz_teams.name", "LIKE", "'%$vsearch%'");
			$result->condition("or", "hefz_ligs.name", "LIKE", "'%$vsearch%'");
			$result->condition("or", "hefz_group.name", "LIKE", "'%$vsearch%'");
			$result->groupClose();

			// echo $result->select()->string();exit();
		})
		->result(function($r) {
			
			$r->edit = '<a class="icoedit" href="hefzlig/teams/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
			if($this->xuId("type") == "race"){
				$r->manage = $this->tag("a")
						->style("cursor:pointer")
						->addClass("icodadd")
						->title("افزودن به مسابقه")
						->addClass("add-race")
						->teamid($r->manage)->render()
						 .'<br>'. 
						 $this->tag("a")
						 ->href("hefzlig/teams/status=show/id=" .$r->manage)
						 ->addClass("icoshare")
						 ->title("نمایش اعضاء")
						 ->render();
			}else{

				$r->manage = $this->tag("a")->href("hefzlig/teams/status=show/id=" .$r->manage)->addClass("icosettings")->render();
			}
		});
		$this->sql(".dataTable", $dtable);
	}

	public function makeQuery() {
		//------------------------------ make sql object

			$x = $this->sql(".branch.hefz_ligs", post::lig_id());
		$y = $this->sql(".branch.users", post::teacher());
		if($x != $y) {
			debug_lib::fatal("خطا در تطابق شناسه شعبه");
		}

		$check = $this->sql()->tableHefz_group()->whereId(post::hefz_group_id())->limit(1)->select()->assoc();

		if($check['lig_id'] != post::lig_id()){
			debug_lib::fatal("اشکال در تطابق شناسه گروه و شناسه مسابقه");
		}

		return $this->sql()
			->tableHefz_teams()
			->setName(post::name())
			->setLig_id(post::lig_id())
			->setHefz_group_id(post::hefz_group_id())
			->setHefz(post::hefz())
			->setTeacher(post::teacher());
	}

	public function post_add_hefz_teams() {


		//------------------------------ insert teams
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert teams successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert teams failed]]");
		});
	}

	public function post_edit_hefz_teams() {
		//------------------------------ update teams
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update teams successful]]");
		});

		//------------------------------ update code
		$this->rollback(function() {
			debug_lib::fatal("[[update teams failed]]");
		});
	}

}
?>