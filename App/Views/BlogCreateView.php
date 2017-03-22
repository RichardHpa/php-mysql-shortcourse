<?php 

namespace App\Views;

class BlogCreateView extends View{

	public function render(){
		extract($this->data);
		$page = "blog.create";
		$page_title = "Add New Blog Post";
		$page_desc = "This is where we add new blog posts";
		include "pages/master.inc.php";
	}

	protected function content(){
		extract($this->data);
		include "pages/blogcreate.inc.php";
	}

}