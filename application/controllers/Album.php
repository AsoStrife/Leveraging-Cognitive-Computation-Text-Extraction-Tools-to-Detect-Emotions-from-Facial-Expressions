<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Album extends CI_Controller{

	function __construct() {
        parent::__construct();

	}

	public function index(){

		$albumID = $this->uri->segment(2);

		if(!$albumID)
			redirect('/', 'refresh');

		if($this->facebook->is_authenticated()){

			$data['album'] = $this->facebook->request('get', '/' . $albumID);

			// If the group  id is not manage by this user, or the api get an error show the error on the view
			if(array_key_exists('error', $data['album'])){
				$data['error'] = $data['album'];
				$this->load->view('app_error/general_error', $data);
				//exit;
			}
			else{
				$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800), groups');
				$data['logoutUrl'] = $this->facebook->logout_url();


				$fb = $this->facebook->object();
				$data['photosAlbum'] = $fb->get('/'. $albumID .'/photos?fields=picture,source,name&type=uploaded')->getDecodedBody();

				$next = isset($data['photosAlbum']['paging']['next']) ? $data['photosAlbum']['paging']['next'] : null;

				while(isset($next)){
					$values = json_decode(file_get_contents($next), true);
					$data['photosAlbum']['data'] = array_merge($data['photosAlbum']['data'], $values['data']);
					$next = isset($values['paging']['next']) ? $values['paging']['next'] : null;
				}
				

				$this->load->view('album', $data);
			}
		}
		else
			redirect('/home/login', 'refresh');
	}
}