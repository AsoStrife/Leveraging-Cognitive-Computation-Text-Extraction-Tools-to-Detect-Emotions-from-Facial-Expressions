<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Photo extends CI_Controller{
	function __construct() {
        parent::__construct();
        $this->load->model('Uploaded_Images_model');
	}

	// Get general info about the user and is groups where is admin 
	public function uploaded(){
		// Check if user is logged in
		if($this->facebook->is_authenticated()){
			// Get user facebook profile details
			$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800)');
			//$data['groups'] = $this->facebook->request('get', '/me?fields=groups');
			$data['logoutUrl'] = $this->facebook->logout_url();
			$data['datasets'] = $this->datasets->getDatasets();

			$id_photo = $this->uri->segment(3);

			$data['photo'] = $this->Uploaded_Images_model->getOneImage($id_photo);
			$data['tags'] = $this->Uploaded_Images_model->getImageTags($id_photo);
			$this->load->view('uploaded_photo', $data);
		}
		else{
			redirect('/home/login', 'refresh');
		}
	}

	// Get general info about the user and is groups where is admin 
	public function facebook(){
		// Check if user is logged in
		if($this->facebook->is_authenticated()){
			// Get user facebook profile details
			$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800)');
			//$data['groups'] = $this->facebook->request('get', '/me?fields=groups');
			$data['logoutUrl'] = $this->facebook->logout_url();
			$data['datasets'] = $this->datasets->getDatasets();

			$id_photo = $this->uri->segment(3);

			$image = $this->imagefacebook->getImage($id_photo, "facebook_id");

			if(empty($image)){
				$facebookPhoto = $this->facebook->request('get', '/' .$id_photo .'?fields=images');

				$this->imagefacebook->add($id_photo, $facebookPhoto['images'][0]['source']);
				redirect($_SERVER['REQUEST_URI'], 'refresh'); 
			}
			else{

				$data['photo'] = $this->Facebook_Images_model->getOneImage($id_photo, "facebook_id");
				$data['tags'] = $this->Facebook_Images_model->getImageTags($id_photo, "facebook_id");
				$this->load->view('facebook_photo', $data);
			}
		}
		else{
			redirect('/home/login', 'refresh');
		}
	}
}