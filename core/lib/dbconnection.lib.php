<?php
class dbconnection_lib{
	

	public $result					= false;
	public  static $resum_on_error	= false;
	public static  $first 			= true;

	public $status					= true;
	public $string					= false;
	public $error					= false;
	public $fieldNames				= array();
	public $oFieldNames				= array();
	public static $connection		= false;
	public static $dbConnection		= array();
	public static $db_name_selected	= false;


	public function __construct(){
		$cls = debug_backtrace();
		if(isset($cls[1]['class']) && $cls[1]['class'] == 'sql_lib'){
			$this->cls = $cls[1]['object'];
		}
		$db_name = self::$db_name_selected != false ? self::$db_name_selected : db_name;
		if(!isset(self::$dbConnection[$db_name])){
			self::$connection = new mysqli(db_host, db_user, db_password, $db_name);
			if(self::$connection->connect_errno == 0 ){
				self::$connection->set_charset(db_charset);
				self::$dbConnection[$db_name] = self::$connection;
			}else{
				$this->error(self::$connection->connect_error, self::$connection->connect_errno);
			}
		}
	}
	public function query($string){
		$patterns = array(
			// '/ة/',
			'/إ/',
			'/أ/',
			'/ي/',
			// '/ئ/',
			// '/ؤ/',
			'/ك/',

			'/۰/',
			'/۱/',
			'/۲/',
			'/۳/',
			'/۴/',
			'/۵/',
			'/۶/',
			'/۷/',
			'/۸/',
			'/۹/'
			);
		$replacements = array(
			// 'ه',
			'ا',
			'ا',
			'ی',
			// 'ی',
			// 'و',
			'ک',

			'0',
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9'
			);
		$string = preg_replace($patterns, $replacements, $string);
		// var_dump($string);
		// var_dump(debug_backtrace());
		// ilog($string);
		if(debug_lib::$status || self::$resum_on_error){
			
			$this->string = $string;

			if(self::$first){
				$this->result = self::$connection->query("SET sql_mode = '' ");
				$this->result = self::$connection->query("SET @n := 0 ");
				self::$first = false;
			}
			
			$this->result = self::$connection->query($string);
			
			if (self::$connection->error) {
				$this->status = false;
				// ilog("error : "  . self::$connection->error . " no: " . self::$connection->errno);
				$this->error(self::$connection->error, self::$connection->errno);

			}
		}else if(!debug_lib::$status){
			$this->status = false;
		}
		$this->string = $string;
		return $this;
	}


	
	public function error($error = null, $errno = null){

		$reg = new dberror_lib();
		$f = "$errno";
		$aError = $reg->$f($error);
		$this->error = $aError;
		if(isset($this->cls) && $this->error && isset($this->cls->table) &&isset($this->cls->tables[$this->cls->table])){
			$table = $this->cls->tables[$this->cls->table];
			$fieldName = $this->error['fieldname'];
			$errorName = isset($this->error['errorName']) ? $this->error['errorName'] : $this->error['errno'];
			if($table->{$fieldName}->closure->validate){
				$error = isset($table->{$fieldName}->closure->validate->sql->{$errorName}) ? $table->{$fieldName}->closure->validate->sql->{$errorName} : false;
				debug_lib::fatal($error, $errorName, 'sql');
			}
		}else{
			debug_lib::fatal($error, $errno, 'sql');
		}
		return $aError;
	}

	/*
	* @return: mysqli_result
	*/
	public function result() {

		if($this->status){

			return $this->result;

		}else{

			return false;
		}
	}

	/*
	* @return: (array) list of fields 
	*/
	public function fieldNames(){

		if(!$this->fieldNames) $this->_return();

		return $this->fieldNames;
	
	}

	public function _return($type = false) {		

		if ($this->result !== null){

			if(method_exists($this->result, "fetch_fields")){

				$fields = $this->result->fetch_fields();

				$aFields = array();
				
				foreach ($fields as $key => $value){

					if(array_search($value->name, $aFields) === false){

						$this->oFieldNames[] = $value;
						$aFields[$key] = $value->name;
					}
				}

				$this->fieldNames = $aFields;		

				$x = false;
				
				if($type) $x = $this->result->fetch_array();
				
				if($x){

					$record = array();
					
					foreach ($aFields as $key => $value){
						
						$record[$value] = html_entity_decode($x[$key], ENT_QUOTES | ENT_HTML5, "UTF-8");
					}

					$return = false;

					switch ($type) {
						case 'assoc':
							$return = $record;
							break;
						case 'object' :
							$return =  (object) $record;
							break;
						case 'array' :
							$return = $x;
							break;
					}

					return $return;
				}
			}

		}
		return false;

	}

	/*
	* @return: (array) field list for ever filds one araay
	*/
	public function oFieldNames(){

		if(!$this->oFieldNames){
		
			$this->fieldNames();	
		}
		
		return $this->oFieldNames;
	}

	public function fetch_array($field = false){

		$return = $this->_return("array"); 
		
		return ($field) ? $return[$field] : $return; 
	}

	public function allArray($field = false){
		
		$all = array();

		while ($x = $this->fetch_array($field)){
			$all[] = $x;
		}

		return $all;
	}

	public function assoc($field = false){

		$return = $this->_return('assoc'); 
		return ($field) ? $return[$field] : $return; 
	}

	public function allAssoc($field = false){
		
		$all = array();

		while ($x = $this->assoc($field)){
			$all[] = $x;
		}

		return $all;
	}

	public function object($field = false) {

		$return = $this->_return('object');

		if($field) {
			$return = @$return->$field;
		}

		return ($return) ? (is_array($return)) ? (object) $return : $return  : false;

	}

	public function allObject($field = false) {
		$all = array();
		while ($x = $this->object($field)) {
			$all[] = $x;
		}
		return  $all;
	}


	public function string(){

		return $this->string;
	}

	public function num(){

		if ($this->status){
			
			if(!$this->result){
				return 0;
			}

			return $this->result->num_rows;

		}else{

			return false;
		}
	}

	public function LAST_INSERT_ID(){

		if ($this->status){

			return self::$connection->insert_id;
		}else{

			return false;
		}
	}	

}
?>