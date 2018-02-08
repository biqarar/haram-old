<?php
/**
 * class for list query result maker
 * @author baravak <itb.baravak@gmail.com>
 * @version 0.0.1
 */
class show_lib{
	private $blackList, $row = 0, $col = 0, $query;

	public $header = array();

	/**
	* @param table filed name $title
	* @param query result list $query
	*/
	public function __construct($title, $query){
		$this->header = array();
		$index = 0;
		// var_dump($title);
		foreach ($title as $key => $value) {
			$this->header[$index++] = array("name" => $key, "label" => $value);
		}
		// var_dump($this->header);
		$this->list = $query;
		$this->query = $query;
	}

	/**
	* sort columns
	*/
	private function sort(){
		ksort ($this->header);
		$header = array();
		foreach ($this->header as $key => $value) {
			$header[] = array("name" => $value['name'], "label" => $value['label']);
		}
		$this->header = $header;
	}

	/**
	* return filed index by $name
	* @param string $name
	* @return int
	*/
	private function getIndex($name){
		foreach ($this->header as $key => $value) {
			if($value['name'] == $name) return $key;
		}
		return -1;
	}

	/**
	* return filed name by $index
	* @param int $index
	* @return string
	*/
	private function getName($index){
		foreach ($this->header as $key => $value) {
			if($key == $index) return $value['name'];
		}
		return -1;
	}

	/**
	* remove column from list
	* @param string $name
	* @return \show
	*/
	public function removeCol($name){
		$name = preg_replace("/^\s*|\s*$/", "", $name);
		$name = preg_replace("/(\s*,\s*)/", ",", $name);
		$args = preg_split("/[,]/", $name);
		foreach ($args as $key => $value) {
			unset($this->header[$this->getIndex($value)]);
		}
		$this->sort();
		return $this;
	}

	public function white($name){
		$name = preg_replace("/^\s*|\s*$/", "", $name);
		$name = preg_replace("/(\s*,\s*)/", ",", $name);
		$args = preg_split("/[,]/", $name);
		$list = array();
		foreach ($args as $key => $value) {
			$list[] = $this->header[$this->getIndex($value)];
		}
		$this->header = $list;
		$this->sort();
		return $this;
	}

	/**
	* add column in list
	* @param string $name or int $index
	* @param string $name or string $label
	* @param string $label or empty
	* @return \show
	*/
	public function addCol(){
		$args = func_get_args();
		$index = (is_int($args[0])) ? $args[0] : count($this->header);
		$name = (is_int($args[0])) ? $args[1] : $args[0];
		$label = (isset($args[2]) && is_int($args[0])) ? $args[2] : $args[1];

		if(isset($this->header[$index])){
			$header = array();
			$start = false;
			foreach ($this->header as $key => $value) {
				if($key == $index){
					$start = true;
				}
				if($start){
					$header[$key+1] = array("name" => $value['name'], "label" => $value['label']);
				}else{
					$header[$key] = array("name" => $value['name'], "label" => $value['label']);
				}
			}
			$this->header = $header;
		}
		$this->header[$index] = array("name" => $name, "label" => $label);
		$this->sort();
		return $this;
	}

	/**
	* add columnt after $after
	* @param string $after
	* @param string $name
	* @param string $label
	* @return \show
	*/
	public function addColAfter($after, $name, $label){
		$index = $this->getIndex($after);
		$index = ($this->getIndex($after) >= 0) ? $index+1 : count($this->header);
		return $this->addCol($index, $name, $label);
	}

	/**
	* add columnt before $before
	* @param string $after
	* @param string $name
	* @param string $label
	* @return \show
	*/
	public function addColBefore($before, $name, $label){
		$index = $this->getIndex($before);
		$index = ($this->getIndex($before) > 0) ? $index : 0;
		return $this->addCol($index, $name, $label);
	}

	/**
	* add columnt at first list
	* @param string $name
	* @param string $label
	* @return \show
	*/
	public function addColFirst($name, $label){
		return $this->addCol(0, $name, $label);
	}

	/**
	* add columnt at end list
	* @param string $name
	* @param string $label
	* @return \show
	*/
	public function addColEnd($name, $label){
		return $this->addCol(count($this->header), $name, $label);
	}

	/**
	* select row of list
	* @param int $row
	* @return \show
	*/
	public function row($row){
		$this->row = $row;
		return $this;
	}

	/**
	* select columnt of list
	* @param string $col or int $col
	* @return \show
	*/
	public function col($col, $name = false){
		if(!is_int($col)){
			$col = $this->getIndex($col);
			$col = ($col >=0 ) ? $col : 0;
		}
		$this->col = $col;
		if($name !== false){
			$this->header[$col]['label'] = $name;
		}
		return $this;
	}

	/**
	* select row and columnt of list
	* @param int $row
	* @param string $col or int $col
	* @return \show
	*/
	public function select($row, $col){
		$this->row($row);
		$this->col($col);
		return $this;
	}

	/**
	* set html tag for column
	* @param string $html or \tags
	*/
	public function html($html){
		$name = $this->getName($this->col);
		if($this->row != -1){
			if(isset($this->list[$this->row])){
				$this->list[$this->row][$name] = $html;
			}
		}else{
			foreach ($this->list as $key => $value) {
				$this->list[$key][$name] = $html;
			}
		}
		return $this;	
	}

	/**
	* callback for setValue replaces
	* @param string $str
	* @return string
	*/
	private function getValue($str){
		if(isset($this->query[$this->row][$str[1]])){
			return $this->query[$this->row][$str[1]];
		}
	}

	/**
	* set dynamic query value in tags
	* @param array $html
	* @return array
	*/
	private function setValue($html){
		$ret = $html;
		$pattern = "/%([^%]*)%/";
		$ret['text'] = preg_replace_callback($pattern, array($this, 'getValue'), $ret['text']);
		$nAttr = array();
		$vAttr = array();
		foreach ($ret['attr'] as $key => $value) {
			$nAttr[] = preg_replace_callback($pattern, array($this, 'getValue'), $key);
			$vAttr[] = preg_replace_callback($pattern, array($this, 'getValue'), $value);
		}
		$ret['attr'] = array_combine($nAttr, $vAttr);

		foreach ($ret['childs'] as $key => $value) {
			$ret['childs'][$key] = $this->setValue($value);
		}

		return $ret;
	}

	/**
	* compile \show and make
	* @return array
	*/
	public function compile(){
		$ret = array();
		$ret['header'] = array();
		foreach ($this->header as $key => $value) {
			$ret['header'][$value['name']] = _($value['label']);
		}
		foreach ($this->list as $key => $value) {
			$this->row = $key;
			foreach ($this->header as $k => $v) {
				$html = (isset($this->list[$key][$v['name']])) ? $this->list[$key][$v['name']] : '';
				if(is_object($html) && get_class($html) == 'tagMaker_lib'){
					$html = $this->setValue($html->compile());
				}else{
					$pattern = "/%(.*)%/";
					$html = preg_replace_callback($pattern, array($this, 'getValue'), $html);
				}
				$ret['list'][$key][$v['name']] = $html;
			}
		}
		return $ret;
	}
}
?>