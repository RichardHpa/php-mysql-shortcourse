<?php

namespace App\Controllers;

//Tell the controller which view it wants to open
//YOu need to do this whenever you go to a new view
use App\Views\HomeView;

//Tell the controller whcih view it wants to open
//You need to do this whenever you go to a new view within the functions
// use App\Views\HomeView;

//This class name needs to be the one mentioned in the routes
class HomeController extends Controller{

	//This function name must match the one in the routes
	//Public functions, are functions that are visible to all classes.
	public function show(){

		//Tells PHP which view you want to open
		$view = new HomeView();
		//Tells the home view to run the render function
		$view->render();
	}
}