<?php
/**
 * html tag maker
 * @author baravak <itb.baravak@gmail.com>
 * @version 0.0.1
 */
class tagMaker_lib {

	/**
	* @param html tag $name
	*/
	function __construct($name) {
		$this->tagName = $name;
		$this->attr = array();
		$this->childs = array();
		$this->text = '';
	}

	/**
	* attribute maker
	* @param string $name
	* @param string $value
	* @return \tagsMaker
	*/
	function attr($name, $value = '') {
		$this->attr[$name] = $value;
		return $this;
	}

	/**
	* set class attribute
	* @param string $class
	* @return \tagsMaker
	*/
	function classname($class) {
		return $this->attr('class', $class);
	}

	/**
	* add $class to class attribute
	* @param string $class
	* @return \tagsMaker
	*/
	function addClass($class) {
		if (!isset($this->attr['class'])) {
			return $this->classname($class);
		}
		$aClass = preg_split("/ /", $this->attr['class']);
		array_push($aClass, $class);
		return $this->classname(join(" ", $aClass));
	}

	/**
	* remove $class from class attribute
	* @param string $class
	* @return \tagsMaker
	*/
	public function removeClass($class) {
		if (!isset($this->attr['class'])) {
			return $this;
		}
		$aClass = preg_split("/ /", $this->attr['class'], -1, PREG_SPLIT_NO_EMPTY);
		$index = array_search($class, $aClass);
		if ($index !== false) {
			unset($aClass[$index]);
		}
		return $this->classname(join(" ", $aClass));
	}

	/**
	* add text to Element
	* @param string $text
	* @return \tagsMaker
	*/
	public function text($text) {
		$this->text = gettext($text);
		return $this;
	}

	/**
	* append children to Element
	* @param html tag name $name
	* @return \this
	*/
	function addChild($name) {
		$childs = new $this($name);
		if (!isset($this->childs)) {
			$this->childs = array();
		}
		$this->childs[] = $childs;
		return $childs;
	}

	/**
	* compile tag to array for you :)
	* @return array
	*/
	public function compile() {
		$array = array();
		$array['tagName'] = $this->tagName;
		$array['text'] = $this->text;
		$array['attr'] = $this->attr;
		$array['childs'] = array();
		foreach ($this->childs as $key => $value) {
			$array['childs'][$key] = $this->childs[$key]->compile();
		}
		return $array;
	}

	public function render(){
		$arr = $this->compile();
		$string = '';
		$this->iRender($string, $arr);
		return $string;
	}

	private function iRender(&$string, $tag){
		$tagName = $tag['tagName'];
		$string .= "<$tagName";
		foreach ($tag['attr'] as $key => $value) {
			$string .= ' '.$key.'="'.$value.'"';
		}
		$string .= ">";
		if(count($tag['childs']) > 0){
			foreach ($tag['childs'] as $key => $value) {
				$this->iRender($string, $value);
			}
		}
		$blackend = array("br", "input", "hr");
		if(!preg_grep("/^$tagName$/", $blackend)){
			if(isset($tag['attr']['vtext'])){
				$string .= $tag['attr']['vtext'];
			}
			$string .= "</$tagName>";
		}
	}

	/**
	* magic function for make atomaticly attribute
	* @param string $name
	* @param array $arg
	* @return \tagsMaker
	* $this->data_type("json") => <div data-type="json"></div>
	* $this->data__type("json") => <div data_type="json"></div>
	*/
	function __call($name, $arg) {
		$name = preg_replace("/^([a-zA-Z0-9]+)_([a-zA-Z0-9]+)$/", "$1-$2", $name);
		$name = preg_replace("/^([a-zA-Z0-9]+)__([a-zA-Z0-9]+)$/", "$1_$2", $name);
		return $this->attr($name, $arg[0]);
	}

}
?>