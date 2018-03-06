<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Emotion extends CI_Controller{

	function __construct() {
        parent::__construct();
	}

	public function index(){
		$datasetID = $this->uri->segment(2);

		if(!$datasetID)
			redirect('/', 'refresh');

		if($this->facebook->is_authenticated()){
			$data['user'] 		= $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800)');
			$data['logoutUrl'] 	= $this->facebook->logout_url();
			$data['images'] 	= $this->datasets->getImagesFromDataset($datasetID);

			$this->load->view('emotion',  $data);
		}
		else
			redirect('/home/login', 'refresh');
	}

	public function delete(){
		$datasetID = $this->uri->segment(3);

		if(!$datasetID)
			redirect('/', 'refresh');

		$this->datasets_model->deleteImageByDataset($datasetID);

		redirect("/emotion/$datasetID", 'refresh');
	}


/*
     ___             __       ___      ___   ___ 
    /   \           |  |     /   \     \  \ /  / 
   /  ^  \          |  |    /  ^  \     \  V  /  
  /  /_\  \   .--.  |  |   /  /_\  \     >   <   
 /  _____  \  |  `--'  |  /  _____  \   /  .  \  
/__/     \__\  \______/  /__/     \__\ /__/ \__\ 

*/
                                                 
	public function ajax_azure_classifier(){
		$image = json_decode($this->input->post('image'));
		$datasetID = $this->input->post('datasetID');

		if(!$image || !$datasetID){
			echo "No POST image or no POST datasetID";
			return;
		}
		

		if($this->datasets->classifyImageWithAzure($image, $datasetID))
			echo $image->filename . " saved and classified";
		else
			echo $image->filename . " ignored because doesn't contain a face";
	}

	public function ajax_get_dataset_for_azure(){
		$datasetID = $this->input->post('datasetID');

		if(!$datasetID){
			echo "Something went wrong. No dataset for azure classifier";
			return;
		}

		$images = json_encode($this->datasets->getImagesFromDataset($datasetID, true));

		echo $images;
	}

	public function ajax_delete_all_data(){
		$datasetID = $this->input->post('datasetID');
		if(!$datasetID){
			echo "No POST datasetID";
			return;
		}

		if($this->datasets_model->deleteImageByDataset($datasetID))
			echo "All data has been deleted";
		else
			echo "Something went wrong. The data has not been deleted";

	}

	public function ajax_save_custom_classification(){
		$imageID = $this->input->post('imageID');
		$class = $this->input->post('class');
		
		if(!$imageID || !$class){
			echo "No POST image";
			return;
		}

		if($this->datasets_model->updateImageOurClass($imageID, $class))
			echo "Update class for image id: " . $imageID;
		else
			echo "Something went wrong. The data has not been updated";
	}

	public function ajax_get_dataset_for_custom_classifier(){
		$datasetID = $this->input->post('datasetID');
		
		if(!$datasetID){
			echo "No POST datasetID";
			return;
		}

		/** 
		 * if is it necessary use csv, currently useless
		 *
		$images = $this->datasets->getImagesForSpark($datasetID);
		$dataset = $this->datasets->getDatasetCSV($images);
		header("Content-Type: text/plain; charset=utf-8");
		echo($dataset);
		*/

		$images = json_encode($this->datasets->getImagesForSpark($datasetID), JSON_PRETTY_PRINT);
		//header('Content-Type: application/json; charset=utf-8');	
		print_r($images);
		
	}

}