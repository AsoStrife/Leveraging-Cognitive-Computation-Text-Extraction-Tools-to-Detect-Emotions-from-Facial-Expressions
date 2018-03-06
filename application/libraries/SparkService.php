<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/vendor/autoload.php');
require_once(APPPATH . 'libraries/vendor/pear/http_request2/HTTP/Request2.php');

class SparkService {
	
	private $serviceUrl = "http://192.167.155.71/acorriga/classifier";

	public function __construct() {
		$this->ci = get_instance();      
		$this->ci->config->load('cognitive_services');
	}
	
	public function analyzeImage($imageID, $tags){

		try {

			$request = new HTTP_Request2( $this->serviceUrl );
			$parameters = array('imageID' => $imageID, 'tags' => $tags);
			$request->setMethod(HTTP_Request2::METHOD_POST)->addPostParameter( $parameters);
			
			return $request->send()->getBody();

		} catch (Exception $exc) {
			echo $exc->getMessage();
		}

	}

}