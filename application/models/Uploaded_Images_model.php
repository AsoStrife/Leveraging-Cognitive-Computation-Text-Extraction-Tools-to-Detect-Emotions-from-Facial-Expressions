<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Uploaded_Images_model extends CI_Model {

	public function __construct() {
		$this->ci = get_instance();
		$this->mainDirectory = './img/uploads/';
		$this->allowed_emotion = array('anger', 'contempt', 'disgust', 'fear', 'happiness', 'neutral', 'sadness', 'surprise');
	}

	public function getAllImages(){
		$data = $this->db->select('*')
						->from('images_upload')
						->order_by('id')
						->get()
						->result();
		if($data)
			return $data;
		else
			return array();
	}

	public function getOneImage($id){
		if(!$id)
			return array();
		
		$data = $this->db->select('*')
						->from('images_upload')
						->where('id', $id)
						->get()
						->row();
		if($data)
			return $data;
		else
			return array();
	}
	public function addImage($filename, $path, $cs_vision, $cs_emotion){

		$data = array(
			'filename' 		=> $filename,
			'path' 			=> $path,
			'cs_vision' 	=> $cs_vision,
			'cs_emotion' 	=> $cs_emotion,
		);

		$insert = $this->db->insert('images_upload', $data);

		if($insert)
			return $this->db->insert_id();
		else
			return false;
	}

	public function updateImage($id, $filename, $path, $class, $cs_vision, $cs_emotion, $our_class){
		if(!$id)
			return false;
		$data = array(
			'filename' 		=> $filename,
			'path' 			=> $path,
			'class' 		=> $class,
			'cs_vision' 	=> $cs_vision,
			'cs_emotion' 	=> $cs_emotion,
			'our_class' 	=> $our_class,
		);

		$this->db->where('id', $id);
		$update = $this->db->update('images_upload', $data);

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
		$update = $this->db->update('images_upload', $data);

		if($update)
			return true;
		else
			return false;
	}

	public function deleteImage($id){
		$delete = $this->db->delete('images_upload', array('id' => $id));

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
						->from('images_upload')
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
				
				return implode(" ", $tags);
			}
			return false;
		}

		return false;
	}
	public function getImageEmotions($id){
		if($id == null)
			return false;

		$data = $this->db->select('*')
						->from('images_upload')
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
}
