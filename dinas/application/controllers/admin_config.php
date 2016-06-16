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

		$data = array();
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "BPJS Configuration";

		$data['kode_prov'] = substr($this->session->userdata("puskesmas"), 0,2);
		$data['kode_kota'] = substr($this->session->userdata("puskesmas"), 0,4);
		$this->session->set_userdata('filter_code_provinsi',$data['kode_prov']);
		$this->session->set_userdata('filter_code_kota',$data['kode_kota']);

		$data['dataprovinsi'] = $this->admin_config_model->get_datawhere($data['kode_prov'],"code","cl_province");

		$data['content'] = $this->parser->parse("admin/config/form",$data,true);

		$this->template->show($data,"home");
	}

	function json(){
		$this->authentication->verify('admin','show');
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				
				$this->db->like($field,$value);	
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		
		if($this->session->userdata('filter_code_provinsi') != '') {
			$this->db->where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_phc.code,2,4)',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_phc.code,2,7)',$this->session->userdata('filter_code_kecamatan'));
		}
		$rows_all = $this->admin_config_model->get_data();

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like($field,$value);	
				
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		if($this->session->userdata('filter_code_provinsi') != '') {
			$this->db->where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_phc.code,2,4)',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_phc.code,2,7)',$this->session->userdata('filter_code_kecamatan'));
		}

		$rows = $this->admin_config_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'				=> $act->code,
				'value'				=> $act->value,
				'server'			=> $act->server!="" ? 1:0,
				'username'			=> $act->username!="" ? 1:0,
				'password'			=> $act->password!="" ? 1:0,
				'consid'			=> $act->consid!="" ? 1:0,
				'secretkey'			=> $act->secretkey!="" ? 1:0,
				'edit'				=> 1,
				'hapus'				=> 1,
				'Cek'				=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function checkBPJS($code=""){
		$data = $this->admin_config_model->checkBPJS($code); 

		echo json_encode($data);
	}
	function detailbpjs($kode=0)
	{
		$data['action']			= "add";
		$data 					= $this->admin_config_model->get_detail($kode);
		$data['namapuskes']		= $this->admin_config_model->get_nama($kode,'value');
		die($this->parser->parse('admin/config/form_detail_bpjs', $data));
	}
}
