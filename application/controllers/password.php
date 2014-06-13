<?php
class Password extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('password_model');
		$this->load->library('form_validation');
	}	

	public function send_email_for_change_pass(){

		$this->form_validation->set_rules('email', 'E-mail', 'trim|xss_clean|required');

		if ($this->form_validation->run() !== FALSE){

			$tmp = $this->session->userdata('user_in');
			$user_id = $tmp['id'];
			
			$email = $this->input->post('email');

			$random_str = $this->generateRandomString();
			$asd = 'localhost/ecoman_repo/change_pass/'.$random_str;
			$message = '<a href='.$asd.'>Change Password</a>';

			$rnd_str = array(
					'random_string' => $random_str,
					'click_control' => 1
				);
			$this->password_model->set_random_string($user_id,$rnd_str);

			$data = array(
					'message' => $message,
					'email' => $email
				);
			$this->sendMAil($data);
			$this->user_logout();
		}
		$this->load->view('template/header');
		$this->load->view('password/send_email_for_change_pass');
		$this->load->view('template/footer');
	}

	public function change_pass($rnd_str){

		$random = $rnd_str = $this->uri->segment(2);

		if($this->password_model->click_control($random) == true){

			$this->form_validation->set_rules('old_pass', 'Old Password', 'trim|xss_clean|required');
			$this->form_validation->set_rules('new_pass', 'New Password', 'trim|xss_clean|required|callback_password_check');
			$this->form_validation->set_rules('new_pass_again', 'New Password Again', 'trim|xss_clean|required');

			if ($this->form_validation->run() !== FALSE){

				$user_id = $this->password_model->get_user_id($random);
				$data = array(
					'random_string' => $random
				);

				$old_pass = $this->input->post('old_pass');
				$new_pass = $this->input->post('new_pass');
				if($this->password_model->do_similar_pass($user_id,md5($old_pass)) == true){
					$control = array(
							'psswrd' => md5($new_pass)
						);
					$this->password_model->change_pass($user_id,$control);
				}

				$message = 'Sifreniz degistirildi. Yeni şifreniz: '.$new_pass;
				$email = $this->password_model->get_email($user_id);

				$send_email = array(
						'message' => $message,
						'email' => $email
					);
				$this->sendMAil($send_email);

				$rnd_str = array(
					'random_string' => null,
					'click_control' => 0
				);
				$this->password_model->set_random_string_zero($random,$rnd_str);

				redirect('login','refresh');
			}

			$this->load->view('template/header');
			$this->load->view('password/change_pass',$data);
			$this->load->view('template/footer');
		}
		else{
			redirect('','refresh');
		}
	}

	public function sendMail($data)
	{
		$config = Array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'ssl://smtp.googlemail.com',
		  'smtp_port' => 465,
		  'smtp_user' => 'ostimteknoloji@gmail.com', // change it to yours
		  'smtp_pass' => 'ostim321', // change it to yours
		  'mailtype' => 'html',
		  'charset' => 'iso-8859-1',
		  'wordwrap' => TRUE
		);

		$message = '';
        $this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('ostimteknoloji@gmail.com'); // change it to yours
		$this->email->to($data['email']);// change it to yours
		$this->email->subject('Bilgilendirme!');
		$this->email->message($data['message']);
		if($this->email->send())
		{
			echo 'Email sent.';
		}
		else
		{
			echo 'olmadi';
			exit();
		}
	}

	public function password_check(){
		if($this->input->post('new_pass') == $this->input->post('new_pass_again')){
			return true;
		}
		else{
			$this->form_validation->set_message('password_check','Passwords aren\'t same.');
			return false;
		}
	}

	public function generateRandomString() {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < 20; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

	public function user_logout(){
		$this->session->sess_destroy();
		redirect('', 'refresh');
	}
}
?>

