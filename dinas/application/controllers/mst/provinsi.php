<?php
class Provinsi extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('morganisasi_model');
		$this->load->model('mst/provinsi_model');
		
	}

	function index(){
		//$this->authentication->verify('mst','edit');
		$data['title_group'] = "MD - Ketuk Pintu";
		$data['title_form'] = "Master Data Provinsi";
		$this->session->set_userdata('filter_code_provinsi','');
		$kode_sess = $this->session->userdata("puskesmas");

		$data['content'] = $this->parser->parse("mst/provinsi/show",$data,true);
		$this->template->show($data,"home");
	}

	function json(){
		$this->authentication->verify('mst','show');

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
			$this->db->where('LEFT(cl_province.CODE,2)',$this->session->userdata('filter_code_provinsi'));
		}

		$rows_all = $this->provinsi_model->get_data();

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
			$this->db->where('LEFT(cl_province.CODE,2)',$this->session->userdata('filter_code_provinsi'));
		}

		$rows = $this->provinsi_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'		=> $act->code,
				'value'		=> $act->value,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function json_provinsi($prov){
		//$this->authentication->verify('mst','show');

		$this->db->where("cl_province.code",$prov);

		$rows_all = $this->provinsi_model->get_data_provinsi();

		$this->db->where("cl_province.code",$prov);
		$rows = $this->provinsi_model->get_data_provinsi($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'		=> $act->code,
				'value'		=> $act->value,
				'edit'					=> 1,
				'delete'				=> 1,
				'detail'				=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function edit($code=""){
        $this->authentication->verify('mst','edit');

        $this->form_validation->set_rules('value', 'Nama', 'trim|required');
		
        $data = $this->provinsi_model->get_kode_province($code);
		$data['code']			= $code;
		$data['title_group']	= "Master Data";
		$data['title_form']		= "Provinsi";
		
        $data['content'] = $this->parser->parse("mst/provinsi/form",$data,true);
        $this->template->show($data,"home");
    }

    function update_provinsi($code){
    	$this->authentication->verify('mst','edit');    	
    
		$this->load->model('morganisasi_model');

        $this->form_validation->set_rules('value', 'value', 'trim|required');
	

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}
    	elseif($this->provinsi_model->update_provinsi($code)){
			echo "Data berhasil disimpan";
			
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
    }

	function dodel($kode=0){
	$this->authentication->verify('mst','del');

		if($this->provinsi_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."admin_user");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."admin_user");
		}
	}

	function get_provinsifilter(){
	
	if ($this->input->post('provinsi')!="null") {
		if($this->input->is_ajax_request()) {
			$provinsi = $this->input->post('provinsi');
			$this->session->set_userdata('filter_code_provinsi',$this->input->post('provinsi'));

			return FALSE;
		}

		show_404();
	}
	}
}