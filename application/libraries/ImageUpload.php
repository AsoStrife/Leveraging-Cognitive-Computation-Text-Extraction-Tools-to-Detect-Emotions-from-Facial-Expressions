<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ImageUpload{

	public function __construct() {
		$this->ci = get_instance();
		$this->ci->load->model('Uploaded_Images_model');

		$this->mainDirectory = './img/uploads/';
	}

	public function add($filename){

		$path = $this->mainDirectory . $filename;

		if(!$filename)
			return false;

		$analyze = $this->ci->cognitiveservices->analyzeImage(base_url() . $path);
				
		if(!$analyze) // General Error
			return false; 

		if(!isset($analyze->faces)) // the image doesn't contain a valid face, usless to continue
			return false; 
		
		$analyze = json_encode($analyze);

		$emotion = $this->ci->cognitiveservices->emotionImage(base_url() . $path);

		// If there's no emotion, usless to continue
		if(empty($emotion))
			return false;

		$emotion = json_encode($emotion);

		$id = $this->ci->Uploaded_Images_model->addImage($filename, $path, $analyze, $emotion);

		if(!$id)
			return false;

		$tags = $this->ci->Uploaded_Images_model->getImageTags($id);

		$sparkResponse = json_decode($this->ci->sparkservice->analyzeImage($id, $tags));

		if($this->ci->Uploaded_Images_model->updateImageOurClass($id, $sparkResponse->class))
			return true;
		else 
			return false;
	}

	public function getImages(){
		return $this->ci->Uploaded_Images_model->getAllImages();
	}

}