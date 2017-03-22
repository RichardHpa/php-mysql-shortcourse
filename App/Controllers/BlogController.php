<?php

namespace App\Controllers;

use App\Views\BlogView;
use App\Views\BlogCreateView;

class BlogController extends Controller{

	public function show(){
		$view = new BlogView();
		$view->render();
	}

	public function create(){
		$view = new BlogCreateView();
		$view->render();
	}

	public function store(){
		
	}
	
}