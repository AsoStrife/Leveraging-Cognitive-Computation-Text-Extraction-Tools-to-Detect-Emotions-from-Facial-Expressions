<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Datasets{

	private $mainDirectory = './img/datasets/';

	public function __construct() {
		$this->ci = get_instance();
		$this->ci->load->model('datasets_model');
		$this->ci->load->library('image');
	}

	/**
	 * return all files with subfolder starting by $id folder. 
	 * 
	 * )
	*/
	public function getImagesFromDataset($id, $wantFolder = false) { 

		if($id == null)
			return false;

		$images = array();

		if($this->ci->datasets_model->checkDatasetsExistOnDB($id) && $wantFolder == false){
			$images = $this->ci->datasets_model->getImagesByDataset($id); 
		}
		else{
			$data = $this->getImagesFromDatasetFolder($id);
			$images = array_reduce($data, "array_merge", array()); // merge in monodimensional array
		}
		return $images;
	} 


	/**
	 * return all files with subfolder starting by $id folder. 
	 * If $id doesn't exist, star from ./img/datasets folder
	 * Array(
	 *	    [category] => Array(
	 *	            [0] => Array(
	 *	                    [filename] => filename.jpg
	 *	                    [path] => /img/datasets/ID/category/filename.jpg
	 *	                )
	 * )
	*/
	public function getImagesFromDatasetFolderArray($id = null){
		if($id == null)
			$dir = $this->mainDirectory;
		else
			$dir = $this->mainDirectory . $id;
		
		$result = array();

		$cdir = scandir($dir);

		foreach ($cdir as $key => $value){
			if (!in_array($value,array(".",".."))){
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){
					$result[$value] = $this->getImagesFromDatasetFolderArray($id . DIRECTORY_SEPARATOR . $value);
				}
				else{
					$result[] = array(
									'filename' => $value,
									'path' => substr($dir . DIRECTORY_SEPARATOR . $value, 1) // remove the first .
								);
				}
			}
		}

		return $result; 
	}

	/** 
	 * Return multidimensional array with [ id category] = array( Image Objects )
	 */ 
	public function getImagesFromDatasetFolder($id){

		$dir = $this->mainDirectory . $id;
			
		$cdir = scandir($dir);

		foreach ($cdir as $index => $value){
			if (!in_array($value,array(".",".."))){
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){
					// Work on first dimension, switch to new class
					$images[] = $this->getImagesFromDatasetFolder($id . DIRECTORY_SEPARATOR . $value);
				}
				else{
					// Add elements in the second array. $index-2 because [0] and [1] is . and ..
					$images[$index-2] = new Image();
					$images[$index-2]->filename = $value;
					$images[$index-2]->path 	= substr($dir . DIRECTORY_SEPARATOR . $value, 1); // remove the first
					$split = explode("/", $dir); // split $dir to have latest folder => class
					$images[$index-2]->class 	= end($split);

				}		
			} 
		}

		return $images;

	}

	// return datasets list in array
	public function getDatasets($bool = false){
		$datasets = scandir($this->mainDirectory, 1);

		if($bool == false)
			$datasets = array_diff($datasets, array('.', '..'));

		return $datasets;
	}

	// Analyze and store the information with azure cognitive services
	public function classifyImageWithAzure($image, $datasetID){
		if(!$image || !$datasetID)
			return false;

		$analyze = $this->ci->cognitiveservices->analyzeImage(base_url() . $image->path);
		
		if(!$analyze) // General Error
			return false; 

		if(!isset($analyze->faces)) // the image doesn't contain a valid face, usless to continue
			return false; 
		
		$analyze = json_encode($analyze);

		$emotion = $this->ci->cognitiveservices->emotionImage(base_url() . $image->path);

		// If there's no emotion, usless to continue
		if(empty($emotion))
			return false;

		$emotion = json_encode($emotion);

		$oldImage = $this->ci->datasets_model->getImageByFilenameAndDataset($image->filename, $datasetID);

		if($oldImage){
			if($this->ci->datasets_model->updateImage($oldImage->id, $oldImage->filename, $oldImage->path, $oldImage->class, $analyze, $emotion, $oldImage->our_class, $datasetID))
				return true;
			else
				return false;
		}
		else{
			if($this->ci->datasets_model->addImage($image->filename, $image->path, $image->class, $analyze, $emotion, $image->our_class, $datasetID))
				return true;
			else
				return false;
		}

		return false; // security return. Never came here. 

	}

	/**
	 * get the images of one dataset in this data structure: 
	 * arrayObj("id" => id, "class" => class, "tags" => "tags1 tags2 tags3" )
	 */
	public function getImagesForSpark($datasetID){
		$images = $this->ci->datasets_model->getImagesQueryByDatasets($datasetID, "id, class"); 

		if(!$images)
			return false;

		foreach($images as $key => $image){
			$images[$key]->tags = implode(" ", $this->ci->datasets_model->getImageTags($image->id));
		}

		return $images;
	}

	// Convert array into csv format for spark
	public function getDatasetCSV($images){
		if(!$images)
			return false;

		$data = "";

		foreach($images as $image){
			$tags = implode(" ", $image->tags);

			$data .= $image->class . "," . $tags . "\r\n";
		}

		return $data;
	}
}