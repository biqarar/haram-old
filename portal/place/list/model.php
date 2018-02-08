<?php

class model extends main_model {
	public function post_api() {
		$dt = $this->dtable->table('place')->fields("name","multiclass" , "status" ,"id edit")
		->search_fields("name")
		->query(function ($q){
			//---------- get branch id in the list
			$q->groupOpen();
			foreach ($this->branch() as $key => $value) {
				if($key == 0){
					$q->condition("where", "place.branch_id","=",$value);
				}else{
					$q->condition("or","place.branch_id","=",$value);
				}
			}
			$q->groupClose();
		})
		->search_result(function($result){
				
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->groupOpen();
				$result->condition("and", "place.name", "LIKE", "'%$vsearch%'");
				// $result->condition("or", "bridge.value", "LIKE", "'%$vsearch%'");
				$result->groupClose();
				// echo $resultس->select()->string();exit();

		})
		->result(function($r){
			$r->edit = '<a class= "icoedit" href="place/status=edit/id='. $r->edit . '"></a>';
			});
		$this->sql(".dataTable", $dt);
	}
}

?>