<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	public function post_api() {
		$dtable = $this->dtable->table("file_tag")
		->fields("id", "tag", "table_name", "type", "max_size")
		->search_fields("tag")
		->result(function($r){
			// $r->edit = '<a href="country/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
		});
		$this->sql(".dataTable", $dtable);
	}

	public function makeQuery() {
		//------------------------------ make sql object
		$condition = '';
		if(post::type() == "image"){
			$condition .= "width:".post::width();
			$ratio = preg_split("[/]", post::ratio(), 2);
			if(count($ratio) > 1){
				$ratio = $ratio[0] / $ratio[1];
			}else{
				$ratio = $ratio[0];
			}
			$condition .= ";ratio:".$ratio;
		}
		return $this->sql()->tableFile_tag()
		->setTag(post::tag())
		->setTable_name(post::table_name())
		->setType(post::type())
		->setMax_size(post::max_size())
		->setCondition($condition);
	}

	public function post_add_file_tag(){
		//------------------------------ insert country
		$sql = $this->makeQuery()->insert();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert tag successful]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert tag failed]]");
		});
	}

	public function post_edit_file_tag(){
		//------------------------------ update country
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update tag ture]]");
		});

		//------------------------------ rolback code
		$this->rollback(function() {
			debug_lib::fatal("[[update tag failed]]");
		});
	}
}
?>