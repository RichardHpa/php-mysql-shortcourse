<?php 

namespace App\Views;

class LoginFormView extends View{

	public function render(){
		extract($this->data);
		$page = "login";
		$page_title = "Login Page";
		$page_desc = "Users Login";
		include "pages/master.inc.php";
	}

	protected function content(){
		extract($this->data);
		include "pages/login.inc.php";
	}

}