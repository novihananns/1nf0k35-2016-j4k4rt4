<?php

class Admin_config extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_config_model');
	}
	
	function index()
	{
		$this->authentication->verify('admin','show');

		$data = $this->admin_config_model->get_data(); 
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Web Configuration";
		$data['theme_default_option'] = $this->admin_config_model->get_theme();
		$data['content'] = $this->parser->parse("admin/config/home",$data,true);

		$this->template->show($data,"home");
	}
	function checkBPJS($code=""){
		$data = $this->admin_config_model->checkBPJS($code); 

		echo json_encode($data);
	}
	function insertdata($kode=0)
	{
		//$this->authentication->verify('admin','add');
		$this->form_validation->set_rules('codepus', 'Puskesmas ', 'trim|required');
        $this->form_validation->set_rules('serverbpjs', 'serverbpjs', 'trim|required');
        $this->form_validation->set_rules('usernamebpjs', 'Username', 'trim|required');
        $this->form_validation->set_rules('passwordbpjs', 'Password', 'trim|required');
        $this->form_validation->set_rules('considbpjs', 'Cons Id', 'trim|required');
        $this->form_validation->set_rules('keybpjs', 'Secret Key', 'trim|required');
      	$data = $this->admin_config_model->get_data_bpjs(); 
		$data['title_group'] = "Admin Panel";
		$data['alert_form'] = '';
		$data['title_form'] = "BPJS Configuration";
		$data['kodepuskesmas'] = $this->admin_config_model->get_data_puskes();
		$data['theme_default_option'] = $this->admin_config_model->get_theme();

        if($this->form_validation->run()== FALSE){
			die($this->parser->parse("admin/config/bpjs",$data));
		}elseif($this->admin_config_model->insert_databpjs($kode)){
			$data['alert_form'] = 'Save data successful...';
			die($this->parser->parse("admin/config/bpjs",$data));
		}else{
			$data['alert_form'] = 'Save data successful...';
			die($this->parser->parse("admin/config/bpjs",$data));
		}

		
	}
	function doupdate(){
		$this->authentication->verify('admin','edit');

		$this->form_validation->set_rules('title', 'Web title', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			die($this->general());
			//redirect(base_url()."index.php/admin_config");
		}elseif($this->admin_config_model->update_entry()){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			die($this->general());
			//redirect(base_url()."index.php/admin_config");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			die($this->general());
			//redirect(base_url()."index.php/admin_config");
		}
	}
	function tab($pageIndex){
		$data = array();
		switch ($pageIndex) {
			case 1:
				$this->bpjs();
				break;
			case 2:
				$this->general();

				break;
		}

	}
	function bpjs()
	{
		$this->authentication->verify('admin','show');

		$data = $this->admin_config_model->get_data_bpjs(); 
		$data['title_group'] = "Admin Panel";
		$data['alert_form'] = '';
		$data['title_form'] = "BPJS Configuration";
		$data['kodepuskesmas'] = $this->admin_config_model->get_data_puskes();
		$data['theme_default_option'] = $this->admin_config_model->get_theme();
		die($this->parser->parse("admin/config/bpjs",$data,true));
	}
	function general()
	{
		$this->authentication->verify('admin','show');

		$data = $this->admin_config_model->get_data(); 
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Web Configuration";
		$data['theme_default_option'] = $this->admin_config_model->get_theme();
		die($this->parser->parse("admin/config/form",$data,true));
	}
}
