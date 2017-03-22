<?php 

namespace App\Views;

class BlogView extends View{

	public function render(){
		extract($this->data);
		$page = "blog";
		$page_title = "Blog";
		$page_desc = "This is the blog page";
		include "pages/master.inc.php";
	}

	protected function content(){
		extract($this->data);
		include "pages/blog.inc.php";
	}

}