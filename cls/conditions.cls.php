<?php
class conditions_cls{
	public static function parse($condition){
		$object = (object) array();
		$split = preg_split("[;]", $condition, -1);
		foreach ($split as $key => $value) {
			$kv = preg_split("[:]", $value, 2);
			$object->{$kv[0]} = isset($kv[1]) ? $kv[1] : null;
		}
		return $object;
	}
}
?>