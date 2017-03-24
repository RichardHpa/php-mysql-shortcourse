<?php 

namespace App\Models;

//Use File Info
use finfo;
//Use InerventionImage
use Intervention\Image\ImageManagerStatic as Image;


class Blog extends DatabaseModel{
	protected static $tableName = "BlogPost";
	protected static $columns = ['id', 'title', 'description', 'image', 'timestamp'];
	protected static $validationRules = [
										"title" => "minlength:1,maxlength:100",
										"description" => "minlength:10"

	];

	public function saveImage($filename){
		//create a new instance of finfo
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($filename);

		//List of acceptable file types
		$extensions = [
			'image/jpg' => '.jpg',
			'image/jpeg' => '.jpeg',
			'image/png' => '.png',
			'image/gif' => '.gif'
		];

		//If an extention is set and is in the valid extensions then that is ok
		//If not make extension .jpg
		if(isset($extensions[$mime])){
			$extension = $extensions[$mime];
		} else {
			$extension = '.jpg';
		}

		//Give the image a unique name
		$newFileName = uniqid() . $extension;

		//On mac, make sure you set all folders that will be getting the images to read and write permissions

		//Point to the image folder
		//Check to see if Orginals folder exsists
		//If not create one
		$folder = "./images/originals";
		if(! is_dir($folder)){
			mkdir($folder, 0777, true);
		}
		//Move the image to that folder
		$destination = $folder . "/" . $newFileName;
		move_uploaded_file($filename, $destination);


		//Moves the first image then we can manipulate it again

		//If Thumbnail folder doesnt exsist
		if(! is_dir("./images/thumbnails")){
			mkdir("./images/thumbnails/", 0777, true);
		}


		//Make an instance of Image Intervention
		$img = Image::make($destination);
		$img->resize(null, 300, function($constraint){
			$constraint->aspectRatio();
		});
		$img->save("./images/thumbnails/" . $newFileName);

		//Save the filename in database
		$this->image = $newFileName;


	}
}