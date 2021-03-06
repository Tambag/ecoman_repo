<?php
class Admin extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('template/header_admin');
		$this->load->view('isscoping/index');
		$this->load->view('template/footer_admin');
	}

	public function newFlow(){
                //print_r($this->session->userdata['user_in']);
                $loginData = $this->session->userdata('user_in');
		if(empty($loginData)){
			redirect(base_url('login'),'refresh');
		}
                $data['userName'] = $this->session->userdata['user_in']['username'];
		$this->load->view('template/header_admin');
		$this->load->view('admin/newFlow',$data);    
		$this->load->view('template/footer_admin');
	}
        
        public function newEquipment(){  
            //print_r($this->session->userdata['user_in']['id']);
                $loginData = $this->session->userdata('user_in');
		if(empty($loginData)){
			redirect(base_url('login'),'refresh');
		}
                $data['userName'] = $this->session->userdata['user_in']['username'];
		$this->load->view('template/header_admin');
		$this->load->view('admin/newEquipment',$data); 
		$this->load->view('template/footer_admin');
	}
        
        public function newProcess(){  
            //print_r($this->session->userdata['user_in']['id']);
                $loginData = $this->session->userdata('user_in');
		if(empty($loginData)){
			redirect(base_url('login'),'refresh');
		}
                $data['userName'] = $this->session->userdata['user_in']['username'];
		$this->load->view('template/header_admin');
		$this->load->view('admin/newProcess',$data); 
		$this->load->view('template/footer_admin');
	}
        
        public function tooltip(){
		//$this->load->view('template/header');
		$this->load->view('isscoping/tooltip');
		//$this->load->view('template/footer');
	}
        
         public function tooltipscenarios(){
		//$this->load->view('template/header');
		$this->load->view('isscoping/tooltipscenarios');
		//$this->load->view('template/footer');
	}
}

