<?php 

namespace App\Models;

//PHP data objects
//Gives access to databases
use PDO;
use App\Models\Exceptions\ModelNotFoundException;

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
					case 'email':
						if(! filter_var($this->$column, FILTER_VALIDATE_EMAIL)){
							$valid = false;
							$this->errors[$column] = "Must be a valid Email";
						}
						break;
					case 'unique':

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
			//Hash the password
			if($column === 'password'){
				$this->$column = password_hash($this->$column, PASSWORD_DEFAULT);
			}
			$statement->bindValue(":" . $column , $this->$column);
		}

		//Run the query
		$statement->execute();

		//Get the id of the query which was just added
		$this->id = $db->lastInsertID();
	}

	//FInd 1 individual blog post
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

		//If there is not a row in the database with that id
		if(! $record){
			throw new ModelNotFoundException();
		}

		//put the record into the data variable
		$this->data = $record;
	}

	//Find all of the blog posts
	public static function all(){
		//Create array to put all the data into
		$blogs = [];

		//Connect to the database
		$db = static::getDatabaseConnection();

		//Write the query
		$query = "SELECT ".implode("," , static::$columns)." FROM ".static::$tableName;

		//prepare the query
		$statement = $db->prepare($query);
		$statement->execute();

		// while the query is running, put each of the rows into the blogs array
		while($record = $statement->fetch(PDO::FETCH_ASSOC)){
			$blog = new static();
			$blog->data = $record;
			array_push($blogs, $blog);
		}

		return $blogs;
	}

	//Count how many entries there are in the database
	public static function count(){
		//Connect to the database
		$db = static::getDatabaseConnection();
		$query = "SELECT count(id) FROM " . static::$tableName;
		$statement = $db->prepare($query);
		$statement->execute();
		$result = $statement->fetchColumn(); 
		return $result;
	}

	//Update the row in the database
	public function updateDatabase(){
		$db = static::getDatabaseConnection();
		//Get all of the columns in the database table
		$columns = static::$columns;
		//Because we dont want the ID to change, remove the ID from the database
		unset($columns[array_search('id', $columns)]);
		//If we had a timecreated and timeupdated column, we would unset the timecreated column here

		//Write an Update Query
		$query = "UPDATE " . static::$tableName . " SET ";
		$updatecols = [];
		foreach ($columns as $column){
			array_push($updatecols, $column . "=:" . $column);
		}
		$query .= implode(",", $updatecols);
		$query .= " WHERE id =:id";
		$statement = $db->prepare($query);

		foreach(static::$columns as $column){
			//Hash the password
			if($column === 'password'){
				$this->$column = password_hash($this->$column, PASSWORD_DEFAULT);
			}
			$statement->bindValue(":".$column, $this->$column);
		}
		$statement->execute();
	}

	public static function DatabaseRemove($id){
		$db = static::getDatabaseConnection();
		//write the delete query
		$query = "DELETE FROM " . static::$tableName . " WHERE id = :id";
		$statement = $db->prepare($query);
		$statement->bindValue(':id', $id);
		$statement->execute();
	}

	public static function findBy($column, $value){
		$db = static::getDatabaseConnection();
		$query = "SELECT ".implode(",", static::$columns)." FROM ".static::$tableName." WHERE ".$column." = :value";
		$statement = $db->prepare($query);
		$statement->bindValue(':value', $value);
		$statement->execute();

		$record = $statement->fetch(PDO::FETCH_ASSOC);

		if(! $record){
			throw new ModelNotFoundException();
		}

		$obj = new static;
		$obj->data = $record;
		return $obj;
	}














}