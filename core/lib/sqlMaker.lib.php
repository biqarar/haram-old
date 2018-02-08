<?PHP
class sqlMaker_lib{
	public $set = array(), $conditions = array(), $groupOpen = false, $limit = array(), $groupby, $table = false, $fields, $order, $foreign = array(), $join = array();

	public function __construct(){
		$this->join = (object) array();
	}

	private function setCaller($name, $args){
		$this->set[$name] = $args[0];
	}

	private function conditionsCaller($name, $args){
		$syntax = count($args);
		$condition = $args[0];
		$field 	= ($name) ? "#$name" : $args[1];
		switch ($syntax) {
			case 4:
			$operator	= $args[2];
			$value	= $args[3];
			break;
			case 3:
			$operator = $args[1];
			$value	= $args[2];
			break;
			default:
			$operator = "=";
			$value	= $args[1];
			break;
		}
		switch ($condition) {
			case 'like':
			$condition = "where";
			$operator = "LIKE";
			break;

			case 'andlike':
			$condition = "and";
			$operator = "LIKE";
			break;

			case 'orlike':
			$condition = "or";
			$operator = "LIKE";
			break;
		}
		$this->condition($condition, $field, $operator, $value);
	}
	private function conditionCaller($name, $args){
		$array = array(
			"condition" => $args[0],
			"field" => $args[1],
			"operator" => $args[2],
			"value" => $args[3]
			);

		if($this->groupOpen !== false){
			array_push($this->conditions[$this->groupOpen], $array);
		}else{
			array_push($this->conditions, $array);
		}
	}
	private function loadCaller($name, $args){
		$status = isset($args[0]) && $args[0] == 'edit' ? 'edit' : 'add';
		$sql = $this->makeSql();
		$forms = new forms_lib;
		foreach ($sql->getForms() as $key => $value) {
			if(isset($value->closure) && $value->closure->form){
				$forms->make($value->closure->form, $key);
			}
		}
		$forms->add("hidden", "#hidden")
		->value("{$status}_{$this->table}");
		$forms->atFirst("hidden");

		$forms->add("submit", "#submit{$status}");
		return $forms;
	}

	private function groupCaller($name){
		if($name === 'open'){
			$this->groupOpen = count($this->conditions);
			$this->conditions[$this->groupOpen] = array();
		}elseif($name === 'close'){
			$this->groupOpen = false;
		}
	}

	private function orderCaller($name, $args){
		$order = (isset($args[0]) && strtolower($args[0]) == "desc")? 'DESC' : 'ASC';
		$this->order = $name . " $order";
	}

	private function limitCaller($name, $args){
		if(count($args) == 1){
			$this->limit = array(0, $args[0]);
		}elseif(count($args) == 2){
			$this->limit = array($args[0], $args[1]);
		}
	}

	private function fieldCaller($name, $args){
		if($name === null){
			$this->fields = $args;
		}elseif($name === 'all'){
			$this->fields = "*";
		}else{
			if(!is_array($this->fields)){
				$this->fields = array();
			}
			array_push($this->fields, $name);
			if(count($args) == 1){
				if(!is_array($this->fields)){
					$this->fieldsAs = array();
				}
				$this->fieldsAs[$name] = $args[0];
			}
		}

	}

	private function foreignCaller($name){
		$sql = new sqlMaker_lib;
		$sql->table = $name;
		$sql->subClass = true;
		array_push($this->foreign, $sql);
		return $sql;
	}

	private function groupbyCaller($name){
		if(!is_array($this->groupby)){
			$this->groupby = array();
		}
		array_push($this->groupby, $name);
	}

	private function syntaxCaller($name, $args){
		$sql = $this->makeSql();
		$syntax = $args[0];
		return $sql->$syntax();

	}

	private function makeSql(){
		// $black = array("groupOpen", "subClass");
		// $jBlack = $black;
		// array_push($jBlack, "join");
		// $maker = (object) array();
		// foreach ($this as $key => $value) {
		// 	if(!preg_grep("/^".$key."$/", $black)){
		// 		if($key == "join"){
		// 			$maker->join = array();
		// 			foreach ($this->join as $jk => $jv) {
		// 				$jMaker = (object) array();
		// 				foreach ($jv as $jkey => $jvalue) {
		// 					if(!preg_grep("/^".$jkey."$/", $jBlack)){
		// 						$jMaker->{$jkey} = $jvalue;
		// 					}
		// 				}
		// 				$maker->join[] = $jMaker;
		// 			}
		// 		}else{
		// 			$maker->$key = $this->$key;
		// 		}

		// 	}
		// }
		$sql = new sql_lib($this);
		return $sql;
	}

	public function tableCaller($name){
		$sql = new sqlMaker_lib;
		$sql->table = $name;
		return $sql;
	}
	public function joinCaller($name, $args){
		$sql = new sqlMaker_lib;
		$this->join->$name = $sql;
		$sql->table = $name;
		$sql->joinCondition = isset($args[0]) && $args[0] ? $args[0] : "INNER";
		$sql->subClass = true;
		return $sql;
	}

	function __call($name, $args){
		$remove = array("table", "select", "update", "insert", "delete", "form", "join");
		if(isset($this->subClass) && preg_grep("/^".$name."$/", $remove)){
			page_lib::page("joinMaker method $sCaller not found");
		}
		preg_match("/^([a-z]+)([A-Z].*)?$/", $name, $caller);
		switch ($caller[1]) {
			case 'where':
			case 'if':
			case 'and':
			case 'or':
			case 'like':
			case 'orlike':
			case 'andlike':
			$sCaller = 'conditionsCaller';
			array_unshift($args, $caller[1]);
			break;

			case 'selects':
			$sCaller = 'selectCaller';
			break;

			case 'update':
			case 'insert':
			case 'delete':
			case 'select':
			case 'show':
			$sCaller = 'syntaxCaller';
			array_unshift($args, $caller[1]);
			break;

			default:
			$sCaller = $caller[1].'Caller';
			break;
		}

		$sName = isset($caller[2]) ? strtolower($caller[2]) : null;
		if(!method_exists($this, $sCaller)){
			page_lib::page("sqlMaker method $sCaller not found");
		}
		$ret = $this->$sCaller($sName, $args);
		return ($ret === null) ? $this : $ret;
	}

	public static function __callStatic($name, $args){
		$sql = new sqlMaker_lib;
		$sql->table = $name;
		return $sql;
	}
}
?>