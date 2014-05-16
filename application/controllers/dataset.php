<?php
class Dataset extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('product_model','',TRUE);
	}

	public function product(){
		$data['product_list'] = $this->product_model->product_list();

		$this->load->view('template/header');
		$this->load->view('dataset/product',$data);
		$this->load->view('template/footer');
	}

	public function new_product()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('productname', 'Product name', 'trim|required|xss_clean|strip_tags');
		
		$this->form_validation->set_rules('producttype', 'Product type', 'trim|required|xss_clean|strip_tags');
		
		$this->form_validation->set_rules('quan', 'Quantity', 'trim|required|xss_clean|strip_tags|numeric');
		
		$this->form_validation->set_rules('productunit', 'Product unit', 'trim|required|xss_clean|strip_tags');
		
		$this->form_validation->set_rules('productdesc', 'Product description', 'trim|required|xss_clean|strip_tags');


		if($this->form_validation->run() == FALSE) {
			$this->load->view('template/header');
			$this->load->view('dataset/new_product');
			$this->load->view('template/footer');
		}
		else{
			$productname = $this->input->post('productname');
			$producttype = $this->input->post('producttype');
			$quan = $this->input->post('quan');
			$productunit = $this->input->post('productunit');
			$productdesc = $this->input->post('productdesc');

			$this->product_model->register_pro($productname,$producttype,$quan,$productunit,$productdesc);

			redirect(base_url('product'), 'refresh');
		}
	}

	public function new_flow()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('flowname', 'Flow name', 'trim|required|xss_clean|strip_tags');
		
		$this->form_validation->set_rules('ei', 'Environmental impact', 'trim|required|xss_clean|strip_tags');
		
		$this->form_validation->set_rules('cost', 'Cost', 'trim|required|xss_clean|strip_tags|numeric');
		
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|strip_tags|numeric');
		

		if($this->form_validation->run() == FALSE) {
			$this->load->view('template/header');
			$this->load->view('dataset/new_flow');
			$this->load->view('template/footer');
		}
		else{
			$flowname = $this->input->post('flowname');
			$ei = $this->input->post('ei');
			$cost = $this->input->post('cost');
			$amount = $this->input->post('amount');

			$this->product_model->register_flow($flowname,$ei,$cost,$amount);

			redirect(base_url('flow_and_component'), 'refresh');
		}
	}

	public function new_component()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('componentname', 'Component name', 'trim|required|xss_clean|strip_tags');
	
		if($this->form_validation->run() == FALSE) {
			$this->load->view('template/header');
			$this->load->view('dataset/new_component');
			$this->load->view('template/footer');
		}
		else{
			$componentname = $this->input->post('componentname');

			$this->product_model->register_component($componentname);

			redirect(base_url('flow_and_component'), 'refresh');
		}
	}

	public function flow_and_component(){

		$data['flow_list'] = $this->product_model->flow_list();
		$data['component_list'] = $this->product_model->component_list();


		$this->load->view('template/header');
		$this->load->view('dataset/flow_and_component', $data);
		$this->load->view('template/footer');
	}

}