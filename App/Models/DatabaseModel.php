<?php 

namespace App\Models;

//PHP data objects
//Gives access to databases
use PDO;

abstract class DatabaseModel{

	public $data = [];
	public $errors = [];
	protected static $columns = [];
	private static $db;

	//craete the Object and whatis needed to construct it
	public function __construct($input= null){

		//If in the model there are columns
		if(static::$columns){
			foreach(static::$columns as $column){
				$this->$column = null;
				$this->errors[$column] = null;
			}
		}

		//Find to see if there is database entry
		if(is_integer($input) && $input > 0){
			$this->find($input);
		}

		//If there is an input in the array then process this function
		if(is_array($input)){
			//If input is an array, load that data from the array
			$this->processArray($input);
		}
	}

	//Create an function which connects to the database
	protected static function getDatabaseConnection(){
		//Self refers to if the current class is being used or not
		if(! self::$db){
			$dsn = 'mysql:host=localhost;dbname=Blog;charset=utf8';
			self::$db = new PDO($dsn, 'root', '');

			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
		return self::$db;
	}


	//Process all of the columns and add the relevnat data to it
	public function processArray($input){
		foreach (static::$columns as $column) {
			if(isset($input[$column])){
				$this->$column = $input[$column];
 			}
		}
	}

	public function __get($name){
		if( in_array($name, static::$columns)){
			return $this->data[$name];
		}
	}

	public function __set($name, $value){
		if (! in_array($name, static::$columns)) {
			
		}
		$this->data[$name] = $value;
	}

	//Function to validate all the columns
	public function isValid(){
		//Initally create a valid variable and set it to true
		$valid = true;
		//Loop through all of the validation rules in the model
		foreach (static::$validationRules as $column => $rules) {
			//At the beginning there are no errors in any column
			$this->errors[$column] = null;
			//sperate all the different rules
			$rules = explode(",", $rules);
			//Loop over each of the different rules for each column
			foreach($rules as $rule){
				//Seperate the value from the rule
				if(strstr($rule, ":")){
					$rule = explode(":", $rule);
					//rule gets turned into an array
					//Put the value into a value variable
					$value = $rule[1];
					//Put the rule back into the rule variable
					$rule = $rule[0];
				}
				//use a switch to go over all of the rules
				switch ($rule) {
					//These case's must match the ones in the model
					//Check min length
					case 'minlength':
						if(strlen($this->$column) < $value){
							$valid = false;
							$this->errors[$column] = "To Short - Must be at least $value characters long";
						}
						break;
					// Check max length
					case 'maxlength':
						if(strlen($this->$column) > $value){
							$valid = false;
							$this->error[$column] = "To Long - Must be no more than $value characters long";
						}
						break;
				}
			}
		}
		return $valid;
	}	

	public function save(){
		//Get the connection to the database
		$db = static::getDatabaseConnection();
		//Find the columns from the model
		$columns = static::$columns;

		//because ID is AI we dont want to put a value in it
		unset($columns[array_search('id', $columns)]);
		//Same with timestamp
		unset($columns[array_search('timestamp', $columns)]);

		//Create an insert query which gets linked to the database
		$query = "INSERT INTO " . static::$tableName . " (". implode(",", $columns) . ") VALUES (";

		//create a variable called insesrtcols. This is where we put the values
		$insertcols = [];
		//For each of the columns in the columns array, add that column into the insert cols array, and seperate it with a :
		foreach ($columns as $column) {
			array_push($insertcols, ":" . $column);
		}
		//turn the insertcols array into 1 string and put a , between each entry
		$query .= implode(",", $insertcols);
		//close the query
		$query .= ")";

		//Prepare the query
		$statement = $db->prepare($query);

		//Foreach of the columns run this function
		foreach ($columns as $column) {
			//Attach the value to each of the columns
			$statement->bindValue(":" . $column , $this->$column);
		}

		//Run the query
		$statement->execute();

		//Get the id of the query which was just added
		$this->id = $db->lastInsertID();
	}

	public function find($id){
		$db = static::getDatabaseConnection();
		//Create a select query
		$query = "SELECT " . implode("," , static::$columns)." FROM " . static::$tableName . " WHERE id = :id";
		//prepare the query
		$statement = $db->prepare($query);
		//bind the column id with :id
		$statement->bindValue(":id", $id);
		//Run the query
		$statement->execute();

		//Put the associated row into a variable
		$record = $statement->fetch(PDO::FETCH_ASSOC);

		//put the record into the data variable
		$this->data = $record;
	}














}