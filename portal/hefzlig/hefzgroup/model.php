<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_api(){
		$group = $this->sql()->tableHefz_group()->whereLig_id($this->xuId("ligid"))->select()->allAssoc();
		debug_lib::msg("groupname", $group);
		// var_dump($group);exit();
	}

	public function post_listapi(){
		$dtable = $this->dtable->table("hefz_group")
		->fields('id', 'ligid', 'name','description',  "id edit")
		->search_fields("name")
		->query(function($q){
			$q->joinHefz_ligs()->whereId("#hefz_group.lig_id")->fieldName("ligid");
		})
		->search_result(function($result){
				$vsearch = $_POST['search']['value'];
			$vsearch = str_replace(" ", "_", $vsearch);
			$result->groupOpen();
			$result->condition("and", "hefz_ligs.name", "LIKE", "'%$vsearch%'");
			$result->condition("or", "hefz_group.name", "LIKE", "'%$vsearch%'");
			$result->groupClose();
			
			})
		->result(function($r) {
			$r->edit =$this->tag("a")->href("hefzlig/hefzgroup/status=edit/id=".$r->edit)->class("icoedit")->title(_("edit"))->render();
				
		
		});
		$this->sql(".dataTable", $dtable);
	}

	public function makeQuery() {
		//------------------------------ make sql object
		return $this->sql()
			->tableHefz_group()
			->setLig_id(post::lig_id())
			->setName(post::name())
			->setDescription(post::description());
	}

	public function post_add_hefz_group() {
		//------------------------------ insert group
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert group successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert group failed]]");
		});
	}

	public function post_edit_hefz_group() {
		//------------------------------ update group
		$sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update group successful]]");
		});

		//------------------------------ update code
		$this->rollback(function() {
			debug_lib::fatal("[[update group failed]]");
		});
	}

}
?>