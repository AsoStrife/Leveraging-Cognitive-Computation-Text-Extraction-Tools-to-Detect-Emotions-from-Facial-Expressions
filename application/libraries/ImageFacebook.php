<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ImageFacebook{

	public function __construct() {
		$this->ci = get_instance();
		$this->ci->load->model('Facebook_Images_model');
	}

	public function add($id_facebook, $url){

		if(!$id_facebook || !$url)
			return false;

		$analyze = $this->ci->cognitiveservices->analyzeImage($url);

		if(!$analyze) // General Error
			return false; 
		
		if(!isset($analyze->faces)){ // the image doesn't contain a valid face, usless to continue
			return $this->ci->Facebook_Images_model->addImage($id_facebook, $url, null,  null); 
		}

		$analyze = json_encode($analyze);

		$emotion = $this->ci->cognitiveservices->emotionImage($url);

		// If there's no emotion, usless to continue
		if(empty($emotion))
			return $this->ci->Facebook_Images_model->addImage($id_facebook, $url, null,  null); 

		$emotion = json_encode($emotion);

		$id = $this->ci->Facebook_Images_model->addImage($id_facebook, $url, $analyze, $emotion);

		if(!$id)
			return false;

		$tags = $this->ci->Facebook_Images_model->getImageTags($id_facebook, "facebook_id");

		$sparkResponse = json_decode($this->ci->sparkservice->analyzeImage($id, $tags));

		if($this->ci->Facebook_Images_model->updateImageOurClass($id, $sparkResponse->class))
			return true;
		else 
			return false;
	}

	public function getImage($id, $column){
		return $this->ci->Facebook_Images_model->getOneImage($id, $column);
	}

}