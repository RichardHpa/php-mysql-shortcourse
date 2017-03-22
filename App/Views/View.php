<?php 
//Because we will be using alot of views, we want to keep data in here which might be in every view

namespace App\Views;

abstract class View{

	//Protected variables or classes, are visible only to the class which they belong to, and any subclasses
	protected $data;

	//This is saying that they need something to construct the function
	//ie, you need wood to construct a house
	public function __construct($data=[]){
		$this->data = $data;
	}

	abstract public function render();
}
