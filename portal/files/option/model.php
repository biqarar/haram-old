<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/

class model extends main_model{
	public $original_path;
	public $file_transaction = array();

	public $post_file = array();

	public function post_files(){
		$this->rollback(function(){
			debug_lib::fatal("file uploaded error");
			foreach ($this->file_transaction as $key => $value) {
				unlink($this->original_path.$value);
			}
		});
		$base = config_lib::$surl['base'];
		$parm_id = post::id();
		$tag = post::tag();
		$file = $_FILES['file'];
		$this->post_file = (object) array(
			'base' => $base,
			'parm_id' => $parm_id,
			'tag' => $tag
			);
		if(!$this->condition($file)) return;
		$insert_query = $this->sql()->tableFiles()
		->setTitle(post::title())
		->setType(post::title())
		->setSize($file['size']/ 1000000)
		->setFile_tag_id($tag)
		->setType($this->post_file->type)
		->setDescription(post::description());
		$this->post_file->query = $insert_query;
		$insert_query = $insert_query->insert();
		if(!$insert_query){
			return false;
		}
		$this->post_file->id = $insert_query->LAST_INSERT_ID();
		if($this->post_file->id === false){
			return false;
		}
		if(!$this->upload_file($file)) return;
		if($this->post_file->list->type == 'image'){
			$this->crop($file);
		}
		$this->commit(function(){
			debug_lib::true("file uploaded");
		});
	}

	private function condition($file){
		$get_condition = $this->sql()->tableFile_tag()
		->whereTable_name($this->post_file->base)
		->andId($this->post_file->tag)->select();
		$list = $get_condition->Object();
		$this->post_file->list = $list;
		$condition = conditions_cls::parse($list->condition);
		$condition->ratio = number_format($condition->ratio, 9);
		$this->post_file->condition = $condition;
		$exec = array();
		switch ($list->type) {
			case 'image':
			$exec = array('png', 'jpg', 'jpeg', 'gif', 'tiff');
			break;
			case 'multimedia':
			$exec = array('mp3', 'mp4', 'flv', 'avi', 'ogg', 'wmv', '3gp');
			break;
			case 'multimedia':
			$exec = array('docx', 'doc', 'xls', 'xlsx', 'ppt', 'pps', 'pptx', 'ppsx');
			break;
			case 'multimedia':
			$exec = array('zip', 'rar', '7zip');
			break;
			
		}
		$file_name = explode(".", $file['name']);
		$file_exec = strtolower(end($file_name));
		$this->post_file->type = $file_exec;
		if(!preg_grep("/^{$file_exec}$/", $exec)){
			debug_lib::fatal("type must be ". join(', ', $exec));
			return false;
		}
		if($list->max_size < ($file['size']/ 1000000)){
			debug_lib::fatal("max size must be ". $list->max_size .' mb');
			return false;
		}
		if($list->type == 'image'){
			$crop = explode(" ", post::crop_size());
			foreach ($crop as $key => $value) {
				$crop[$key] = (float) $value;
			}
			$this->post_file->crop = $crop;
			switch ($condition->ratio) {
				case '1.777777778':
				$rat_name = '16:9';
				break;
				case '1.333333333':
				$rat_name = '4:3';
				break;
				case '0.75':
				$rat_name = '3:4';
				break;
				case '0.5625':
				$rat_name = '9:16';
				break;
				default:
				$rat_name = $condition->ratio;
				break;
			}
			$crop_rat = number_format($crop[2] / $crop[3], 9);
			if($condition->ratio != $crop_rat){
				debug_lib::fatal("ratio must be ". $rat_name);
				return false;
			}
		}
		return true;
	}
	private function upload_file($file){
		$path = $this->original_path = root_dir.'../haram_updfiles/';
		if(!is_dir($path)){
			if(!mkdir($path)){
				debug_lib::fatal("haram_updfiles path is not exists");
				return false;
			}
		}
		if(!move_uploaded_file($file['tmp_name'], $path.$this->post_file->id)){
			debug_lib::fatal("The file was not moved");
			return false;
		}
		array_push($this->file_transaction, $this->post_file->id);
		return true;
	}

	private function crop(){
		$src = $this->original_path.$this->post_file->id;
		$options = preg_split("[ ]", post::crop_size());

		$image_size = getimagesize($src);
		if(isset($options[4])){
			$options[4] = (int) $options[4];
			$Wx = $image_size[0];
			$Hx = $image_size[1];
			$nw = $options[4];
			$aM = $Wx / $options[4];
			$nh = $Hx / $aM;
			$options[0] *= $aM;
			$options[1] = ($options[1] * $Hx) / $nh;
			$options[2] *= $aM;
			$options[3] *= $aM;
		}
		switch ($this->post_file->type) {
			case 'jpg':
			case 'jpeg':
			$img_r = imagecreatefromjpeg($src);
			break;
			case 'png':
			$img_r = imagecreatefrompng($src);
			break;
			case 'gif':
			$img_r = imagecreatefromgif($src);
			break;
		}
		foreach (array('120') as $key => $value) {
			if(!$this->save_crop($value, $options, $img_r)){
				return false;
			}
		}

		foreach (preg_split("[ ]", $this->post_file->condition->width) as $key => $value) {
			if(!$this->save_crop($value, $options, $img_r)){
				return false;
			}
		}
		return true;
	}

	private function save_crop($value, $options, $img_r){
		$targ_w = $value;
		$targ_h = $value/$this->post_file->condition->ratio;
		$dst_r = ImageCreateTrueColor($targ_w, $targ_h);
		imagecopyresampled($dst_r, $img_r, 0, 0, $options[0], $options[1],  $targ_w, $targ_h, $options[2], $options[3]);
		$isub_query = clone $this->post_file->query;
		$isub_query->setDependence($this->post_file->id)
		->setSize("#NULL");
		$sub_query = $isub_query->insert();
		if($sub_query){
			$id = $sub_query->LAST_INSERT_ID();
			if(imagejpeg($dst_r, $this->original_path.$id, 100)){
				array_push($this->file_transaction, $id);
				$size = filesize($this->original_path.$id);
				$isub_query = clone $this->post_file->query;
				$q = $isub_query->setSize($size / 1000000)
				->whereId($id)
				->setDependence($this->post_file->id)
				->update();
			}else{
				debug_lib::fatal("error in crop size ". $value);
				return false;
			}
		}else{
			debug_lib::fatal("error in crop size ". $value);
			return false;
		}
		return true;
	}

	public function sql_get_tag($base){
		$sql = $this->sql()->tableFile_tag()->whereTable_name($base);
		return $sql->select()->allObject();
	}

	public function post_add_files(){
		$uploadPath = query_files_cls::getAddr();
		$FILE = isset($_FILES) && isset($_FILES['file'])? $_FILES['file'] : false;
		$name = false;
		$gPathId = false;
		if(!$FILE){
			debug_lib::fatal('upload your file', 'file', 'form');
		}elseif($FILE){
			if($FILE['error'] == 4){
				debug_lib::fatal('select your file', 'file', 'form');
			}
			if($FILE['error'] == 0){
				$name = fileexec_cls::validation($FILE['name'], true);
				if(!$name){
					debug_lib::fatal('incorrect file exec', 'file', 'form');
				}
			}
		}
		if($name){
			$size = (float) number_format($FILE['size'] / (1024 * 1024), 2);
			if($size > $name['max']){
				debug_lib::fatal("max file size is $name[max] mb", 'file', 'form');
			}
			$sqlU = $this->sql()->tableFiles()
			->setTitle(post::title())
			->setSize($size)
			->setType($name['exec'])
			->setDescription(post::description())
			->insert();
			$id = $sqlU->LAST_INSERT_ID();
			$path = $uploadPath;
			$gPathId = $this->sql()->tableFiles()->whereId($id)->select()->assoc();
			$path .= $gPathId['folder'];
			if(!is_dir($path) && !mkdir($path)){
				debug_lib::fatal("error in file transfer", 'file', 'form');
			}
			$filename = str_pad($id, 10, "0", STR_PAD_LEFT);

			$copyPublic = query_files_cls::checkbase($name);
			if(debug_lib::$status){
				$fileAddr = $path.'/'.$filename;
				if(!move_uploaded_file($FILE['tmp_name'], $fileAddr)){
					debug_lib::fatal("error in file transfer", 'file', 'form');
				}elseif($copyPublic == 'public'){
					$gPathId['fileAddr'] = str_replace(query_files_cls::getPath(), "", $fileAddr);
					// $pPath = $this->publicAddr.'/'.$this->prefixPath.$gPathId['folder'];
					// $pFileAddr = $pPath.'/'.$filename.'.'.$name['exec'];
					// if(!is_dir($this->publicAddr) && !mkdir($this->publicAddr)){
					// 	debug_lib::fatal("error in public file transfer", 'file', 'form');
					// }elseif(!is_dir($pPath) && !mkdir($pPath)){
					// 	debug_lib::fatal("error in public file transfer", 'file', 'form');
					// }elseif(!copy($fileAddr, $pFileAddr)){
					// 	debug_lib::fatal("error in public file transfer", 'file', 'form');
					// }else{
					// 	$gPathId['publicAddr'] = $pFileAddr;
					// }
					// if(debug_lib::$status){
					// 	unlink($fileAddr);
					// }
				}
			}

		}
		
		$this->commit(function($array) {
			$base = $_SESSION['base_'.post::base()];
			unset($_SESSION['base_'.post::base()]);
			$array['base'] = query_files_cls::makeBase($base);
			debug_lib::true("[[insert files successful]]");
			debug_lib::msg($array);
		}, $gPathId);
		$this->rollback(function() {
			debug_lib::fatal("[[insert files failed]]");
		});
	}

	public function post_edit_files(){
		$sql = $this->makeQuery()
		->whereId($this->uId())
		->update();
		$this->commit(function() {
			debug_lib::true("[[update files successful]]");
		});
		$this->rollback(function() {
			debug_lib::fatal("[[update files failed]]");
		});
	}
}
?>