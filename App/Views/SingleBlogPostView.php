<?php 

namespace App\Views;

class SingleBlogPostView extends View{

	public function render(){
		extract($this->data);
		$page = "blogpost";
		$page_title = "$blogPost->title";
		$page_desc = "";
		include "pages/master.inc.php";
	}

	protected function content(){
		extract($this->data);
		include "pages/singleblogpost.inc.php";
	}

}