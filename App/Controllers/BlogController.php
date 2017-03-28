<?php

namespace App\Controllers;

use App\Views\BlogView;
use App\Views\BlogCreateView;
use App\Views\SingleBlogPostView;

use App\Models\Blog;

class BlogController extends Controller{

	public function show(){
		$blogs = Blog::all();
		$blogCount = Blog::count();
		//Only compact when there is more than 1 variable going into the view
		//Otherwise use the one in the create function
		$view = new BlogView(compact('blogs', 'blogCount'));
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
		if($_FILES['image']['error'] === UPLOAD_ERR_OK){
			$blogPost->saveImage($_FILES['image']['tmp_name']);
		}

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

	public function edit(){
		$blogPost = $this->getFormData($_GET['id']);
		$view = new BlogCreateView(['blogPost' => $blogPost]);
		$view->render();
	}

	public function update(){
		$blogPost = new Blog((int)$_GET['id']);

		$blogPost->processArray($_POST);

		if(! $blogPost->isValid()){
			$_SESSION['blog.create'] = $blogPost;
			header("Location: .\?page=blog.edit&id=".$_GET['id']);
			exit();
		}

		if($_FILES['image']['error'] === UPLOAD_ERR_OK){
			//Remove the old images if a new image is uploaded
			unlink("./images/originals/$blogPost->image");
			unlink("./images/thumbnails/$blogPost->image");
			$blogPost->saveImage($_FILES['image']['tmp_name']);
		} else if(isset($_POST['removeImage']) && ($_POST['removeImage'] === "true")){
			//If someone hasn't uploaded a new image but has pressed the remove image button
			$blogPost->image = null;
			unlink("./images/originals/$blogPost->image");
			unlink("./images/thumbnails/$blogPost->image");
		}

		$blogPost->updateDatabase();
		header("Location:.\page=blog.post&id=" . $blogPost->id);
	}

	public function remove(){
		$blogPost = new Blog((int)$_POST['id']);
		if(isset($BlogPost->image)){
			unlink("./images/originals/$blogPost->image");
			unlink("./images/thumbnails/$blogPost->image");
		}
		Blog::DatabaseRemove($_POST['id']);
		header("Location:./?page=blog");
	}


	public function getFormData($id = null){
		//If there is a session called blog.create
		if(isset($_SESSION['blog.create'])){
			$blogPost = $_SESSION['blog.create'];
			//remove session called blog.create
			unset($_SESSION['blog.create']);
		} else{
			$blogPost = new Blog((int)$id);
		}
		return $blogPost;
	}
	
}