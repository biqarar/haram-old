<?php
class g_lib{
	function ifAjax(){
		return ifAjax();
	}
}

function ifAjax(){
	$Header = apache_request_headers();
	if(isset($Header['X-Requested-With']) && $Header['X-Requested-With'] == 'XMLHttpRequest'){
		return true;
	}else{
		return false;
	}
}

function extendObject(){
	$arg = func_get_args();
	$loop = false;
	$wloop = false;
	if(is_bool($arg[0])){
		if($arg[0]){
			$loop = true;
		}
		array_shift($arg);
	}elseif(is_array($arg[0])){
		$wloop = $arg[0];
		array_shift($arg);
	}
	$base = $arg[0];
	array_shift($arg);

	foreach ($arg as $K => $V) {
		foreach ($V as $key => $value) {
			if($loop && (is_object($value) || is_array($value)) && isset($base->$key)){
				$value = call_user_func(__FUNCTION__, true, $base->$key, $value);
			}elseif($wloop && count(preg_grep("/^$key$/", $wloop)) > 0 ){
				$value = call_user_func(__FUNCTION__, $base->$key, $value);
			}
			$base->$key = $value;
		}
	}
	return $base;
}
?>