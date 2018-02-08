<?php
class query_assoc_cls extends query_cls
{
	public function province() {
		return $this->sql()->tableProvince()->select()->allAssoc();
	}

	public function education() {
		return $this->sql()->tableEducation()->groupbyGroup()->select()->allAssoc();
	}

	public function foreign($table = false, $id = false, $field = false, $where = "id" , $andWhere = false) {
		$table     = "table" . ucfirst($table);
		$id        = intval($id);
		$funcField = "field" . ucfirst($field);
		$where     = "where" . ucfirst($where);
		if($andWhere) {
			$a        = preg_split("/\=/",$andWhere);
			$andWhere = "and" . ucfirst($a[0]);
			$andValue = $a[1];	
			$sql = $this->sql()->$table()->$where($id)->$andWhere($andValue)->limit(1)->$funcField()->select();
		}else{
			$sql = $this->sql()->$table()->$where($id)->limit(1)->$funcField()->select();
		}
		return $sql->assoc($field);
	}
}
?>