<?php 

namespace App\Services;
use App\Models\User;

class AuthService{

	private static $currentUser;

	public function __construct(){

		if(! isset($_SESSION['AuthenticationService'])){
			$this->resetSession();
		}

		if(isset($_SESSION['AuthService']['currentUser'])){
			try {
				//If the user is in the database
				static::$currentUser = User::findBy('email', $_SESSION['AuthService']['currentUser']->email);
			} catch (ModelNotFoundException $e) {
				//user has signed out
				$this->resetSession();
			}
		}
	}

	public function resetSession(){
		$_SESSION['AuthService'] = [
								'currentUser' => null
		];
	}

	public function attempt($email, $password){
		//get the user with the matching email
		try {
			$user = User::findBy('email', $email);	
		} catch (Exception $e) {
			return false;
		}

		//compare passwords
		if($this->comparePassword($password, $user)){
			$this->loginUser($user);
			return true;
		}
		echo "Password error";
		return false;
	}

	private function comparePassword($password, User $user){
		if(password_verify($password, $user->password)){
			if(password_needs_rehash($user->password, PASSWORD_DEFAULT)){
				$user->password = password_hash($password, PASSWORD_DEFAULT);
				$user->store();
			}
			return true;
		}
		return false;
	}

	public function check(){
		return(static::$currentUser ? true : false);
	}

	public function user(){
		return static::$currentUser;
	}

	public function logout(){
		unset($_SESSION['AuthService']);
		static::$currentUser = null;
	}

	public function loginUser(User $user){
		$_SESSION['AuthService']['currentUser'] = $user;
		static::$currentUser = $user;
	}

}