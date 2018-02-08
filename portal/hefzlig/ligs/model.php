<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_api(){
		$query = $this->sql()->tableHefz_teams()->whereLig_id($this->xuId());//->select();
		$query->joinHefz_group()->whereId("#hefz_teams.hefz_group_id")->fieldName("groupname")->fieldId("groupid");
		$query = $query->select();
		if($query->num() > 0){
			debug_lib::msg("teams", $query->allAssoc());
		}
	}

	public function post_listapi(){
		$fields = array('id', 'start_date', 'end_date','name',  "id edit");
		if($this->xuId("type") == "result"){
			$fields = array('id', 'start_date', 'end_date','name',"id mrhefz" , "id edit");
		}
		$dtable = $this->dtable->table("hefz_ligs")
		->fields($fields)
		->search_fields("name")
		->result(function($r) {
			if($this->xuId("type") == "result"){
				$r->mrhefz = $this->tag("a")->href("hefzlig/status=mrhefz/id=".$r->mrhefz)->class("icouser")->title(_("آقای حفظ"))->render();
				$r->edit =$this->tag("a")->href("hefzlig/status=showresult/id=".$r->edit)->class("icocertification")->title(_("جدول نتایج"))->render();
			}else{
				$r->edit =$this->tag("a")->href("hefzlig/ligs/status=edit/id=".$r->edit)->class("icoedit")->title(_("edit"))->render();
				
			}
		});
		$this->sql(".dataTable", $dtable);
	}

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()
			->tableHefz_ligs()
			->setName(post::name())
			->setStart_date(post::start_date())
			->setEnd_date(post::end_date())
			->setBranch_id($this->post_branch());
	}

	public function post_add_hefz_ligs() {
		//------------------------------ insert ligs
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert ligs successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert ligs failed]]");
		});
	}

	public function post_edit_hefz_ligs() {
		//------------------------------ update ligs
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update ligs successful]]");
		});

		//------------------------------ update code
		$this->rollback(function() {
			debug_lib::fatal("[[update ligs failed]]");
		});
	}

}
?>