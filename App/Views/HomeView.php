<?php 

namespace App\Views;

class HomeView extends View{

	public function render(){
		//extract all of the data from the controller
		extract($this->data);
		//What page is it
		$page = "home";
		//What is the page title
		$page_title = "home";
		//What is the description of the page
		$page_desc = "This is the Home Page";
		//include the master page
		include "pages/master.inc.php";
	}

}