<?php 

//Set the default timezone
date_default_timezone_set("Pacific/Auckland");

//Tell the project we want to see all the errors
error_reporting(E_ALL);

//Autoload does all of the grunt work for loading files, makes it faster
require 'vendor/autoload.php';

//Need to turn on the session variable
session_start();
session_regenerate_id(true);

$auth = new App\Services\AuthService();

App\Views\View::registerAuthService($auth);
App\Controllers\Controller::registerAuthService($auth);

//Routes tell us which page/function is being loaded and what to load
require "routes.php";

//Test the connection
//Uncomment the line bellow when you want to test on another computer
// require "databasetest.php";

var_dump($auth);

