<?php
class Desa extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/desa_model');

		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
	}
	
	function index()
	{
	$this->authentication->verify('mst','show');
		$data['title_group'] = "Master Data";
		$data['title_form'] = "Desa";

		$this->session->set_userdata('filter_code_desa','');
		$this->session->set_userdata('filter_code_kota','');
		$this->session->set_userdata('filter_code_kecamatan','');
		
		$kode_sess = $this->session->userdata("desa");
		$data['datadesa'] = $this->desa_model->get_datawhere($kode_sess,"code","cl_province");
		$data['content'] = $this->parser->parse("mst/desa/show",$data,true);
		$this->template->show($data,'home');
	}

		function json(){
	//	$this->authentication->verify('desa','show');
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
		
		
		if($this->session->userdata('filter_code_desa') != '') {
			$this->db->where('mid(cl_village.code,1,2)',$this->session->userdata('filter_code_desa'));
			//where('mid(cl_village.code,2,2)',$this->session->userdata('filter_code_desa'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_village.code,1,4)',$this->session->userdata('filter_code_kota'));
			//where('cl_village.code',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_village.code,1,7)',$this->session->userdata('filter_code_kecamatan'));
			//where('cl_village.code',$this->session->userdata('filter_code_kecamatan'));
		}
		$rows_all = $this->desa_model->get_data();

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
		
		if($this->session->userdata('filter_code_desa') != '') {
			$this->db->where('mid(cl_village.code,1,2)',$this->session->userdata('filter_code_desa'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_village.code,1,4)',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_village.code,1,7)',$this->session->userdata('filter_code_kecamatan'));
		}

		$rows = $this->desa_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'					=> $act->code,
				'value'					=> $act->value,
				'edit'					=> 1,
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
	function json_desa($des){
	//	$this->authentication->verify('eform','show');
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

		$this->db->where("cl_village.code",$des);
		$rows_all = $this->desa_model->get_data_pusdes();

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

		$this->db->where("cl_village.code",$des);
		$rows = $this->desa_model->get_data_des($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'					=> $act->code,
				'value'					=> $act->value,
				'edit'					=> 1,
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

	function get_desafilter(){
	
	if ($this->input->post('desa')!="null") {
		if($this->input->is_ajax_request()) {
			if ($this->session->set_userdata('filter_code_desa')!=null) {
				$this->session->set_userdata('filter_code_desa','');
			}
			if ($this->session->set_userdata('filter_code_kota')!=null) {
				$this->session->set_userdata('filter_code_kota','');
			}
			if ($this->session->set_userdata('filter_code_kecamatan')!=null) {
				$this->session->set_userdata('filter_code_kecamatan','');
			}
			$desa = $this->input->post('desa');
			$this->session->set_userdata('filter_code_desa',$this->input->post('desa'));
			if ($desa=="" || empty($desa)) {
				echo '<option value="">Pilih Kota/Distric</option>';
			}else{
				$this->db->like('left(code,2)',$desa);
				$kode = $this->desa_model->get_datawhere($desa,"left(code,2)","cl_district");
				echo '<option value="">Pilih Kota/Distric</option>';
				foreach($kode as $kode) :
					echo $select = $kode->code == $this->session->userdata('filter_code_desa') ? 'selected' : '';
					echo '<option value="'.$kode->code.'" '.$select.'>' . $kode->value . '</option>';
				endforeach;
				}

			return FALSE;
			}

		show_404();
		}
	}
	function get_kotafilter(){
	if ($this->input->post('kota')!="null") {
		if($this->input->is_ajax_request()) {
				if ($this->session->set_userdata('filter_code_kota')!=null) {
					$this->session->set_userdata('filter_code_kota','');
				}
				if ($this->session->set_userdata('filter_code_kecamatan')!=null) {
					$this->session->set_userdata('filter_code_kecamatan','');
				}
			$kota = $this->input->post('kota');
			$this->session->set_userdata('filter_code_kota',$this->input->post('kota'));
			if ($kota=='' || empty($kota)) {
				echo '<option value="">Pilih Kecamatan</option>';
			}else{
				$this->db->like('code',$kota);
				$kode = $this->desa_model->get_datawhere($kota,"left(code,4)","cl_kec");
				echo '<option value="">Pilih Kecamatan</option>';
				foreach($kode as $kode) :
					echo $select = $kode->code == $this->session->userdata('filter_code_kota') ? 'selected' : '';
					echo '<option value="'.$kode->code.'" '.$select.'>' . $kode->nama. '</option>';
				endforeach;
			}

			return FALSE;
		}

		show_404();
	}
	}
	function get_kecamatanfilter(){
	if ($this->input->post('kecamatan')!="null" || $this->input->post('kota')!="null") {	
		if($this->input->is_ajax_request()) {
			/*$rukunwarga = $this->input->post('rukunwarga');
			$kelurahan = $this->input->post('kelurahan');*/
			$this->session->set_userdata('filter_code_kecamatan',$this->input->post('kecamatan'));
		}
	}
	}


	function edit($code=""){
        $this->authentication->verify('mst','edit');

        $this->form_validation->set_rules('value', 'Nama', 'trim|required');
		
        $data = $this->desa_model->get_kode_village($code);
		$data['code']			= $code;
		$data['title_group']	= "Master Data";
		$data['title_form']		= "desa";
		
        $data['content'] = $this->parser->parse("mst/desa/form",$data,true);
        $this->template->show($data,"home");
    }

    function update_desa($code){
    	$this->authentication->verify('mst','edit');    	
    
		$this->load->model('morganisasi_model');

        $this->form_validation->set_rules('value', 'value', 'trim|required');
	

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}
    	elseif($this->desa_model->update_desa($code)){
			echo "Data berhasil disimpan";
			
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
    }
	
		function dodel($kode=0){
	$this->authentication->verify('mst','del');

		if($this->desa_model->delete_entry($kode)){
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
				$this->desa_model->delete_entry($kode);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('code')).') data successful...');
			redirect(base_url()."mst/desa");
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
			redirect(base_url()."mst/desa");
		}

		redirect(base_url()."mst/desa");
	}

}