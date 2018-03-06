<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook_Images_model extends CI_Model {

	public function __construct() {
		$this->ci = get_instance();
		$this->allowed_emotion = array('anger', 'contempt', 'disgust', 'fear', 'happiness', 'neutral', 'sadness', 'surprise');
	}

	public function getOneImage($id, $column = "id"){
		if(!$id)
			return array();
		
		$data = $this->db->select('*')
						->from('images_facebook')
						->where($column, $id)
						->get()
						->row();
		if($data)
			return $data;
		else
			return array();
	}

	public function addImage($facebook_id, $url, $cs_vision, $cs_emotion){

		$data = array(
			'facebook_id' 	=> $facebook_id,
			'url' 			=> $url,
			'cs_vision' 	=> $cs_vision,
			'cs_emotion' 	=> $cs_emotion,
		);

		$insert = $this->db->insert('images_facebook', $data);

		if($insert)
			return $this->db->insert_id();
		else
			return false;
	}

	public function updateImage($facebook_id, $url, $cs_vision, $cs_emotion, $our_class){
		if(!$facebook_id)
			return false;

		$data = array(
			'url' 			=> $url,
			'cs_vision' 	=> $cs_vision,
			'cs_emotion' 	=> $cs_emotion,
			'our_class' 	=> $our_class
			);

		$this->db->where('facebook_id', $facebook_id);
		$update = $this->db->update('images_facebook', $data);

		if($update)
			return true;
		else
			return false;
	}

	public function updateImageOurClass($id, $our_class, $column = "id"){
		if(!$id)
			return false;
		
		$data = array(
			'our_class' => $our_class
		);

		$this->db->where($column, $id);
		$update = $this->db->update('images_facebook', $data);

		if($update)
			return true;
		else
			return false;
	}

	public function deleteImageByID($id){
		$delete = $this->db->delete('images_facebook', array('id' => $id));

		if($delete)
			return true;
		else
			return false;
	}

	public function deleteImageByIDFacebook($facebook_id){
		$delete = $this->db->delete('images_facebook', array('facebook_id' => $facebook_id));

		if($delete)
			return true;
		else
			return false;
	}

	// Read the json object on 
	public function getImageTags($id, $column = "id"){
		if($id == null)
			return false;

		$data = $this->db->select('*')
						->from('images_facebook')
						->where($column, $id)
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

	public function getImageEmotions($facebook_id){
		if($facebook_id == null)
			return false;

		$data = $this->db->select('*')
						->from('images_facebook')
						->where('facebook_id', $facebook_id)
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
