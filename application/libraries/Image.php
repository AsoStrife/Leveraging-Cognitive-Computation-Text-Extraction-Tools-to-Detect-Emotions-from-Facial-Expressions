<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Image{

	public $id 			= null;
	public $filename 	= null;
	public $path 		= null; 
	public $class 		= null; 
	public $cs_vision	= null; 
	public $cs_emotion 	= null; 
	public $our_class 	= null; 
	public $id_dataset 	= null; 

	public function __construct() {
		//$allowed_emotion = array('anger', 'contempt', 'disgust', 'fear', 'happiness', 'neutral', 'sadness', 'surprise');
	}
}