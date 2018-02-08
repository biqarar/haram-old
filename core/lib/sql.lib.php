<?php
class sql_lib{
	static $tables;
	private $blackList = array("index", "foreign", "unique");
	public static $connection = false;

	/**
	 * @param [object] $maker object of sql maker for make string query
	 */
	public function __construct($maker = false){
		if(!self::$tables){
			self::$tables = (object) array();
		}
		if(is_object($maker)){
			if(autoload::check("sql_cls") && method_exists("sql_cls", "config")) {
				sql_cls::config($maker);
			}
			$this->maker = $maker;
			self::$tables = (object) array();	
			$this->loadTable();
		}
	}

	/**
	 * [__call description]
	 * @param  [string] $name [virtual function name]
	 * @param  [array] $args [arguments of virtual function]
	 * @return [type]       [description]
	 */
	public function __call($name, $args){
		$syntax = "{$name}Caller";
		$string = $this->$syntax();
		if(preg_match("/^(insert|delete|update|select)$/", $name)){
			if(autoload::check("sql_cls") && method_exists("sql_cls", "call")) {
				sql_cls::call($this->maker, $name);
			}
			
			$this->groupby($string);
			$this->order($string);
			$string .= (count($this->maker->limit) > 0) ? " LIMIT ". join($this->maker->limit, ', ') : '';
			// echo "\n<pre>\n";
			// echo($string)."\n\n";
			// echo "\n</pre>\n";
			// $_SESSION['time'] = microtime(true);
			$connection = new dbconnection_lib;
			$result = $connection->query($string);
			// ilog(microtime(true) - $_SESSION['time'], $string);
			return $result;
		}else{
			return $string;
		}
	}

	public function groupby(&$string){
		$agroup = array();
		if($this->maker->groupby){
			array_push($agroup, array($this->maker->table, $this->maker->groupby));
		}
		foreach ($this->maker->join as $key => $value) {
			if($value->groupby){
				array_push($agroup, array($value->table, $value->groupby));
			}
		}
		$s = '';
		$a = array();
		foreach ($agroup as $key => $value) {
			$table = $value[0];
			foreach ($value[1] as $k => $v) {
				$groupby_ = $this->oString($table, $v);
				array_push($a, $groupby_);
			}
		}
		if(count($agroup) > 0){
			$string .= " GROUP BY ".join($a, ', ');
		}

	}
	// public function order(&$string){
	// 	$aorder = array();
	// 	if($this->maker->order){
	// 		foreach ($this->maker->order as $key => $value) {
	// 			array_push($aorder, array($this->maker->table, $value));
	// 		}
	// 	}
	// 	foreach ($this->maker->join as $key => $value) {
	// 		if($value->order){
	// 			foreach ($value->order as $k => $v) {
	// 				array_push($aorder, array($value->table, $v));
	// 			}
	// 		}
	// 	}
	// 	$array_string_order = array();
	// 	foreach ($aorder as $key => $value) {
	// 		$orderField = $this->oString($value[0], $value[1][0]);
	// 		array_push($array_string_order, "$orderField {$value[1][1]}");
	// 	}
	// 	if($aorder){
	// 		$string .= " ORDER BY ".join(', ', $array_string_order);
	// 	}
	// }
	public function order(&$string){
		$aorder = false;
		if($this->maker->order){
			$aorder = array($this->maker->table, $this->maker->order);
		}
		foreach ($this->maker->join as $key => $value) {
			if($value->order){
				$aorder = array($value->table, $value->order);
			}
		}
		if($aorder){
			if(preg_match("/^([^\s]+)(\s(ASC|DESC))?$/", $aorder[1], $order)){
				if(count($order) == 4){
					$sort = $order[3];
					$orderField = $this->oString($aorder[0], $order[1]);
					$string .= ($aorder[1]) ? " ORDER BY $orderField $sort" : '';
				}
			}
		}
	}

	/**
	 * function for make select query
	 * @return [string] string of select query
	 */
	public function selectCaller(){
		$string = "SELECT ";
		$fields = array();
		$tables = array();
		$mapField = array();
		$this->oField($this->maker, $fields, $mapField);
		$fAs = (is_array($this->maker->fields)) ? $this->maker->fields : array($this->maker->fields);
		foreach ($mapField as $key => $value) {
			if(isset($this->maker->fieldsAs) && $this->maker->fieldsAs[$value]){
				$fields[$key] .=  " AS ". $this->maker->fieldsAs[$value];
			}
		}
		array_push($tables, $this->maker->table);
		foreach ($this->maker->join as $key => $value) {
			array_push($tables, $value->table);
			$Scount = count($fields);
			$this->oField($value, $fields, $mapField);
			for ($Scount; $Scount < count($mapField); $Scount++){
				if(isset($value->fieldsAs) && $value->fieldsAs[$mapField[$Scount]]){
					$fields[$Scount] .=  " AS ". $value->fieldsAs[$mapField[$Scount]];
				}
			}
		}
		$tablse = array($this->maker->table);
		$string .= join($fields, ", ");
		$string .= " FROM ". $this->oString($this->maker->table);
		$string .= $this->join();
		if(count($this->maker->conditions) > 0){
			$string .= " WHERE".$this->condition($this->maker);
		}
		return $string;
	}


	public function insertCaller(){
		$string = "INSERT INTO ";
		$string .= "`".$this->maker->table."`";
		$keys = array_keys($this->maker->set);
		$fKeys = array_keys($this->maker->set);
		foreach ($keys as $key => $value) {
			$keys[$key] = $this->oString($this->maker->table, $value);
		}

		$values = array_values($this->maker->set);
		foreach ($values as $key => $value) {
			if($value === false || $value === null || $value == "") $value = "#NULL";
			$values[$key] = $this->oString($this->maker->table, $fKeys[$key] ,$value);
		}

		$string .= " (".join($keys, ", ").")";
		$string .= " VALUES (".join($values, ", ").")";
		return $string;
	}

	public function updateCaller(){
		$string = "UPDATE ";
		$string .= "`".$this->maker->table."`";
		$keys = array_keys($this->maker->set);
		$fKeys = array_keys($this->maker->set);
		foreach ($keys as $key => $value) {
			$keys[$key] = $this->oString($this->maker->table, $value);
		}

		$values = array_values($this->maker->set);
		foreach ($values as $key => $value) {
			$values[$key] = $keys[$key]. " = ".$this->oString($this->maker->table, $fKeys[$key] ,$value);
		}
		$string .= " SET ". join($values, ", ");
		if(count($this->maker->conditions) > 0){
			$string .= " WHERE".$this->condition($this->maker);
		}
		if(autoload::check("sql_cls") && method_exists("sql_cls", "update_log")) {
			sql_cls::update_log($this->maker ,$this->condition($this->maker) );
		}
		return $string;
	}

	public function deleteCaller(){
		$string = "DELETE FROM ";
		$string .= "`".$this->maker->table."`";
		$string .= " WHERE".$this->condition($this->maker);
		if(autoload::check("sql_cls") && method_exists("sql_cls", "trash")) {
			sql_cls::trash($this->maker ,$this->condition($this->maker));
		}
		return $string;
	}

	public function join(){
		$string = "";
		foreach ($this->maker->join as $key => $value) {
			$joinCondition = $value->joinCondition;
			$string .= " $joinCondition JOIN `".$value->table."` ON";
			$string .= $this->condition($value);
		}
		return $string;
	}

	/**
	 * [condition description]
	 * @param  [type] $maker [description]
	 * @return [type]        [description]
	 */
	public function condition($maker){
		// var_dump($maker->conditions);
		$string = "";
		foreach ($maker->conditions as $key => $value) {
			if(isset($value[0])){
				foreach ($value as $ckey => $cvalue) {
					if($ckey == 0){
						$string .= $key != 0 ? " ".strtoupper($cvalue["condition"])."(" : "(";
					}else{
						$string .= " ".strtoupper($cvalue["condition"])." ";
					}
					$string .= $this->conditionString($cvalue, $maker->table);
				}
				$string .= ")";
				/**
				 * 
				 */
			}else{
				$string .= $key != 0 ? " ".strtoupper($value["condition"])." " : " ";
				$string .= $this->conditionString($value, $maker->table);
			}
		}
		return $string;
	}
	/**
	 * [conditionString description]
	 * @param  [type] $condition [description]
	 * @param  [type] $table     [description]
	 * @return [type]            [description]
	 */
	public function conditionString($condition, $table){
		$string = "";
		if(preg_match("/^#(.*)$/", $condition['field'], $field)){
			if(strtolower($condition['operator']) == "like"){
				$op = "";
				if(preg_match("/^%(.*)$/", $condition['value'], $v)){
					$condition['value'] = $v[1];
					$op .= "0";
				}
				if(preg_match("/^(.*)%$/", $condition['value'], $v)){
					$condition['value'] = $v[1];
					$op .= "1";
				}
				$val = $this->oString($table, $field[1], $condition['value'], false);
				if(preg_match("/0/", $op)){
					if(preg_match("/^'/", $val)){
						$val = preg_replace("/^'/", "'%", $val);
					}else{
						$val = "'%$val";
					}
				}
				if(preg_match("/1/", $op)){
					if(preg_match("/'$/", $val)){
						$val = preg_replace("/'$/", "%'", $val);
					}else{
						$val = "$val%'";
					}
				}
			}else{
				$val = $this->oString($table, $field[1], $condition['value']);
			}
			
			$string .= $this->oString($table, $field[1])." {$condition['operator']} ". $val;
		}else{
			$string .= "$condition[field] {$condition['operator']} $condition[value]";
		}
		return $string;
	}

	/**
	 * set optiomize of select fields
	 * @param  [object] $maker   [sql maker object]
	 * @param  [array] $aFields [array of fields]
	 */
	public function oField($maker, &$aFields, &$map){
		$table = $maker->table;
		$fields = is_array($maker->fields) ? $maker->fields : array($maker->fields);
		if(!$fields[0] || $fields[0] == "*"){
			array_push($aFields, $this->oString($table, "*"));
			array_push($map, "*");
		}else{
			foreach ($fields as $key => $value) {
				array_push($aFields, $this->oString($table, $value));
				array_push($map, $value);
			}
		}
	}

	/**
	 * optimize sql table, fields and value
	 * @param  [string] $table [set table name]
	 * @param  [string] $field [set field name]
	 * @param  [string] $value [set value]
	 * @return [string]        [optimize of string]
	 * @example
	 * 	oSting(users)			return #users#
	 * 	oSting(users, id)		return #users.id#
	 * 	oSting(users, id, 150)	return #users.id 150#
	 */
	public function oString($table, $field = null, $value = null, $checkCondition = true){
		if($value !== null){
			$cInt = false;
			if(preg_match("/^#(.*)$/", $value, $v)){
				$value = $v[1];
				$cInt = true;
			}else{
				if(isset(self::$tables->$table->$field)){
					// var_dump(self::$tables->$table->$field->type);
					$type = self::$tables->$table->$field->type;
					$int = array("int","tinyint", "smallint","decimal");
					preg_match("/^([^@]*)@/", $type, $tp);
					if(preg_grep("/^".$tp[1]."$/", $int)){
						$cInt = true;
					}
				}
				if(isset(self::$tables->$table->$field->closure) && $checkCondition){
					$gTable = self::$tables->$table->$field->closure;
					$value = preg_replace("/^\\\#/", "#", $value);
					$v = new validator_lib(array($field, $value), $gTable->validate, 'form');
					$value = $v->compile();
					$value = (($value == '' && is_string($value)) || ($value === false))? "NULL" : $value;
					if($value == "NULL"){
						$cInt = true;
					}
				}

				if(!$cInt){
					$value = htmlentities($value, ENT_QUOTES, "UTF-8");
				}
			}
			$optimize = $cInt ? "$value" : "'$value'";
		}else{
			$optimize = "`$table`";
			if($field){
				if(preg_match("/^#/", $field)){
					$optimize = preg_replace("/^#/", "", $field);
				}else{
					$optimize .= $field ? ($field === "*") ? ".$field" : ".`$field`" : "";
				}
			}
		}
		return $optimize;

	}

	/**
	 * load ORM tables on this class
	 * change private $tables as array with $table index
	 */
	public function loadTable(){
		$tName = array($this->maker->table);
		foreach ($this->maker->join as $key => $value) {
			array_push($tName, $value->table);
		}
		foreach ($tName as $key => $value) {
			$this->saveTable($value);
		}
	}

	public function getForms($index = 0){
		$tab = $this->maker->table;
		$table = self::$tables->$tab;
		return $table;
	}

	public static function getTable($name){
		$object = new self;
		return $object->saveTable($name);
	}

	public function saveTable($name){
		if(isset(self::$tables->$name)) return self::$tables->$name;
		$sName = "\\sql\\{$name}";
		$tables = new $sName;
		$cName = "\\sql\\{$name}";
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
					$Closure = $func->getClosure(new $cName());
					$options->$key = \Closure::bind($Closure, $options);

					$options->table = $tables;
					$options->tableName = $tables;
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
					// if(get_class($closure->$key) !== 'Closure') return;
					call_user_func($closure->$key);
				}
			}
		}
		self::$tables->$name = $tables;
		return self::$tables->$name;
	}
}
?>