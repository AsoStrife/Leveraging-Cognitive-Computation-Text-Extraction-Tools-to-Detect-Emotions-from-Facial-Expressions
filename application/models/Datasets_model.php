<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Datasets_model extends CI_Model {

	public function __construct() {
		$this->ci = get_instance();
		$this->mainDirectory = './img/datasets/';
		$this->allowed_emotion = array('anger', 'contempt', 'disgust', 'fear', 'happiness', 'neutral', 'sadness', 'surprise');
	}

	// Check if one of dataset id exist on DB
	public function checkDatasetsExistOnDB($id = null){
		if($id == null)
			return false;

		$count = $this->db->select('id')
						->from('images')
						->where('id_dataset', $id)
						->count_all_results();

		if($count > 0)
			return $count;
		else
			return false;
	}

	// Return all images of one dataset
	public function getImagesByDataset($id = false){
		if($id == null)
			return false;

		$data = $this->db->select('*')
						->from('images')
						->where('id_dataset', $id)
						->order_by('id')
						->get()
						->result();

		if($data)
			return $data;
		else
			return false;
	}

	// Return one image by ID
	public function getImageById($id = null){
		if($id == null)
			return false;

		$data = $this->db->select('*')
						->from('images')
						->where('id', $id)
						->get()
						->row();

		if($data)
			return $data;
		else
			return false;
	}

	// Return one image by ID
	public function getImageByFilenameAndDataset($filename = null, $id_dataset){
		if($filename == null || $id_dataset == null)
			return false;

		$data = $this->db->select('*')
						->from('images')
						->where('filename', $filename)
						->where('id_dataset', $id_dataset)
						->get()
						->row();

		if($data)
			return $data;
		else
			return false;
	}
	public function addImage($filename, $path, $class, $cs_vision, $cs_emotion, $our_class, $id_dataset){
		if (!in_array($class, $this->allowed_emotion))
			$class = "neutral"; // if the class is not alowed, put neutral for security

		$data = array(
			'filename' 		=> $filename,
			'path' 			=> $path,
			'class' 		=> $class,
			'cs_vision' 	=> $cs_vision,
			'cs_emotion' 	=> $cs_emotion,
			'our_class' 	=> $our_class,
			'id_dataset' 	=> $id_dataset
		);

		$insert = $this->db->insert('images', $data);

		if($insert)
			return true;
		else
			return false;
	}

	public function updateImage($id, $filename, $path, $class, $cs_vision, $cs_emotion, $our_class, $id_dataset){
		if(!$id)
			return false;
		$data = array(
			'filename' 		=> $filename,
			'path' 			=> $path,
			'class' 		=> $class,
			'cs_vision' 	=> $cs_vision,
			'cs_emotion' 	=> $cs_emotion,
			'our_class' 	=> $our_class,
			'id_dataset' 	=> $id_dataset
		);

		$this->db->where('id', $id);
		$update = $this->db->update('images', $data);

		if($update)
			return true;
		else
			return false;
	}

	public function updateImageOurClass($id, $our_class){
		if(!$id)
			return false;
		$data = array(
			'our_class' => $our_class
		);

		$this->db->where('id', $id);
		$update = $this->db->update('images', $data);

		if($update)
			return true;
		else
			return false;
	}

	public function deleteImage($id){
		$delete = $this->db->delete('images', array('id' => $id));

		if($delete)
			return true;
		else
			return false;
	}

	public function deleteImageByDataset($id_dataset){
		if(!$id_dataset)
			return false;

		$delete = $this->db->delete('images', array('id_dataset' => $id_dataset));

		if($delete)
			return true;
		else
			return false;
	}


	// Read the json object on 
	public function getImageTags($id){
		if($id == null)
			return false;

		$data = $this->db->select('*')
						->from('images')
						->where('id', $id)
						->get()
						->row();

		if(!$data)
			return false;
		else{
			if($data->cs_emotion){

				$vision = json_decode($data->cs_vision);
				$tags = array();

				foreach($vision->tags as $tag)
					$tags[] = $tag->name;

				foreach($vision->description->tags as $tag)
					$tags[] = $tag;
				
				return $tags;
			}
			return false;
		}

		return false;
	}
	public function getImageEmotions($id){
		if($id == null)
			return false;

		$data = $this->db->select('*')
						->from('images')
						->where('id', $id)
						->get()
						->row();

		if(!$data)
			return false;
		else{

			if($data->cs_emotion){
				$emotion = json_decode($data->cs_emotion);

				$emotions = array(
									"anger" 	=> $emotion[0]->scores->anger,
									"contempt" 	=> $emotion[0]->scores->contempt,
									"disgust"	=> $emotion[0]->scores->disgust,
									"happiness" => $emotion[0]->scores->happiness,
									"neutral" 	=> $emotion[0]->scores->neutral,
									"sadness" 	=> $emotion[0]->scores->sadness,
									"surprise" 	=> $emotion[0]->scores->surprises
				);

				return $emotions;
			}
			else
				return false;
		}

		return false; 
	}

	// Create a custom query for selecting all images of one datasets
	public function getImagesQueryByDatasets($id_dataset, $select){
		if($id_dataset == null){
			$data = $this->db->select($select)
						->from('images')
						->order_by('id')
						->get()
						->result();
		}
		else{
			$data = $this->db->select($select)
							->from('images')
							->where('id_dataset', $id_dataset)
							->order_by('id')
							->get()
							->result();
		}

		if($data)
			return $data;
		else
			return false;
	}
}