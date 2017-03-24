<?php

namespace App\Controllers;

use App\Views\BlogView;
use App\Views\BlogCreateView;
use App\Views\SingleBlogPostView;

use App\Models\Blog;

class BlogController extends Controller{

	public function show(){
		$view = new BlogView();
		$view->render();
	}

	public function create(){
		//Run the get form data function and put result into variable
		//Check to see if there is any data we need to fill the form with
		$blogPost = $this->getFormData();

		$view = new BlogCreateView(['blogPost' => $blogPost]);
		$view->render();
	}

	public function store(){
		// var_dump($_POST);
		//create a new instance of the Model
		$blogPost = new Blog($_POST);

		//Validate the form
		if (! $blogPost->isValid()) {
			//return to the previous page
			$_SESSION['blog.create'] = $blogPost;
			header("Location:.\?page=blog.create");
		}

		//Check to see if image uploaded is ok
		//If the image is uploaded then go to the saveImage function in the Modal
		// if($_FILES['image']['error'] === UPLOAD_ERR_OK){
		// 	$blogPost->saveImage($_FILES['image']['tmp_name']);
		// }

		//Run the save function in the database controller
		$blogPost->save();
		//Go to that blog post
		header("Location:./?page=blog.post&id=" . $blogPost->id);
		
	}

	public function SingleBlogPost(){
		//Get the blog post which has the relevant ID
		$blogPost = new Blog((int)$_GET['id']);
		$view = new SingleBlogPostView(['blogPost' => $blogPost]);
		$view->render();
	} 


	public function getFormData($id = null){
		//If there is a session called blog.create
		if(isset($_SESSION['blog.create'])){
			$blogpost = $_SESSION['blog.create'];
			//remove session called blog.create
			unset($_SESSION['blog.create']);
		} else{
			$blogpost = new Blog((int)$id);
		}
		return $blogpost;
	}
	
}