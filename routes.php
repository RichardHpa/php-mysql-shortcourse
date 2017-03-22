<?php 
//This file contains all of our different pages we have in our site

//We will mention classes which are in each seperate controller
//This will help the PHP know where these classes are so we dont have to write them all in here
namespace App\Controllers;

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

//Try all of the possible outcomes
//If no match go to catch
try {
	//switch to whcih evey case matches our variable
	switch ($page) {
		case 'home':
			$controller = new HomeController();
			$controller->show();
			break;
		case 'blog':
			$controller = new BlogController();
			$controller->show();
			break;
		case 'blog.create':
			$controller = new BlogController();
			$controller->create();
			break;
		case 'blog.store':
			$controller = new BlogController();
			$controller->store();
			break;
			
		default:
			echo "There isnt any page matching your request";
			break;
	}
} catch (Exception $e) {
	echo "There is an error in your routes";
}