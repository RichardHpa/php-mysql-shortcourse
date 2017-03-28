<?php 
//This file contains all of our different pages we have in our site

//We will mention classes which are in each seperate controller
//This will help the PHP know where these classes are so we dont have to write them all in here
namespace App\Controllers;

//Call the Model not found exception
use App\Models\Exceptions\ModelNotFoundException;

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
		case 'blog.post':
			$controller = new BlogController();
			$controller->SingleBlogPost();
			break;
		case 'blog.edit':
			$controller = new BlogController();
			$controller->edit();
			break;
		case 'blog.update':
			$controller = new BlogController();
			$controller->update();
			break;
		case 'blog.remove':
			$controller = new BlogController();
			$controller->remove();
			break;
		case 'register':
			$controller = new AuthenticationController();
			$controller->register();
			break;
		case 'auth.store':
			$controller = new AuthenticationController();
			$controller->store();
			break;
		case 'login':
			$controller = new AuthenticationController();
			$controller->login();
			break;
		case 'auth.attempt':
			$controller = new AuthenticationController();
			$controller->attempt();
			break;
		case 'logout':
			$controller = new AuthenticationController();
			$controller->logout();
			break;
			
		default:
			throw new ModelNotFoundException();
			break;
	}
} catch (ModelNotFoundException $e) {
	$controller = new ErrorController();
	$controller->error404();
}