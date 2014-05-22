<?php
class User extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}

	public function user_register(){
		$this->load->library('form_validation');

		//form kontroller
		$this->form_validation->set_rules('name','Name','trim|required|xss_clean	');
		$this->form_validation->set_rules('surname','Surname','trim|required|xss_clean');
		$this->form_validation->set_rules('jobTitle','Job Title','required|trim|xss_clean');
		$this->form_validation->set_rules('description','Description','trim|xss_clean');
		$this->form_validation->set_rules('email', 'e-mail' ,'trim|required|valid_email|is_unique[T_USER.email]');
		$this->form_validation->set_rules('cellPhone', 'Cell Phone Number', 'required|numeric|min_length[11]|xss_clean');
		$this->form_validation->set_rules('workPhone', 'Work Phone Number', 'required|numeric|min_length[11]|xss_clean');
		$this->form_validation->set_rules('fax', 'Fax Number', 'required|numeric|min_length[11]|xss_clean');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean|is_unique[T_USER.user_name]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|matches[rePassword]');

		if ($this->form_validation->run() !== FALSE)
		{
			//file properties
			$config['upload_path'] = './assets/user_pictures/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '5000';
			$config['file_name']	= $this->input->post('username').'.jpg';
			$this->load->library('upload', $config);

			//Resmi servera yükleme
			if (!$this->upload->do_upload())
			{
				print_r($this->upload->display_errors());
				exit;
			}

			//Yüklenen resmi boyutlandırma ve çevirme
			$config['image_library'] = 'gd2';
			$config['source_image']	= './assets/user_pictures/'.$this->input->post('username').'.jpg';
			$config['maintain_ratio'] = TRUE;
			$config['width']	 = 200;
			$config['height']	 = 200;
			$this->load->library('image_lib', $config);

			$this->image_lib->resize();

			//inserting data to database
			$data = array(
				'name'=>$this->input->post('name'),
				'surname'=>$this->input->post('surname'),
				'title'=>$this->input->post('jobTitle'),
				'description'=>$this->input->post('description'),
				'email'=>$this->input->post('email'),
				'phone_num_1'=>$this->input->post('cellPhone'),
				'phone_num_2'=>$this->input->post('workPhone'),
				'fax_num'=>$this->input->post('fax'),
				'user_name'=>$this->input->post('username'),
				'psswrd'=>md5($this->input->post('password')),
				'photo'=>$this->input->post('username').'.jpg'
			);
			$this->user_model->create_user($data);

			//process completed
			redirect('okoldu', 'refresh');
		}

		$this->load->view('template/header');
		$this->load->view('user/create_user');
		$this->load->view('template/footer');
	}

}
?>