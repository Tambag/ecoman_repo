<?php
class Dataset extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('flow_model');
		$this->load->model('process_model');
	}

	public function product(){
		$data['product_list'] = $this->product_model->product_list();

		$this->load->view('template/header');
		$this->load->view('dataset/product',$data);
		$this->load->view('template/footer');
	}

	public function new_product()
	{

		$data['products']=$this->product_model->get_product_list();

		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('product', 'Product name', 'trim|required|xss_clean|strip_tags');

		if($this->form_validation->run() == FALSE) {
			$this->load->view('template/header');
			$this->load->view('dataset/dataSetLeftSide');
			$this->load->view('dataset/new_product',$data);
			$this->load->view('template/footer');
		}
		else{
			$product = $this->input->post('product');

			
			$this->product_model->register_product_to_company();

			redirect(base_url('new_product'), 'refresh');
		}
	}

	public function new_flow()
	{
		$data['flownames'] = $this->flow_model->get_flowname_list();
		$data['flowtypes'] = $this->flow_model->get_flowtype_list();

		$companyID = 1; // bunu alamıyoruz şuan. static olarak 1

		$data['company_flows']=$this->flow_model->get_company_flow_list($companyID);


		$this->load->library('form_validation');
		$this->form_validation->set_rules('flowname', 'Flow Name', 'trim|required|xss_clean|strip_tags');
		$this->form_validation->set_rules('flowtype', 'Flow Type', 'trim|required|xss_clean|strip_tags');	
		$this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|xss_clean|strip_tags|numeric');
		$this->form_validation->set_rules('cost', 'Cost', 'trim|required|xss_clean|strip_tags|numeric');
		$this->form_validation->set_rules('ep', 'EP', 'trim|required|xss_clean|strip_tags|numeric');

		if($this->form_validation->run() !== FALSE) {

			$flowID = $this->input->post('flowname');
			$flowtypeID = $this->input->post('flowtype');
			$ep = $this->input->post('ep');
			$cost = $this->input->post('cost');
			$quantity = $this->input->post('quantity');

			$flow = array(
			'cmpny_id'=>$companyID,
			'flow_id'=>$flowID,
			'qntty'=>$quantity,
			'cost' =>$cost,
			'ep' => $ep,
			'flow_type_id'=> $flowtypeID
			);
			$this->flow_model->register_flow_to_company($flow);

			redirect(base_url('new_flow'), 'refresh'); // tablo olusurken ajax kullanılabilir. 
			//şuan sayfa yenileniyor her seferinde database'den satırlar ekleniyor.

		}else{

			$this->load->view('template/header');
			$this->load->view('dataset/dataSetLeftSide');
			$this->load->view('dataset/new_flow',$data);
			$this->load->view('template/footer');
		}




	}

	public function new_component()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('componentname', 'Component name', 'trim|required|xss_clean|strip_tags');
	
		if($this->form_validation->run() == FALSE) {
			$this->load->view('template/header');
			$this->load->view('dataset/dataSetLeftSide');
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


	public function new_process(){
		$data['process'] = $this->process_model->get_process();

		$companyID = 1; // bunu alamıyoruz şuan. static olarak 1

		$data['company_flows']=$this->flow_model->get_company_flow_list($companyID);

		$this->load->view('template/header');
		$this->load->view('dataset/dataSetLeftSide');
		$this->load->view('dataset/new_process',$data);
		$this->load->view('template/footer');
	}
}