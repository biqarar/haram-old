<?php
class model extends main_model {
	public function post_classeslist() {
		$absence_type = $this->absence_type();

		$dtable = $this->dtable->table("classification")

			->fields("name person.name",
					 "family person.family",
					 "date_entry",
					 "date_delete",
					 "because",
					 "id type",
					 "id insert",
					 "usersid attendance",
					 "usersid more")

			->search_fields("name person.name", "family person.family")

			->query(function($q){

				//--------------- CHECK BRANCH
				$this->sql(".branch.classes", $this->xuId("classesid"));


				$q->andClasses_id($this->xuId("classesid"));

				// $q = $this->classification_finde_active_list($q);

				$q->joinPerson()->whereUsers_id("#classification.users_id")
								->fieldName("name")->fieldFamily("family")->fieldUsers_id("usersid");

			})

			->search_result(function($result){
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})

			->result(function($r, $absence_type){

				$x = $this->tag("select")->name("type")->class("absence-type");

				foreach ($absence_type as $key => $value) {
					if($value == "unjustified absence"){
						$x->addChild("option")->value($value)->label(gettext($value))->vtext(gettext($value))->selected("selected");
					}else{
						$x->addChild("option")->value($value)->label(gettext($value))->vtext(gettext($value));
					}
				}

				$r->type ="<div class='form-element' >"  . $x->render() . "</div>";

				$r->attendance = $this->tag("a")
									  ->addClass("icoattendance")
									  ->href("absence/status=add/usersid=". $r->attendance)
									  ->render();

				$r->insert 	   = $this->tag("a")
									  ->addClass("icodadd a-undefault")
									  ->style("cursor: pointer;")
									  ->addClass("insertAbsenceApi")
									  ->classification($r->insert)
									  ->render();

				$r->more = $this->tag("a")
									  ->addClass("icomore a-undefault")
									  ->style("cursor: pointer;")
									  ->href("users/learn/absence/id=" . $r->more. '/classesid=0')
									  ->render();

			}, $absence_type);

			$this->sql(".dataTable", $dtable);
	}

	public function absence_type(){

		$sql = new dbconnection_lib;
		$x = $sql->query("SELECT COLUMN_TYPE FROM
							INFORMATION_SCHEMA.COLUMNS
						    WHERE TABLE_NAME = 'absence'
						    AND COLUMN_NAME = 'type'");

		$x = $x->result->fetch_assoc();

		$x = preg_replace("/enum|\(|\)|\'/", '', $x['COLUMN_TYPE']);
		$x = preg_split("[,]", $x);

		return $x;
	}
}
?>