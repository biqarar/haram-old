<?PHP
class sqlMaker_lib{
	public $set = array(), $conditions = array(), $groupOpen = false, $limit = array(), $groupby, $table = false, $fields, $order, $foreign = array();

	private function setCaller($name, $args){
		$this->set[$name] = $args[0];
	}

	private function conditionsCaller($name, $args){
		$operator = $args[0];
		$operator = preg_replace("/^\s+|\s+$/", "", $operator);
		array_shift($args);
		if(isset($args[1]) && !empty($args[1])){
			preg_match("/^(.+)condition$/", $args[1], $typeC);
			$operator .=" $typeC[1]" ;
		}
		if($this->groupOpen !== false){
			array_push($this->conditions[$this->groupOpen], array("operator" => $operator, "field" => $name, "condition" => $args[0]));
		}else{
			array_push($this->conditions, array("operator" => $operator, "field" => $name, "condition" => $args[0]));
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
		$order = (isset($args[0]) && $args[0] == "desc")? 'DESC' : 'ASC';
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
		}

	}

	private function foreignCaller($name){
		array_push($this->foreign, $name);
	}

	private function groupbyCaller($name){
		$this->groupby = $name;
	}

	private function syntaxCaller($name, $args){
		$sql = $this->makeSql();
		$syntax = $args[0];
		return $sql->$syntax();

	}

	private function makeSql(){
		$sql = new sql_lib;
		// check permission in haram prject
		if(class_exists("sql_cls")){
			$s = new sql_cls($this);
		}
		foreach ($this as $key => $value) {
			$sql->$key = $this->$key;
		}
		return $sql;
	}

	public function tableCaller($name){
		$sql = new sqlMaker_lib;
		$sql->table = $name;
		return $sql;
	}

	function __call($name, $args){
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

			case 'condition':
			$sCaller = 'conditionsCaller';
			break; 

			case 'andcondition':
			case 'orcondition':
			$sCaller = 'conditionsCaller';
			array_push($args, $caller[1]);
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