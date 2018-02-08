<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	/*public function post_listapi(){
		$dtable = $this->dtable->table("hefz_teams")
		->fields('id', 'ligname', 'min_person','max_person','name','hefz','teachefamily', "id edit")
		->search_fields("name" , "ligname hefz_ligs.name" , "teacher person.teachefamily")
		->query(function($q){
			$q->joinPerson()->whereUsers_id("#hefz_teams.teacher")->fieldName("teachername")->fieldFamily("teachefamily");
			// $q->joinHefz
			$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id")->fieldId("lig_id")->fieldName("ligname");
			foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}
			$q->groupClose();
		})
		->result(function($r) {
			// $r->teacher = 
			$r->edit = '<a class="icoedit" href="hefzlig/teams/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
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
		return $this->sql()
			->tableHefz_teams()
			->setName(post::name())
			->setLig_id(post::lig_id())
			->setMin_person(post::min_person())
			->setMax_person(post::max_person())
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
		});*/
	}

}
?>