<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/vendor/autoload.php');
require_once(APPPATH . 'libraries/vendor/pear/http_request2/HTTP/Request2.php');

class CognitiveServices {
	
	public function __construct() {
		$this->ci = get_instance();      
		$this->ci->config->load('cognitive_services');
	}

	public function moderateImage($imageUrl){
		
		$cognitive_services_endpoint 	= $this->ci->config->item('cs_moderation_endpoint');
		$subscription_key 				= $this->ci->config->item('cs_moderation_key1');

		$request = new Http_Request2($cognitive_services_endpoint . '/moderate/v1.0/ProcessImage/Evaluate');
		$url = $request->getUrl();

		$headers = array(
			// Request headers
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => $subscription_key,
		);

		$request->setHeader($headers);

		$parameters = array(
			// Request parameters
			'CacheImage' => true,
		);

		$url->setQueryVariables($parameters);

		$request->setMethod(HTTP_Request2::METHOD_POST);

		$array = array(
				"DataRepresentation" => "URL",
				"Value" => $imageUrl // Method Parameter
		);

		$body = json_encode( $array );
		// Request body
		$request->setBody($body);

		try{
			$response = $request->send();
			return json_decode($response->getBody());
		}
		catch (HttpException $ex){
			return $ex;
		}
	}
	
	public function analyzeImage($imageUrl){
		$cognitive_services_endpoint 	= $this->ci->config->item('cs_vision_endpoint');
		$subscription_key 				= $this->ci->config->item('cs_vision_key1');


		$request = new Http_Request2( $cognitive_services_endpoint . '/analyze' );
		$url = $request->getUrl();

		$headers = array(
			'Content-Type' => 'application/json',
			'Ocp-Apim-Subscription-Key' => $subscription_key,
		);

		$request->setHeader($headers);

		$parameters = array(
			'visualFeatures' => 'Categories, Tags, Description, Faces, Color, Adult',
			'language' => 'en'
		);

		$url->setQueryVariables($parameters);

		$request->setMethod(HTTP_Request2::METHOD_POST);

		$jsonBody = json_encode(array("url" => $imageUrl)); 
		$request->setBody( $jsonBody );  

		try{
			$response = $request->send();
			return json_decode($response->getBody());
		}
		catch (HttpException $ex){
			return json_decode($ex);
		}
	}

	public function emotionImage($imageUrl){
		$cognitive_services_endpoint 	= $this->ci->config->item('cs_emotion_endpoint');
		$subscription_key 				= $this->ci->config->item('cs_emotion_key1');

		$request = new Http_Request2($cognitive_services_endpoint . '/recognize');
		$url = $request->getUrl();

		$headers = array(
			'Content-Type' 				=> 'application/json',
			'Ocp-Apim-Subscription-Key' => $subscription_key,
		);
		$request->setHeader($headers);

		$parameters = array(
		);

		$url->setQueryVariables($parameters);

		$request->setMethod(HTTP_Request2::METHOD_POST);

		$jsonBody = json_encode(array("url" => $imageUrl)); 
		$request->setBody($jsonBody);

		try{
			$response = $request->send();
			return json_decode($response->getBody());
		}
		catch (HttpException $ex){
			return json_decode($ex);
		}
	}
}