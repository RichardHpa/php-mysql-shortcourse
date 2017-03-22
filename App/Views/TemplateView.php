<?php 

namespace App\Views;

class VIEWNAMECHANGE!!!!! extends View{

	public function render(){
		extract($this->data);
		$page = "";
		$page_title = "";
		$page_desc = "";
		include "pages/master.inc.php";
	}

	protected function content(){
		extract($this->data);
		include "pages/PAGENAMECHANGE!!!.inc.php";
	}

}