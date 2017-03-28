<?php 


namespace App\Controllers;

//In this class we put all the information whcih might be repeated a few times throughout the project
class Controller{
	protected static $auth;

	public static function registerAuthService($auth){
		self::$auth = $auth;
	}
}