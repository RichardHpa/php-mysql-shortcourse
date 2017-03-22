<?php 

namespace App\Models;

//PHP data objects
//Gives access to databases
use PDO;

abstract class DatabaseModel{

	public $data = [];
	public $errors = [];
	protected static $columns = [];

}