<?php
class query_edit_cls extends query_cls
{
	public function config($table = false, $id = false, $form = false)	{

		//------------------------------ if edit not called $id == 0
		if(intval($id) == 0) return false;
		
		$id = intval($id);

		//------------------------------ get result whit table and id from mysql engine		
		$result =  $this->query($table, $id); 

		//------------------------------ check for real "id" in url
		if($result->num() >= 1 ) {
			$option = $result->assoc();
		}else{
			page_lib::access("id not found");				
		}

		//------------------------------ load form whit value from database
		foreach ($form as $key => $value) {
			if(isset($option[$key])){
				$oForm = $form->$key;
				if($oForm->attr['type'] == "radio" || $oForm->attr['type'] == "select" || $oForm->attr['type'] == "checkbox") {
					foreach ($oForm->child as $k => $v) {
						if($v->attr["value"] == $option[$key]){
							if ($oForm->attr['type'] == "select"){
								$form->$key->child($k)->selected("selected");
							}else{
								$v->checked("checked");
							}
						}
					}
				}else{
					$oForm->value($option[$key]);
				}
			}
		}
	}

	/**
	* @return obecj 
	* result sql whit select id
	*/
	public function query($table = false, $id = false) {
		$table = "table".ucfirst($table);
		return $this->sql()->$table()->whereId($id)->select();
	}
}
?>