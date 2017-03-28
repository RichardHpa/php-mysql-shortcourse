<?php 

namespace App\Views;

class Error404View extends View{

	public function render(){
		extract($this->data);
		$page = "error404";
		$page_title = "error 404";
		$page_desc = "404 Page Not found";
		include "pages/master.inc.php";
	}

	protected function content(){
		extract($this->data);
		include "pages/error404.inc.php";
	}

}