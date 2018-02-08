<?php 
class fileexec_cls{
	public static $mdocs = 5;
	public static $docs = array(
		'doc', 'docx', 'pdf', 'txt'
		);

	public static $mimages = .5;
	public static $images = array(
		'jpg', 'jpeg', 'gif', 'png',
		);

	public static $mmultimedia = 200;
	public static $multimedia = array(
		'mp3', 'mp4', 'flv', 'ogg', 'avi'
		);
	public static function validation($filename, $array = false){
		$name = array();
		if(is_array($filename)){
			$name[2] = $filename['type'];
			$name[1] = $filename['id'];
		}else{
			preg_match("/^(.*)\.([^\.]*)$/", $filename, $name);
			if(count($name) !== 3){
				return false;
			}
		}
		$name[2] = strtolower($name[2]);
		$obj = array(
			'docs'		=> self::$docs,
			'images'		=> self::$images,
			'multimedia'	=> self::$multimedia
			);
		$access = false;
		foreach ($obj as $key => $value) {
			$group = $key;
			if(array_search($name[2], $value) !== false){
				$access = true;
				break;
			}
		}
		if(!$access) return false;
		return $array === true ? array('name' => $name[1], 'exec' => $name[2], 'group' => $group, 'max' => self::${"m$group"}) : true;
	}
}
?>