<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller{
	function __construct() {
        parent::__construct();
		// Load facebook library
		$this->load->library('facebook');
		$this->load->library('CognitiveServices');
	}
	
	public function index(){
		/*
		require_once(APPPATH . 'libraries/vendor/autoload.php');
		require_once(APPPATH . 'libraries/vendor/pear/http_request2/HTTP/Request2.php');

		$url = "https://scontent.xx.fbcdn.net/v/t31.0-1/p960x960/21992794_806035529556712_5428056146789228173_o.jpg?oh=aff0622035d0c3bca30ff2e4b22ae3b6&oe=5B1253A4";
		$response = $this->cognitiveservices->analyzeImage( $url );
		//$response = $this->cognitiveservices->emotionImage( $url );

		header('Content-Type: application/json; charset=utf-8');
		print_r(json_encode(json_decode($response), JSON_PRETTY_PRINT));
		*/

		$dir = $this->dirToArray('./img/datasets/01');
		header('Content-Type: application/json; charset=utf-8');
		print_r(json_encode(($dir), JSON_PRETTY_PRINT));
		//print_r($dir);

	}


	/**
	 * @link http://are2.andreacorriga.com/test/getFileReforgiato?output=csv
	 * @link http://are2.andreacorriga.com/test/getFileReforgiato?output=csv&id_dataset=1
	 * Get in various format the images classified
	 * @param output [csv, array, json]
	 * @param id_dataset [null, integer]
	 */
	public function getFileReforgiato(){

		$id_dataset = $this->input->get('id_dataset') ? $this->input->get('id_dataset') : null;
		$output = $this->input->get('output');

		/** 
		 * if is it necessary use csv, currently useless
		 */
		if($id_dataset)
			$images = $this->db->select('id, filename, class, cs_emotion, our_class')->from('images')->where('id_dataset', $id_dataset)->where('our_class !=', null)->get()->result();
		else
			$images = $this->db->select('id, filename, class, cs_emotion, our_class')->from('images')->where('our_class !=', null)->get()->result();

		$index = 0;

		foreach($images as $image){
			$image->cs_emotion = json_decode($image->cs_emotion, 1);
			$value = max($image->cs_emotion[0]["scores"]);
			$key = array_search($value, $image->cs_emotion[0]["scores"]);
			$images[$index]->cs_emotion = $key;
			$index++;
		}
		
		
		if($output == "array" || !isset($output)){
			echo '<pre>';
			print_r($images);
		}

		if($output == "json"){
			$images = json_encode($images, JSON_PRETTY_PRINT);
			header('Content-Type: application/json; charset=utf-8');	
			print_r($images);
		}

		if($output == "csv"){
			$data = "id, filename, class, cs_emotion, our_class\n";

			foreach($images as $image){
				$data .= $image->id . ', ' . $image->filename . ', ' . $image->class . ', ' . $image->cs_emotion . ', ' . $image->our_class ."\n";
			}
			
			header("Content-Type: text/plain; charset=utf-8");
			echo($data);
		}

	}
}