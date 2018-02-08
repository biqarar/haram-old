<?php
class query_files_cls extends query_cls {
	static $uploadPath = '../../';
	static $mainPath = 'updfiles';
	static $prefixPath = '';
	static $publicAddr	= 'images';
	function form($obj, $opt){
		$opt['group'] = isset($opt['group']) ? $opt['group'] : 'private';
		$f = $obj->form("@files", $obj->uStatus());
		if($obj->uStatus() == 'add'){
			$fileINP = $obj->form("file")->name("file")->label("file_input");
			$f->add('file', $fileINP);
			$f->atEnd('submit');
		}
		$session = self::makeBase($opt['group']);
		$fSession = $obj->form("hidden")->name("base")->value($session);
		$f->add("base", $fSession);
		return $f;
	}
	
	function getFile($id = false){
		$file = $this->sql()->tableFiles()
		->whereId($id)
		->limit(1)
		->select();
		return $file->assoc();
	}
	static function makeBase($group){
		$session = rand(1141301, 8251301);
		$_SESSION['base_'.$session] = $group;
		return $session;
	}

	static function checkbase($name){
		$public = false;
		if(!preg_match("/^\d{7}$/", post::base())){
			debug_lib::fatal("base file incorrect", 'base', 'form');
		}elseif(!isset($_SESSION['base_'.post::base()])){
			debug_lib::fatal("base permission denied", 'base', 'form');
		}elseif(!$public = self::checkType($_SESSION['base_'.post::base()], $name)){
			debug_lib::fatal("type permission denied", 'file', 'form');
		}
		return $public;
	}

	static function getAddr(){
		if(!is_dir(core.self::$uploadPath.self::$mainPath) && !mkdir(core.self::$uploadPath.self::$mainPath)){
			debug_lib::fatal("error in file transfer", 'file', 'form');
		}
		return core.self::$uploadPath.self::$mainPath.'/'.self::$prefixPath;
	}
	static function getPath(){
		return core.self::$uploadPath;
	}
	static function checkType($group, $name){
		switch ($group) {
			case 'news':
			if($name['group'] != 'images') return false;
			else return 'public';
			break;
			case 'private':
			return true;
			break;
			default:
			return false;
			break;
		}
	}

	static function imageCR($file, $options){
		$fileName = str_pad($file['id'], 10, "0", STR_PAD_LEFT);
		$fileAddr = self::getAddr().$file['folder'].'/'.$fileName;
		$image_size = getimagesize($fileAddr);
		if(isset($options[6])){
			$Wx = $image_size[0];
			$Hx = $image_size[1];
			$nw = $options[6];
			$aM = $Wx / $options[6];
			$nh = $Hx / $aM;
			$options[2] *= $aM;
			$options[3] = ($options[3] * $Hx) / $nh;
			$options[4] *= $aM;
			$options[5] *= $aM;
		}
		switch ($file['type']) {
			case "gif":
			$img = imagecreatefromgif($fileAddr);
			break;
			case "jpeg":
			case "jpg":
			$img = imagecreatefromjpeg($fileAddr);
			break;
			case "png":
			$img = imagecreatefrompng($fileAddr);
			break;
		}
		$to_crop_array = array('x' =>$options[2] , 'y' => $options[3], 'width' => $options[4], 'height'=> $options[5]);
		$crop = imagecrop($img, $to_crop_array);
		$img_end = imagecreatetruecolor($options[0], $options[1]);
		imagecopyresized($img_end, $crop, 0, 0, 0, 0, $options[0]+1, $options[1]+1, $options[4], $options[5]);
		$public = self::$publicAddr;
		if(!is_dir($public) && !mkdir($public)){
			debug_lib::fatal("make dir error", "makedir", "public");
		}
		$public = self::$publicAddr.'/'.$file['folder'];
		if(!is_dir($public) && !mkdir($public)){
			debug_lib::fatal("make dir error", "makedir", "public");
		}
		$public .='/'.$fileName.'.jpg';
		$imgc = imagepng($img_end, $public, 9);
		return $imgc ? $public : false;

	}
}