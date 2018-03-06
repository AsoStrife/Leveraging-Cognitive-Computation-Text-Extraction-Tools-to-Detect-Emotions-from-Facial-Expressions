<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller{

	function __construct() {
        parent::__construct();
	}

	public function index(){
		show_404();
	}
	
	/**
	public function index(){

		$groupID = $this->uri->segment(2);

		if(!$groupID)
			redirect('/', 'refresh');

		if($this->facebook->is_authenticated()){

			$data['group'] = $this->facebook->request('get', '/' . $groupID);

			// If the group  id is not manage by this user, or the api get an error show the error on the view
			if(array_key_exists('error', $data['group'])){
				$data['error'] = $data['group'];
				$this->load->view('app_error/general_error', $data);
				exit;
			}

			$data['albums'] = $this->facebook->request('get', '/' . $groupID.  '?fields=albums'); // Bisogna fare l'handler per prendere tutti gli album
			//$data['videos'] = $this->facebook->request('get', '/' . $groupID.  '?fields=videos'); // Bisogna fare l'handler per prendere tutti i video

			$data['user'] = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(800).height(800), groups');
			$data['group_summary'] = $this->facebook->request('get', '/'.$groupID.'?fields=admins.limit(0).summary(true), members.limit(0).summary(true)');
			$data['logoutUrl'] = $this->facebook->logout_url();


			$this->load->view('group', $data);
			
		}
		else
			redirect('/home/login', 'refresh');
	}
	*/
}