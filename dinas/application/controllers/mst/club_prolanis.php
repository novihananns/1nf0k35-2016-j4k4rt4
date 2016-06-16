<?php
class Club_prolanis extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('mst/clubprolanis/clubprolanis_model');
		$this->load->model('bpjs');
	}
	
	function index(){
		$this->authentication->verify('mst','show');
		$data['title_group'] = "Master Data";
		$data['title_form'] = "Club Prolanis";

		$this->session->set_userdata('filter_code_puskesmas','');
		
		$kode_sess = $this->session->userdata("puskesmas");
		$data['datapuskesmas'] = $this->clubprolanis_model->get_puskesmas();
		$data['content'] = $this->parser->parse("mst/clubprolanis/show",$data,true);
		$this->template->show($data,'home');
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
		
		if($this->session->userdata('filter_code_puskesmas') != '') {
			$this->db->where('provider',$this->session->userdata('filter_code_puskesmas'));
		}
		$rows_all = $this->clubprolanis_model->get_data();

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
		
		if($this->session->userdata('filter_code_puskesmas') != '') {
			$this->db->where('provider',$this->session->userdata('filter_code_puskesmas'));
		}

		$rows = $this->clubprolanis_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'clubId'		=> $act->clubId,
				'kdProgram'		=> $act->kdProgram,
				'alamat'		=> $act->alamat,
				'nama'			=> $act->nama,
				'ketua_noHP'	=> $act->ketua_noHP,
				'ketua_nama'	=> $act->ketua_nama,
				'provider'		=> $act->provider,
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function dodel($kode=0){
		$this->authentication->verify('mst','del');

		if($this->clubprolanis_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."mst/clubprolanis");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/clubprolanis");
		}
	}

	function dodel_multi(){
		$this->authentication->verify('mst','del');

		if(is_array($this->input->post('code'))){
			foreach($this->input->post('code') as $kode){
				$this->clubprolanis_model->delete_entry($kode);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('code')).') data successful...');
			redirect(base_url()."mst/clubprolanis");
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
			redirect(base_url()."mst/clubprolanis");
		}

		redirect(base_url()."mst/clubprolanis");
	}

	function edit($code=""){
        $this->authentication->verify('mst','edit');

        $this->form_validation->set_rules('value', 'Nama', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');      
        $this->form_validation->set_rules('tlp','Tlp','trim|required');

        $data = $this->clubprolanis_model->get_kode_phc($code);
		$data['code']			= $code;
		$data['title_group']	= "Master Data";
		$data['title_form']		= "Club Prolanis";
		
        $data['content'] = $this->parser->parse("mst/clubprolanis/form",$data,true);
        $this->template->show($data,"home");
    }

    function update_puskes($code){
    	$this->authentication->verify('mst','edit');    	
    
		$this->load->model('morganisasi_model');
        $this->form_validation->set_rules('value', 'value', 'trim|required');
		$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');      
        $this->form_validation->set_rules('tlp','tlp','trim|required');
	    $this->form_validation->set_rules('code', 'code', 'trim');

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}
    	elseif($this->clubprolanis_model->update_puskess($code)){
			echo "Data berhasil disimpan";
			
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
    }

    function bpjs_club(){
    	$i=0;
    	$data = array();
    	$club = array();
		$puskesmas = $this->clubprolanis_model->get_puskesmas();
		foreach($puskesmas as $kode) :
			$dt = array();
			$bpjs = $this->bpjs->bpjs_club($kode->code);
			foreach ($bpjs as $jenis=>$dtj){
				$provider 	= $kode->code;
				$kdProgram	= $jenis;
				if(is_array($dtj)){
					foreach ($dtj as $val){
						$i++;
						$dt['clubId'] 		= $val['clubId'];
						$dt['kdProgram']	= $kdProgram;
						$dt['tglMulai'] 	= $val['tglMulai']!="" ? date("Y-m-d",strtotime($val['tglMulai'])) : "";
						$dt['tglAkhir'] 	= $val['tglAkhir']!="" ? date("Y-m-d",strtotime($val['tglAkhir'])) : "";
						$dt['alamat'] 		= $val['alamat'];
						$dt['nama'] 		= $val['nama'];
						$dt['ketua_noHP']	= $val['ketua_noHP'];
						$dt['ketua_nama']	= $val['ketua_nama'];
						$dt['provider']		= $provider;

						$this->clubprolanis_model->insertClub($dt);
					}
				}
			}
		endforeach;

		echo $i." Club Prolanis Synced";
    }

    function get_puskesmas(){
    	$this->authentication->verify('mst','show');    	
		$kode = $this->clubprolanis_model->get_puskesmas();
		echo '<option value="">Pilih Puskesmas</option>';
		foreach($kode as $kode) :
			echo $select = $kode->code == $this->session->userdata('filter_code_puskesmas') ? 'selected' : '';
			echo '<option value="'.$kode->code.'" '.$select.'>' . $kode->value. '</option>';
		endforeach;

    }

	function get_puskesmasfilter(){
    	$this->authentication->verify('mst','show');    	
		if ($this->input->post('puskesmas')!="null") {	
			if($this->input->is_ajax_request()) {
				$this->session->set_userdata('filter_code_puskesmas',$this->input->post('puskesmas'));
			}else{
				$this->session->unset_userdata('filter_code_puskesmas');
			}
		}else{
			$this->session->unset_userdata('filter_code_puskesmas');
		}
	}

}