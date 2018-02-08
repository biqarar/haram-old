<?php
class query_unique_cls extends query_cls
{
	public function config($table = false, $where = false) {
		$table = "table". ucfirst($table);
		// $sql = $this->sql()->$table()->where ????
	}
}
?>