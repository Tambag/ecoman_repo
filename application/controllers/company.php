<?php
class Company extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('company_model');
	}

	public function new_company(){
		$this->load->library('form_validation');
		$this->load->library('googlemaps');
//alert("1:" + event.latLng.lat() + " 2:" + event.latLng.lng());
		$config['center'] = '39.98280915242299, 32.73923635482788';
	    $config['zoom'] = '15';
//	    $config['onclick'] = '$("#latId").val("Lat:" + event.latLng.lat()); $("#longId").val("Long:" + event.latLng.lng()); $("#lat").val(event.latLng.lat()); $("#long").val(event.latLng.lng());';
 	    $config['places'] = TRUE;
	    $config['placesRadius'] = 20; 
	    $this->googlemaps->initialize($config);

		$data['map'] = $this->googlemaps->create_map();


		$this->form_validation->set_rules('companyName', 'Company Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('naceCode', 'Nace Code', 'trim|required|xss_clean|regex_match[/^\d{2}\.\d{2}\.\d{2}$/]');
		$this->form_validation->set_rules('coordinates', 'Coordinates', 'trim|xss_clean');
		$this->form_validation->set_rules('companyDescription', 'Company Description', 'trim|xss_clean');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|is_unique[T_CMPNY.email]');
		$this->form_validation->set_rules('cellPhone', 'Cell Phone Number', 'required|numeric|min_length[11]|xss_clean');
		$this->form_validation->set_rules('workPhone', 'Work Phone Number', 'required|numeric|min_length[11]|xss_clean');
		$this->form_validation->set_rules('fax', 'Fax Number', 'required|numeric|min_length[11]|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');


		if ($this->form_validation->run() !== FALSE)
		{
			$config['upload_path'] = './assets/company_pictures/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '5000';
			$config['file_name']	= $this->input->post('companyName').'.jpg';
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload())
			{
				print_r($this->upload->display_errors());
				exit;
			}

			$config['image_library'] = 'gd2';
			$config['source_image']	= './assets/company_pictures/'.$this->input->post('companyName').'.jpg';
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 200;
			$config['height'] = 200;
			$this->load->library('image_lib', $config);

			$this->image_lib->resize();

			$data = array(
			'name'=>$this->input->post('companyName'),
			'phone_num_1'=>$this->input->post('cellPhone'),
			'phone_num_2'=>$this->input->post('workPhone'),
			'fax_num'=>$this->input->post('fax'),
			'address'=>$this->input->post('address'),
			'description'=>$this->input->post('companyDescription'),
			'email'=>$this->input->post('email'),
			'latitude'=>$this->input->post('lat'),
			'longitude'=>$this->input->post('long'),
			'logo'=>$this->input->post('companyName').'.jpg',
			'active'=>'1'
			);
			$code = $this->input->post('naceCode');
			$this->company_model->create_company($data,$code);

			redirect('okoldu', 'refresh');
		}

		$this->load->view('template/header');
		$this->load->view('company/create_company',$data);
		$this->load->view('template/footer');
	}

	public function show_all_companies(){
		$data['companies'] = $this->company_model->get_companies();

		$this->load->view('template/header');
		$this->load->view('company/show_all_companies',$data);
		$this->load->view('template/footer');
	}

	public function companies($term){
		$this->load->library('googlemaps');

		$data['companies'] = $this->company_model->get_company($term);
		$config['center'] = $data['companies'][0]['latitude'].','. $data['companies'][0]['longitude'];
	    $config['zoom'] = '15';
	    $config['places'] = TRUE;
	    $config['placesRadius'] = 20; 
	    $marker = array();
		$marker['position'] = $data['companies'][0]['latitude'].','. $data['companies'][0]['longitude'];
		$this->googlemaps->add_marker($marker);
	    $this->googlemaps->initialize($config);

		$data['map'] = $this->googlemaps->create_map();
		$data['nacecode'] = $this->company_model->get_nace_code($term);
		$data['prjname'] = $this->company_model->get_company_proj($term);
		$data['cmpnyperson'] = $this->company_model->get_company_workers($term);

		$this->load->view('template/header');
		$this->load->view('company/company_show_detailed',$data);
		$this->load->view('template/footer');
	}
}
?>
