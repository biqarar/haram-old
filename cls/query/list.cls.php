<?php
class query_list_cls extends query_cls
{
	function config($table, $fn = false)
	{
		$sql = $this->sql();
		$result = $sql::$table();
		if(is_object($fn)){
			$arg = func_get_args();
			$args = array_splice($arg, 2);
			array_unshift($args, $result);
			call_user_func_array($fn, $args);
		}

		$query = $result->select();

		$fl = $query->oFieldNames();
		$header = array();
		$tables = array();
		foreach ($fl as $key => $value) {
			if(!isset($tables[$value->orgtable])){
				$cName = "\\sql\\{$value->orgtable}";
				$tables[$value->orgtable] = new $cName;
			}
			$tbl = $tables[$value->orgtable];
			$index = $value->name;
			$label = isset($tbl->{$value->orgname}['label']) ? $tbl->{$value->orgname}['label'] : $value->name;
			$header[$index] = $label;
		}
		return new show_lib($header, $query->allAssoc());
	}

	function card($table, $id = 0 , $where = "id") {

		$where = "where" . ucfirst($where);

		//------------------------------ make card whit .list query
		$card = $this->config($table , function ($query , $id, $where){
			$query->$where($id);
		}, $id , $where)->compile();

		//------------------------------ remove field if count of field > 10
		foreach ($card as $key => $value) {
			if($key = "header"){
				$i = 1;
				foreach ($value as $k => $v) {
					// if($k == "id") unset($card["header"][$key]);
					if($i >= 10){
						unset($card["header"][$k]);
					}
					$i++;
				}
			}
			if($key = "list"){
				foreach ($value as $k => $v) {
				// if($k == "id") unset($card["list"][0][$k]);
					$i = 1;
					foreach ($value as $k => $v) {
						if($i >= 10){
							unset($card["list"][0][$k]);
						}
						$i++;
					}
				}

			}
		}

		//------------------------------ globals : title , addLink , editLink , moreLink
		$return = array();
		$return['list'] = $card;
		$return['title'] = $table;
		$return["addLink"] = "$table/status=add";
		$return["editLink"] = "$table/status=edit/id=$id";
		$return["moreLink"] = "$table/status=detail/id=$id";
		return $return;
	}

	function permission($table, $operator) {}
}
?>