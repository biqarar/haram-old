<?php
class autoload{
	public $classcode = 1200;
	static function hendel($name, $ret = false){
		$otLoad = false;
		if($ret == false){
			$Load = debug_backtrace(true);
			if(isset($Load[2]) && isset($Load[2]['function']) && $Load[2]['function'] == "class_exists"){
				$ret = true;
				$otLoad = true;
			}
		}
		if(($split_name = preg_split("[\\\]", $name))  && preg_match("[\\\]", $name) ){
			$path = ""; 
			switch ($split_name[0]) {
				case 'sql':
				$path = sql;
				break;
				case 'lib':
				$path = lib;
				break;
				case 'cls':
				$path = cls;
				break;
				case 'helper':
				$path = helper;
				break;
			}
			$subPath = join(array_splice($split_name, 1), "/");
			$filename = $path.$subPath.".$split_name[0].php";
			if(file_exists($filename)){
				require_once ($filename);
			}else{
				if($ret){
					return false;
				}else{
					page_lib::page("$split_name[0] => $subPath not found");
				}
			}
		}
		$spn = preg_split("[_]", $name);
		$type = $spn[count($spn) -1];
		$name = array_slice($spn, 0, count($spn)-1);
		$content = content;
		if(class_exists("config_hendel_lib")){
			$content = config_hendel_lib::$content;
		}
		if(preg_match("/^(model|view|controller|forms|query)$/", $type)){
			if(count($name) == 0){
				$file = join($name,'/');
				$filename = $content.config_lib::$class;
				$filename .= '/'.config_lib::$method;
				$filename .= (config_lib::$child) ? '/'.config_lib::$child : '';
			}else{
				$filename = $content.'main';
			}
			$filename .= '/'.(($type == 'forms')? 'view' : $type).'.php';
			if(file_exists($filename)){
				if($ret && !$otLoad){
					return true;
				}else{
					require_once $filename;
				}
			}else{
				if($ret){
					return false;
				}else{
					page_lib::page(join($spn, '::'));
				}
			}
		}else if(preg_match("/^(lib|cls)$/",$type)){
			if($type == 'lib'){
				$path = lib;
			}else{
				$path = cls;
			}
			$filename = $path.join($name,'/').".$type.php";
			if(file_exists($filename)){
				if($ret && !$otLoad){
					return true;
				}else{
					require_once $filename;
				}
			}else{			
				$name = join($spn,'::');
				if($ret){
					return false;
				}else{
					page_lib::core(join($spn,'::'));
				}
			}
		}
	}

	static function check($filename){
		return self::hendel($filename, true);

	}
}
spl_autoload_register("autoload::hendel");
class saloos extends \lib\saloos{

}
$saloos = new saloos;
$x = saloos::helper()->validate()->track;
?>