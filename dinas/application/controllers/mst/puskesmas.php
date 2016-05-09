<?php
class Puskesmas extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/puskesmas/puskesmas_model');
		$this->load->model('morganisasi_model');

		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
	}
	
	function index()
	{
	$this->authentication->verify('mst','show');
		$data['title_group'] = "Master Data";
		$data['title_form'] = "Puskesmas";

		$this->session->set_userdata('filter_code_provinsi','');
		$this->session->set_userdata('filter_code_kota','');
		$this->session->set_userdata('filter_code_kecamatan','');
		
		$kode_sess = $this->session->userdata("puskesmas");
		$data['dataprovinsi'] = $this->puskesmas_model->get_datawhere($kode_sess,"code","cl_province");
		$data['content'] = $this->parser->parse("mst/puskesmas/show",$data,true);
		$this->template->show($data,'home');
	}

		function data_export(){
    	$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

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
			$this->db->where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
			//where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_phc.code,2,4)',$this->session->userdata('filter_code_kota'));
			//where('cl_phc.code',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_phc.code,2,7)',$this->session->userdata('filter_code_kecamatan'));
			//where('cl_phc.code',$this->session->userdata('filter_code_kecamatan'));
		}
		
		$rows_all = $this->puskesmas_model->get_data_export();

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
			//where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_phc.code,2,4)',$this->session->userdata('filter_code_kota'));
			//where('cl_phc.code',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_phc.code,2,7)',$this->session->userdata('filter_code_kecamatan'));
			//where('cl_phc.code',$this->session->userdata('filter_code_kecamatan'));
		}

		
		$rows = $this->puskesmas_model->get_data_export(/*$this->input->post('recordstartindex'), $this->input->post('pagesize')*/);
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'code'					=> $act->code,
				'value'					=> $act->value,
				'cd_kompemilikbarang'	=> $act->cd_kompemilikbarang,
				'cd_propinsi'			=> $act->cd_propinsi,
				'cd_kabkota'			=> $act->cd_kabkota,
				'cd_bidang'				=> $act->cd_bidang,
				'cd_unitbidang'			=> $act->cd_unitbidang,
				'cd_satuankerja'		=> $act->cd_satuankerja,
				'alamat'				=> $act->alamat,
				'tlp'					=> $act->tlp,
				'edit'					=> 1,
				'hapus'				=> 1,
				'Cek'				=> 1
			);
		}
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		
		if ($this->input->post('provinsi')!='' || $this->input->post('provinsi')!='null') {
			$provinsi = $this->input->post('provinsi');
		}else{
			$provinsi = '-';
		}
		if ($this->input->post('kota')!='' || $this->input->post('kota')!='null') {
			$kota = $this->input->post('kota');
		}else{
			$kota = '-';
		}
		if ($this->input->post('kecamatan')!='' || $this->input->post('kecamatan')!='null') {
			$kota = $this->input->post('kecamatan');
		}else{
			$kota = '-';
		}
		
		$tanggal_export = date("Y-m-d");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/data_puskesmas.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/puskesmas/hasil_ketukpintu_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

		function json(){
	//	$this->authentication->verify('puskesmas','show');
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
			//where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_phc.code,2,4)',$this->session->userdata('filter_code_kota'));
			//where('cl_phc.code',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_phc.code,2,7)',$this->session->userdata('filter_code_kecamatan'));
			//where('cl_phc.code',$this->session->userdata('filter_code_kecamatan'));
		}
		$rows_all = $this->puskesmas_model->get_data();

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
			//query('select mid(code,2,2) from cl_phc',$this->session->userdata('filter_code_provinsi'));
			//where('mid(cl_phc.code,2,2)',$this->session->userdata('filter_code_provinsi'));
		}
		if($this->session->userdata('filter_code_kota') != '') {
			$this->db->where('mid(cl_phc.code,2,4)',$this->session->userdata('filter_code_kota'));
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('mid(cl_phc.code,2,7)',$this->session->userdata('filter_code_kecamatan'));
		}

		$rows = $this->puskesmas_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'					=> $act->code,
				'value'					=> $act->value,
				'cd_kompemilikbarang'	=> $act->cd_kompemilikbarang,
				'cd_propinsi'			=> $act->cd_propinsi,
				'cd_kabkota'			=> $act->cd_kabkota,
				'cd_bidang'				=> $act->cd_bidang,
				'cd_unitbidang'			=> $act->cd_unitbidang,
				'cd_satuankerja'		=> $act->cd_satuankerja,
				'alamat'				=> $act->alamat,
				'tlp'					=> $act->tlp,
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
	function json_puskesmas($kesmas){
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

		$this->db->where("cl_phc.code",$kesmas);
		$rows_all = $this->puskesmas_model->get_data_puskesmas();

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

		$this->db->where("cl_phc.code",$kesmas);
		$rows = $this->puskesmas_model->get_data_puskesmas($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'					=> $act->code,
				'value'					=> $act->value,
				'cd_kompemilikbarang'	=> $act->cd_kompemilikbarang,
				'cd_propinsi'			=> $act->cd_propinsi,
				'cd_kabkota'			=> $act->cd_kabkota,
				'cd_bidang'				=> $act->cd_bidang,
				'cd_unitbidang'			=> $act->cd_unitbidang,
				'cd_satuankerja'		=> $act->cd_satuankerja,
				'alamat'				=> $act->alamat,
				'tlp'					=> $act->tlp,
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

	function dodel($kode=0){
	$this->authentication->verify('mst','del');

		if($this->puskesmas_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."mst/puskesmas");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/puskesmas");
		}
	}

		function dodel_multi(){
		$this->authentication->verify('mst','del');

		if(is_array($this->input->post('code'))){
			foreach($this->input->post('code') as $kode){
				$this->puskesmas_model->delete_entry($kode);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('code')).') data successful...');
			redirect(base_url()."mst/puskesmas");
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
			redirect(base_url()."mst/puskesmas");
		}

		redirect(base_url()."mst/puskesmas");
	}

	function edit($code=""){
        $this->authentication->verify('mst','edit');

        $this->form_validation->set_rules('value', 'Nama', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');      
        $this->form_validation->set_rules('tlp','Tlp','trim|required');

        $data = $this->puskesmas_model->get_kode_phc($code);
		$data['code']			= $code;
		$data['title_group']	= "Master Data";
		$data['title_form']		= "Puskesmas";
		
        $data['content'] = $this->parser->parse("mst/puskesmas/form",$data,true);
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
    	elseif($this->puskesmas_model->update_puskess($code)){
			echo "Data berhasil disimpan";
			
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
    }


	function get_provinsifilter(){
	
	if ($this->input->post('provinsi')!="null") {
		if($this->input->is_ajax_request()) {
			if ($this->session->set_userdata('filter_code_provinsi')!=null) {
				$this->session->set_userdata('filter_code_provinsi','');
			}
			if ($this->session->set_userdata('filter_code_kota')!=null) {
				$this->session->set_userdata('filter_code_kota','');
			}
			if ($this->session->set_userdata('filter_code_kecamatan')!=null) {
				$this->session->set_userdata('filter_code_kecamatan','');
			}
			$provinsi = $this->input->post('provinsi');
			$this->session->set_userdata('filter_code_provinsi',$this->input->post('provinsi'));
			if ($provinsi=="" || empty($provinsi)) {
				echo '<option value="">Pilih Kota/Distric</option>';
			}else{
				$this->db->like('left(code,2)',$provinsi);
				$kode = $this->puskesmas_model->get_datawhere($provinsi,"left(code,4)","cl_district");
				echo '<option value="">Pilih Kota/Distric</option>';
				foreach($kode as $kode) :
					echo $select = $kode->code == $this->session->userdata('filter_code_kota') ? 'selected' : '';
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
				$kode = $this->puskesmas_model->get_datawhere($kota,"left(code,4)","cl_kec");
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
			$this->session->set_userdata('filter_code_kecamatan',$this->input->post('kecamatan'));
		}
		
	}
	}

}