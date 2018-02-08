<?php
class model extends main_model {
	public function post_api() {
		$dtable = $this->dtable->table("price_change")
			->fields("id", "name" , "type", "id edit")
			->search_fields("name")
			->query(function($q){
				// $q->groupOpen();
				// foreach ($this->branch() as $key => $value) {
				// 	if($key == 0){
				// 		$q->condition("where", "branch_id","=",$value);
				// 	}else{
				// 		$q->condition("or","branch_id","=",$value);
				// 	}	
				// }
				// $q->groupClose();

			})
			->result(function($r){
				$r->edit = '<a href="price/change/status=edit/id=' . $r->edit . '" class="icoedit" ></a>';
			});
			$this->sql(".dataTable", $dtable);
	}
}
?>