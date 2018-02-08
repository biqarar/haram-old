<?php
class sql_lib{
	public $tables = array();
	private $blackList = array("index", "foreign", "unique");
	public static $connection = false;

	public function __call($name, $args){
		$this->loadTable();
		$syntax = "{$name}Caller";
		
		$string = $this->$syntax();
		
		if(preg_match("/^(insert|delete|update|select)$/", $name)){
			$string .= ($this->groupby) ? " GROUP BY `$this->groupby`" : '';
			preg_match("/^([^\s]+)(\s(ASC|DESC))?$/", $this->order, $order);
			if(count($order) == 4){
				$sort = $order[3];
				$orderField = $order[1];
				$string .= ($this->order) ? " ORDER BY `$orderField` $sort" : '';
			}
			$string .= (count($this->limit) > 0) ? " LIMIT ". join($this->limit, ', ') : '';
			$connection = new dbconnection_lib;
			$result = $connection->query($string);
			return $result;
		}else{
			return $string;
		}
	}

	public function showCaller(){
		$table = $this->tables[$this->table];
		$array = array();
		foreach ($table as $key => $value) {
			if(!preg_grep("/^$key$/", $this->blackList)){
				$array[$key] = isset($value->label) ? $value->label : $key;
			}
		}
	}

	public function updateCaller(){
		$condition = $this->makeConditions();
		$validate = $this->validate($this->set);
		$fields = $this->quotFields($validate);
		$array = array();
		foreach ($fields as $key => $value) {
			$array[] = "`$key` = $value";
		}
		$update = join($array, ', ');
		$string = "UPDATE `$this->table` SET $update $condition";
		return $string;
	}

	public function insertCaller(){
		$validate = $this->validate($this->set);
		$fields = $this->quotFields($validate);
		$name = array_keys($fields);
		foreach ($name as $key => $value) {
			$name[$key] = "`$value`";
		}
		$values = array_values($fields);
		$sName = join($name, ', ');
		$sValues = join($values, ', ');
		$string = "INSERT INTO `$this->table` ($sName) VALUES ($sValues)";
		return $string;

	}

	public function selectCaller(){
		$condition = count($this->conditions)> 0 ? $this->makeConditions() : '';
		$table = $this->tables[$this->table];
		$string = "SELECT ";
		if(is_array($this->fields)){
			foreach ($this->sFields as $key => $value) {
				$sFields[$key] = "`$value`";
			}
			$sFields = join($sFields, ', ');
		}elseif(!empty($this->fields) && $this->fields !== '*'){
			$sFields = "`{$this->fields}`";
		}else{
			$sFields = '*';
		}
		$sTables = " FROM `$this->table`";
		$sForeigns = '';
		if(count($this->foreign) > 0){
			foreach ($this->foreign as $key => $value) {
				if(isset($table->foreign[$value])){
					list($foreignTable, $foreignField) = $this->atSplit($table->foreign[$value]);
					$Fsql = $this->loadTable(true, $foreignTable);
					$Ffld = array();
					foreach ($Fsql as $Fkey => $Fvalue) {
						if(!preg_grep("/^$Fkey$/", $this->blackList)){
							$Ffld[] = "$foreignTable.$Fkey AS ".ucfirst($foreignTable)."_$Fkey";
						}
					}
					$sFields .= ", ".join($Ffld, ', ');
					$sForeigns.= " INNER JOIN $foreignTable ON {$this->table}.$value = $foreignTable.$foreignField";
				}
			}
		}else{
		}

		$string .= $sFields;
		$string .= $sTables;
		$string .= $sForeigns;
		$string .= " $condition";
		return $string;
	}

	public function deleteCaller(){
		$condition = $this->makeConditions();
		$string = "DELETE FROM `$this->table` $condition";
		return $string;
	}

	/**
	 * load ORM tables on this class
	 * change private $tables as array with $table index
	 */
	public function loadTable($return = false, $tName = false){
		$name = !$tName ? $this->table : $tName;
		$sName = "\\sql\\{$name}";
		$tables = new $sName;
		$object = array();
		
		foreach ($tables as $key => $value) {
			if(!preg_grep("/^$key$/", $this->blackList)){
				$keys = array_keys($value);
				$values = array_values($value);
				$array = array();
				foreach ($keys as $k => $v) {
					if(is_int($v)){
						$keys[$k] = $values[$k];
						$values[$k] = true;
					}
				}
				if(method_exists($tables, $key)){
					$options = new dbTableOptions_lib;
					$func = new ReflectionMethod("\\sql\\{$name}", $key);
					$cName = "\\sql\\{$name}";
					$Closure = $func->getClosure(new $cName());
					$options->$key = \Closure::bind($Closure, $options);
					$options->table = $tables;
					$options->tableName = $this->table;
					$options->fieldName = $key;
					$values[] = $options;
					$keys[] = 'closure';
				}
				$array = array_combine($keys, $values);
				$tables->$key = (object) $array;
			}
		}
		foreach ($tables as $key => $value) {
			if(method_exists($tables, $key)){
				if(isset($tables->{$key}->closure)){
					$closure = $tables->{$key}->closure;
					call_user_func($closure->$key);
				}
			}
		}
		if($return){
			return $tables;
		}else{
			$this->tables[$name] = $tables;
		}
	}

	/**
	 * split text from symbols
	 * exp: type@users.id => array(type, users, id)
	 * @param  text $string orginal ORM text string
	 * @return array         array(before at, before dot, after dot)
	 */
	public function atSplit($string){
		preg_match("/^(.*)@([^\!]*)(\!(.*))?$/", $string, $split);
		array_shift($split);
		$return = array();
		$return[0] = isset($split[0]) ? $split[0] : false;
		$return[1] = isset($split[1]) ? $split[1] : false;
		$return[2] = isset($split[3]) ? $split[3] : false;
		return $return;
	}

	public function getForms($index = 0){
		$table = $this->loadTable(true);
		return $table;
	}

	/**
	 * insert "'" before and after any field if not int type
	 * @param  [field Object] $fields [description]
	 * @return [type]         [description]
	 */
	public function quotFields($fields){
		$array = array();
		$int = array("int");
		$fieldTable = $this->tables[$this->table];
		foreach ($fields as $key => $value) {
			if(!isset($fieldTable->$key)){
				#error fields not found
			}elseif(isset($fieldTable->{$key}->type)){
				list($type) = $this->atSplit($fieldTable->{$key}->type);
				if(array_search($type, $int) === false){
					$value = "'$value'";
				}else{
					$value = empty($value) ? "NULL" :"'$value'";
				}
			}
			$array[$key] = $value;
		}
		return $array;
	}

	/**
	 * validation enter fields
	 * @param  [type] $fields [description]
	 * @return [type]         [description]
	 */
	public function validate($fields){
		$array = array();
		$tables = $this->tables[$this->table];
		foreach ($fields as $key => $value) {
			if(isset($tables->{$key}->closure)){
				$closure = $tables->{$key}->closure;
				$v = new validator_lib(array($key, $value), $closure->validate, 'form');
				$value = $v->compile();
			}
			$array[$key] = htmlentities($value, ENT_QUOTES, "UTF-8");
		}
		return $array;
	}

	public final function stringCondition($field, $value, $operator, $index){
		$string = null;
		preg_match("/^(or|and|if|>|<|=)\s?(.*)$/", $operator, $sOperator);
		if($index >= 1){
			if(isset($sOperator[2]) && !empty($sOperator[2])){
				$string .= " $sOperator[2] ";
			}else{
				$string .= " $sOperator[1] ";
			}
		}
		if(isset($sOperator[2]) && !empty($sOperator[2])){
			$symbol = $sOperator[1];
		}else{
			$symbol = (preg_match("/^(like|orlike|andlike)$/", $operator))? "LIKE" : '=';
		}
		$string .= "`$field` $symbol $value";
		return $string;
	}

	public final function makeConditions(){
		$array = array();
		foreach ($this->conditions as $key => $value) {
			if(isset($value[0])){
				foreach ($value as $k => $v) {
					$array[$v['field']] = $v['condition'];
				}
			}else{
				$array[$value['field']] = $value['condition'];
			}
		}
		$validate = $this->validate($array);
		$fields = $this->quotFields($validate);
		$string = "WHERE ";
		foreach ($this->conditions as $key => $value) {
			if(isset($value[0])){
				if($key >= 1){
					preg_match("/^(or|and|if).*$/", $value[0]['operator'], $sOperator);
					$string .= " $sOperator[1] (";
				}else{
					$string .= "(";
				}
				foreach ($value as $k => $v) {
					$qValue = $fields[$v['field']];
					$string .= $this->stringCondition($v['field'], $qValue, $v['operator'], $k);
				}
				$string .= ')';
			}else{
				$valueField = $value['field'];
				$qValue = $fields[$valueField];
				$string .= $this->stringCondition($value['field'], $qValue, $value['operator'], $key);
			}
		}
		// if(class_exists("sql_cls")){
		// 	$c = new sql_cls($this, $string);
		// 	call_user_func_array(array("sql_cls","config"), array($this, $string));
		// }

		
		return $string;
	}
}
?>