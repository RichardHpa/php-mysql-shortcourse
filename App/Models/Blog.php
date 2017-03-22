<?php 

namespace App\Models;

class Blog extends DatabaseModel{
	protected static $tableName = "blog";
	protected static $columns = ['id', 'title', 'description', 'imageName']
	protected static $validationRules = [
										"title" => "minlength:1,maxlength:100",
										"description" => "minlength:10"

	];
}