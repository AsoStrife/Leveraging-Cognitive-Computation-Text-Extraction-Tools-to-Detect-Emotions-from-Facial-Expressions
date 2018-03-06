<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller{
	function __construct() {
        parent::__construct();
		// Load facebook library
	}

	// Get general info about the user and is groups where is admin 
	public function index(){
		// Check if user is logged in
		if($this->facebook->is_authenticated()){
			// Get user facebook profile details
			$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800)');
			//$data['groups'] = $this->facebook->request('get', '/me?fields=groups');
			$data['logoutUrl'] = $this->facebook->logout_url();
			$data['datasets'] = $this->datasets->getDatasets();

			$data['facebookPhotos'] = $this->facebook->request('get', '/me/photos?fields=picture, images');
			
			$data['uploadedPhotos'] = $this->imageupload->getImages();

			$this->load->view('index', $data);
		}
		else{
			/*echo '<b>code:</b> ' . $_GET['code'] .'<br>';
			echo '<b>state:</b> ' . $_GET['state'];

			$fb = $this->facebook->object();
			print_r($fb);*/
			redirect('/home/login', 'refresh');
		}
		/*
		http://are2.andreacorriga.com/home?code=AQCMFHWjnJICfbn7ZrCJxqzCFEJd7Cl0XY-fcGRCMMxtJKPQDSMh9JTWM9VQGI0sEdZ-N_XlKWqlX9kwYqJs-4CKj6ZkzDMfJurmBMuMmTJiuVBeYP3UXz-DTFJN-Lt9ERoDTVzhQOk772InGScMCUiaZdFuqUil5WS768rWeFAw3PdVE1MH0vHqdX8JumxdqLvfacV6ohRxMBFj-Gwm4vHlAUSzHrQWlf389DJ2YOgy8R0WKMs_pKXlPpYwcdd-f71mhz3Ip7LuYQICLwc4LXDvy8y6LaroFNXPH0MrI9st5Gb10OBstw0MLxE0n20E_piDJmoYtVw0lE06xc04krUt&state=7c314af51da73938b3961df21eacfe30#_=_

		http://are2.andreacorriga.com/home?code=&state=
			//redirect('/home/login', 'refresh');
			*/
	}

	// Login page, if already logged return to home
	public function login(){

		// Check if user is logged in
		if(!$this->facebook->is_authenticated()){
			// Get login URL
			$data['authUrl'] =  $this->facebook->login_url();
			// Load login & profile view
			$this->load->view('login', $data);
		}
	else 
		redirect('/home', 'refresh');
		
	}

	// Logout page, if user is not logged only return to the home
	public function logout() {
		if($this->facebook->is_authenticated()){
			// Remove local Facebook session
			$this->facebook->destroy_session();
		}
		redirect('/', 'refresh');
	}

	// Privacy policy page
	public function privacy_policy(){
		$data = array();
		if($this->facebook->is_authenticated()){
			$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture.width(800).height(800)');
			$data['logoutUrl'] = $this->facebook->logout_url();
		}
		$this->load->view('privacy_policy', $data);
	}

	public function do_upload(){
		
		$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800)');
		$data['logoutUrl'] = $this->facebook->logout_url();

		$config['upload_path']	= './img/uploads/';
		$config['allowed_types']= 'jpg|png';
		$config['max_size']		= 5000;
		$config['max_width']	= 4096;
		$config['max_height']	= 2160;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('uploadPic')){
			$data['error'] = $this->upload->display_errors();
			$this->load->view('error', $data);
		}
		else{
			$data = array('upload_data' => $this->upload->data());

			$filename = $data['upload_data']['file_name'];

			if($this->imageupload->add($filename)){
				redirect('/', 'refresh');
			}
			else{
				unlink($data['upload_data']['full_path']);
				$data['error'] = 'The image doesn\'t contain a face.';
				$this->load->view('error', $data);
			}
		}
	}
}