<?php
class Kabupatenkota extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('morganisasi_model');
		$this->load->model('mst/kabupaten_model');
		
	}

	function index(){
		//$this->authentication->verify('mst','edit');
		$data['title_group'] = "MD - Ketuk Pintu";
		$data['title_form'] = "Master Data Kabupaten/Kota";
		$this->session->set_userdata('filter_code_provinsi','');
		$kode_sess = $this->session->userdata("kabupatenkota");

		$data['dataprovinsi'] = $this->kabupaten_model->get_datawhere($kode_sess,"code","cl_province");

		$data['content'] = $this->parser->parse("mst/kabupatenkota/show",$data,true);
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
			$this->db->where('LEFT(cl_district.CODE,2)',$this->session->userdata('filter_code_provinsi'));
		}

		$rows_all = $this->kabupaten_model->get_data();

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
			$this->db->where('LEFT(cl_district.CODE,2)',$this->session->userdata('filter_code_provinsi'));
		}

		$rows = $this->kabupaten_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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

	function json_kabupaten($prov){
		$this->authentication->verify('mst','show');

		$this->db->where("cl_district.code",$prov);

		$rows_all = $this->kabupaten_model->get_data_kabupaten();

		$this->db->where("cl_district.code",$prov);
		$rows = $this->kabupaten_model->get_data_kabupaten($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
		
        $data = $this->kabupaten_model->get_kode_district($code);
		$data['code']			= $code;
		$data['title_group']	= "Master Data";
		$data['title_form']		= "kabupatenkota";
		
        $data['content'] = $this->parser->parse("mst/kabupatenkota/form",$data,true);
        $this->template->show($data,"home");
    }

    function update_kota($code){
    	$this->authentication->verify('mst','edit');    	
    
		$this->load->model('morganisasi_model');

        $this->form_validation->set_rules('value', 'value', 'trim|required');
	

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}
    	elseif($this->kabupaten_model->update_kota($code)){
			echo "Data berhasil disimpan";
			
		}else{
			echo "Penyimpanan data gagal dilakukan";
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

	function dodel($kode=""){
	$this->authentication->verify('mst','del');

		if($this->kabupaten_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."admin_user");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."admin_user");
		}
	}

		function dodel_multi(){
		$this->authentication->verify('mst','del');

		if(is_array($this->input->post('code'))){
			foreach($this->input->post('code') as $kode){
				$this->kabupatenkota_model->delete_entry($kode);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('code')).') data successful...');
			redirect(base_url()."mst/kabupatenkota");
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
			redirect(base_url()."mst/kabupatenkota");
		}

		redirect(base_url()."mst/kabupatenkota");
	}
}