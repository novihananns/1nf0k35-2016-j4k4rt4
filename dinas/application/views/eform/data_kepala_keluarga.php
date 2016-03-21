<?php
class Data_kepala_keluarga extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');



		$this->load->model('eform/datakeluarga_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('eform/pembangunan_keluarga_model');
		$this->load->model('eform/anggota_keluarga_kb_model');
		$this->load->model('eform/dataform_model');
	}
    function datakepalakeluaraexport(){
    	$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		$this->authentication->verify('eform','show');

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
		
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}else if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
	/*	if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}*/
		$rows_all = $this->datakeluarga_model->get_data_export();

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
		
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}else if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
	/*	if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}
*/
		$rows = $this->datakeluarga_model->get_data_export(/*$this->input->post('recordstartindex'), $this->input->post('pagesize')*/);
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'tanggal_pengisian'		=> $act->tanggal_pengisian,
				'jam_data'				=> $act->jam_data,
				'alamat'				=> $act->alamat,
				'id_propinsi'			=> $act->id_propinsi,
				'id_kota'				=> $act->id_kota,
				'id_kecamatan'			=> $act->id_kecamatan,
				'value'					=> $act->value,
				'rt'					=> $act->rt,
				'rw'					=> $act->rw,
				'norumah'				=> $act->norumah,
				'nourutkel'				=> $act->nourutkel,
				'id_kodepos'			=> $act->id_kodepos,
				'namakepalakeluarga'	=> $act->namakepalakeluarga,
				'notlp'					=> $act->notlp,
				'namadesawisma'			=> $act->namadesawisma,
				'id_pkk'				=> $act->id_pkk,
				'nama_komunitas'		=> $act->nama_komunitas,
				'laki'					=> $act->laki,
				'pr'					=> $act->pr,
				'jmljiwa'				=> $act->jmljiwa,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}

				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$nama  = $this->inv_barang_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));

		if ($this->input->post('kecamatan')!='' || $this->input->post('kecamatan')!='null') {
			$kecamatan = $this->input->post('kecamatan');
		}else{
			$kecamatan = '-';
		}
		if ($this->input->post('kelurahan')!='' || $this->input->post('kelurahan')!='null') {
			$kelurahan = $this->input->post('kelurahan');
		}else{
			$kelurahan = '-';
		}
		if ($this->input->post('rukunwarga')!='' || $this->input->post('rukunwarga')!='null') {
			$rukunwarga = $this->input->post('rukunwarga');
		}else{
			$rukunwarga = '-';
		}
		/*if ($this->input->post('rukunrumahtangga')!='' || $this->input->post('rukunrumahtangga')!='null') {
			$rukunrumahtangga = $this->input->post('rukunrumahtangga');
		}else{
			$rukunrumahtangga = '-';
		}
			'rt' => $rukunrumahtangga
		*/
		$tanggal_export = date("Y-m-d");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'rw' => $rukunwarga);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/data_kepala_keluarga.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_ketokpintu_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	function json(){
		$this->authentication->verify('eform','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				if ($field=="tanggal_pengisian") {
					$this->db->like("tanggal_pengisian",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
				
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}else if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
	/*	if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}*/
		$rows_all = $this->datakeluarga_model->get_data();

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if ($field=="tanggal_pengisian") {
					$this->db->like("tanggal_pengisian",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		if($this->session->userdata('filter_code_kelurahan') != '') {
			$this->db->where('data_keluarga.id_desa',$this->session->userdata('filter_code_kelurahan'));
		}else if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		/*if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}*/

		$rows = $this->datakeluarga_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'tanggal_pengisian'		=> $act->tanggal_pengisian,
				'jam_data'				=> $act->jam_data,
				'alamat'				=> $act->alamat,
				'id_propinsi'			=> $act->id_propinsi,
				'id_kota'				=> $act->id_kota,
				'id_kecamatan'			=> $act->id_kecamatan,
				'value'					=> $act->value,
				'rt'					=> $act->rt,
				'rw'					=> $act->rw,
				'norumah'				=> $act->norumah,
				'nourutkel'				=> $act->nourutkel,
				'id_kodepos'			=> $act->id_kodepos,
				'namakepalakeluarga'	=> $act->namakepalakeluarga,
				'notlp'					=> $act->notlp,
				'namadesawisma'			=> $act->namadesawisma,
				'id_pkk'				=> $act->id_pkk,
				'nama_komunitas'		=> $act->nama_komunitas,
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
	function json_anggotaKeluarga($anggota){
		$this->authentication->verify('eform','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				if($field=="tgl_lahir"){
					$this->db->like("tgl_lahir",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
				
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where("data_keluarga_anggota.id_data_keluarga",$anggota);
		$rows_all = $this->datakeluarga_model->get_data_anggotaKeluarga();

    	if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field=="tgl_lahir"){
					$this->db->like("tgl_lahir",date("Y-m-d",strtotime($value)));
				}else{
					$this->db->like($field,$value);	
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where("data_keluarga_anggota.id_data_keluarga",$anggota);
		$rows = $this->datakeluarga_model->get_data_anggotaKeluarga($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'no_anggota'			=> $act->no_anggota,
				'nama'					=> $act->nama,
				'nik'					=> $act->nik,
				'tmpt_lahir'			=> $act->tmpt_lahir,
				'tgl_lahir'				=> $act->tgl_lahir,
				'id_pilihan_hubungan'	=> $act->id_pilihan_hubungan,
				'id_pilihan_kelamin'	=> $act->id_pilihan_kelamin,
				'id_pilihan_agama'		=> $act->id_pilihan_agama,
				'id_pilihan_pendidikan'	=> $act->id_pilihan_pendidikan,
				'id_pilihan_pekerjaan'	=> $act->id_pilihan_pekerjaan,
				'id_pilihan_kawin'		=> $act->id_pilihan_kawin,
				'id_pilihan_jkn'		=> $act->id_pilihan_jkn,
				'jeniskelamin'			=> $act->jeniskelamin,
				'hubungan'				=> $act->hubungan,
				'usia'					=> $act->usia,
				'suku'					=> $act->suku,
				'no_hp'					=> $act->no_hp,
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

	function index(){
		$this->authentication->verify('eform','edit');
		$data['title_group'] = "eForm - Ketuk Pintu";
		$data['title_form'] = "Data Kepala Keluarga";
		$this->session->set_userdata('filter_code_kecamatan','');
		$this->session->set_userdata('filter_code_kelurahan','');
		$this->session->set_userdata('filter_code_rukunwarga','');
		$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
		$kode_sess = $this->session->userdata("puskesmas");
		$data['datakecamatan'] = $this->datakeluarga_model->get_datawhere(substr($kode_sess, 0,7),"code","cl_kec");
		$data['content'] = $this->parser->parse("eform/datakeluarga/show",$data,true);
		$this->template->show($data,"home");
	}
	function adddataform_profile(){
		 $this->dataform_model->insertdataform_profile();
	}

	function add(){
		$this->authentication->verify('eform','add');

        $this->form_validation->set_rules('tgl_pengisian', 'Tanggal Pengisian', 'trim|required');
        $this->form_validation->set_rules('jam_data', 'Jam Pendataan', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('dusun', 'Dusun / RW', 'trim|required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|required');
        $this->form_validation->set_rules('norumah', 'No Rumah', 'trim|required');
        $this->form_validation->set_rules('namakomunitas', 'Nama Komunitas', 'trim|required');
        $this->form_validation->set_rules('namakepalakeluarga', 'Nama Kepala Keluarga', 'trim|required');
        $this->form_validation->set_rules('notlp', 'No. HP / Telepon', 'trim|required');
        $this->form_validation->set_rules('namadesawisma', 'Nama Desa Wisma', 'trim|required');
        $this->form_validation->set_rules('jabatanstuktural', '', 'trim');
        $this->form_validation->set_rules('kelurahan', '', 'trim');
        $this->form_validation->set_rules('kodepos', '', 'trim');
        
		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "eForm - Ketuk Pintu";
			$data['title_form']="Tambah Data Keluarga";
			$data['action']="add";
			$data['id_data_keluarga']="";
          	$data['data_provinsi'] = $this->datakeluarga_model->get_provinsi();
          	$data['data_kotakab'] = $this->datakeluarga_model->get_kotakab();
          	$data['data_kecamatan'] = $this->datakeluarga_model->get_kecamatan();
          	$data['data_desa'] = $this->datakeluarga_model->get_desa();
          	$data['data_pos'] = $this->datakeluarga_model->get_pos();
          	$data['data_pkk'] = $this->datakeluarga_model->get_pkk();

			$data['content'] = $this->parser->parse("eform/datakeluarga/form",$data,true);
			$this->template->show($data,"home");
		}elseif($id = $this->datakeluarga_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'eform/data_kepala_keluarga/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}

	}
    
	function addtable(){
		 $this->datakeluarga_model->insertDataTable();
	}
    
	function edit($id_data_keluarga=0){
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('dusun', 'Dusun / RW', 'trim|required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|required');
        $this->form_validation->set_rules('norumah', 'No Rumah', 'trim|required');
        $this->form_validation->set_rules('namakomunitas', 'Nama Komunitas', 'trim|required');
        $this->form_validation->set_rules('namakepalakeluarga', 'Nama Kepala Keluarga', 'trim|required');
        $this->form_validation->set_rules('notlp', 'No. HP / Telepon', 'trim|required');
        $this->form_validation->set_rules('namadesawisma', 'Nama Desa Wisma', 'trim|required');
        $this->form_validation->set_rules('jabatanstuktural', '', 'trim');
        $this->form_validation->set_rules('kelurahan', '', 'trim');
        $this->form_validation->set_rules('kodepos', '', 'trim');
        $this->form_validation->set_rules('nama_koordinator', '', 'trim');
        $this->form_validation->set_rules('nama_pendata', '', 'trim');
        $this->form_validation->set_rules('jam_selesai', '', 'trim');

		if($this->form_validation->run()== FALSE){
			$data = $this->datakeluarga_model->get_data_row($id_data_keluarga); 

			$data['title_group'] = "eForm - Ketuk Pintu";
			$data['title_form']="Ubah Data Keluarga";
			$data['action']="edit";
			$data['id_data_keluarga'] = $id_data_keluarga;
          	$data['data_provinsi'] = $this->datakeluarga_model->get_provinsi();
          	$data['data_kotakab'] = $this->datakeluarga_model->get_kotakab();
          	$data['data_kecamatan'] = $this->datakeluarga_model->get_kecamatan();
          	$data['data_desa'] = $this->datakeluarga_model->get_desa();
          	$data['data_pos'] = $this->datakeluarga_model->get_pos();
          	$data['data_pkk'] = $this->datakeluarga_model->get_pkk();
            $data['jabatan_pkk'] = $this->datakeluarga_model->get_pkk_value($data['id_pkk']);

			$data['data_profile']  = $this->datakeluarga_model->get_data_profile($id_data_keluarga); 
            //$data['data_print'] = $this->parser->parse("eform/datakeluarga/print", $data, true);

			$data['content'] = $this->parser->parse("eform/datakeluarga/form_detail",$data,true);
			$this->template->show($data,"home");
		}elseif($this->datakeluarga_model->update_entry($id_data_keluarga)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."eform/data_kepala_keluarga/edit/".$id_data_keluarga);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/data_kepala_keluarga/edit/".$id_data_keluarga);
		}
	}

	function tab($pageIndex,$id_data_keluarga){
		$data = array();
		$data['id_data_keluarga']=$id_data_keluarga;

		switch ($pageIndex) {
			case 1:
				$this->profile($id_data_keluarga);

				break;
			case 2:
				$this->anggota($id_data_keluarga);

				break;
			case 3:
				$this->kb($id_data_keluarga);

				break;
			default:
				$this->pembangunan($id_data_keluarga);
				break;
		}

	}

	function dodel($kode=0){
		$this->authentication->verify('eform','del');

		if($this->datakeluarga_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}
	}
	function anggota_dodel($idkeluarga=0,$noanggota=0){
		$this->authentication->verify('eform','del');

		if($this->datakeluarga_model->delete_Anggotakeluarga($idkeluarga,$noanggota)){
			$data['alert_form'] = 'Delete data ('.$kode.')';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}else{
			$data['alert_form'] = 'Delete data error';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}
	}

	function anggota($kode=0)
	{
		$this->authentication->verify('eform','edit');

		$data['action']="edit";
		$data['id_data_keluarga'] = $kode;

		die($this->parser->parse("eform/datakeluarga/form_anggota",$data));
	}
	
	function anggota_add($kode=0)
	{
		$this->authentication->verify('eform','edit');

		$this->form_validation->set_rules('nik', 'NIK ', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tmpt_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('suku', 'Suku', 'trim|required');
        $this->form_validation->set_rules('no_hp', 'No HP', 'trim|required');

        $data['action']="add";
		$data['id_data_keluarga'] = $kode;
		$data['alert_form'] = "";

        $data['data_pilihan_hubungan'] = $this->datakeluarga_model->get_pilihan("hubungan");
      	$data['data_pilihan_kelamin'] = $this->datakeluarga_model->get_pilihan("jk");
      	$data['data_pilihan_agama'] = $this->datakeluarga_model->get_pilihan("agama");
      	$data['data_pilihan_pendidikan'] = $this->datakeluarga_model->get_pilihan("pendidikan");
      	$data['data_pilihan_pekerjaan'] = $this->datakeluarga_model->get_pilihan("pekerjaan");
      	$data['data_pilihan_kawin'] = $this->datakeluarga_model->get_pilihan("kawin");
      	$data['data_pilihan_jkn'] = $this->datakeluarga_model->get_pilihan("jkn");

      	$data['alert_form'] = '';

        if($this->form_validation->run()== FALSE){
			die($this->parser->parse("eform/datakeluarga/form_anggota_add",$data));
		}elseif($noanggota=$this->datakeluarga_model->insert_dataAnggotaKeluarga($kode)){
			$this->anggota_edit($this->input->post('id_data_keluarga'),$noanggota);
		}else{
			$data['alert_form'] = 'Save data failed...';
			die($this->parser->parse("eform/datakeluarga/form_anggota_add",$data));
		}

		
	}
	public function addanggotaprofile()
	 {
	 	$this->datakeluarga_model->addanggotaprofile();
	 } 
	function anggota_edit($idkeluarga=0,$noanggota=0)
	{
		$this->authentication->verify('eform','edit');
		$data = $this->datakeluarga_model->get_data_row_anggota($idkeluarga,$noanggota);
		
        $data['action']="edit";
		$data['id_data_keluarga'] = $idkeluarga;
		$data['noanggota'] = $noanggota;
		$data['alert_form'] = "";

        $data['data_pilihan_hubungan'] = $this->datakeluarga_model->get_pilihan("hubungan");
      	$data['data_pilihan_kelamin'] = $this->datakeluarga_model->get_pilihan("jk");
      	$data['data_pilihan_agama'] = $this->datakeluarga_model->get_pilihan("agama");
      	$data['data_pilihan_pendidikan'] = $this->datakeluarga_model->get_pilihan("pendidikan");
      	$data['data_pilihan_pekerjaan'] = $this->datakeluarga_model->get_pilihan("pekerjaan");
      	$data['data_pilihan_kawin'] = $this->datakeluarga_model->get_pilihan("kawin");
      	$data['data_pilihan_jkn'] = $this->datakeluarga_model->get_pilihan("jkn");

      	$data['alert_form'] = '';

       $data['data_profile_anggota'] = $this->datakeluarga_model->get_data_anggotaprofile($idkeluarga,$noanggota);
		die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		/*}elseif($noanggota=$this->datakeluarga_model->insert_dataAnggotaKeluarga($idkeluarga)){
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}else{
			$data['alert_form'] = 'Save data failed...';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}*/
			

			
		//}
	}
	function update_kepala(){
		$this->datakeluarga_model->update_kepala();
	}
	
	function profile($kode=0)
	{
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('xx', '', 'trim|required');

		if($this->form_validation->run()== FALSE){
			//$data = $this->anggota_keluarga_kb_model->get_data_row($kode); 

			$data['action']="edit";
			$data['id_data_keluarga'] = $kode;
			//$data['data_keluarga_kb']  = $this->anggota_keluarga_kb_model->get_data_profile($kode); 
			$data['alert_form'] = "";
		
		/*}elseif($this->anggota_keluarga_kb_model->update_entry($kode)){
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data successful...';*/
		}
		$data['data_formprofile']  = $this->dataform_model->get_data_formprofile($kode); 
		die($this->parser->parse("eform/datakeluarga/form_profile",$data));
	}

	function kb($kode=0)
	{
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('xx', '', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->anggota_keluarga_kb_model->get_data_row($kode); 

			$data['action']="edit";
			$data['id_data_keluarga'] = $kode;
			$data['data_keluarga_kb']  = $this->anggota_keluarga_kb_model->get_data_keluargaberencana($kode); 
			$data['alert_form'] = "";
		
		}elseif($this->anggota_keluarga_kb_model->update_entry($kode)){
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data successful...';
		}
		die($this->parser->parse("eform/datakeluarga/form_kb",$data));
	}
	public function addkeluargaberencana()
	{
		$this->anggota_keluarga_kb_model->insertDataKeluargaBerencana();
	}
	function pembangunan($kode=0)
	{
		$this->authentication->verify('eform','edit');

        $this->form_validation->set_rules('xx', '', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->pembangunan_keluarga_model->get_data_row($kode); 

			$data['action']="edit";
			$data['id_data_keluarga'] = $kode;
			$data['data_pembangunan']  = $this->pembangunan_keluarga_model->get_data_pembangunan ($kode); 
			$data['alert_form'] = "";

		}elseif($this->pembangunan_keluarga_model->update_entry($kode)){
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data successful...';
		}

		die($this->parser->parse("eform/datakeluarga/form_pembangunan",$data));
	}
	function addpembangunan(){
		$this->pembangunan_keluarga_model->insertdatatable_pembangunan();
	}
	function get_kecamatanfilter(){
	
	if ($this->input->post('kecamatan')!="null") {
		if($this->input->is_ajax_request()) {
			$kecamatan = $this->input->post('kecamatan');
			$this->session->set_userdata('filter_code_kecamatan',$this->input->post('kecamatan'));
			$kode 	= $this->datakeluarga_model->get_datawhere($kecamatan,"code","cl_village");

				echo '<option value="">Pilih Keluarahan</option>';
			foreach($kode as $kode) :
				echo $select = $kode->code == set_value('kelurahan') ? 'selected' : '';
				echo '<option value="'.$kode->code.'" '.$select.'>' . $kode->value . '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	}
	function get_kelurahanfilter(){
	if ($this->input->post('kelurahan')!="null") {
		if($this->input->is_ajax_request()) {
			$kelurahan = $this->input->post('kelurahan');
			$this->session->set_userdata('filter_code_kelurahan',$this->input->post('kelurahan'));
			$kode 	= $this->datakeluarga_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

				echo '<option value="">Pilih Keluarahan</option>';
			foreach($kode as $kode) :
				echo $select = $kode->rw == set_value('rukuwarga') ? 'selected' : '';
				echo '<option value="'.$kode->rw.'" '.$select.'>' . $kode->rw . '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	}
	function get_rukunwargafilter(){
	if ($this->input->post('rukunwarga')!="null" || $this->input->post('kelurahan')!="null") {	
		if($this->input->is_ajax_request()) {
			/*$rukunwarga = $this->input->post('rukunwarga');
			$kelurahan = $this->input->post('kelurahan');*/
			$this->session->set_userdata('filter_code_rukunwarga',$this->input->post('rukunwarga'));
/*
			$this->db->where("id_desa",$kelurahan);
			$kode 	= $this->datakeluarga_model->get_datawhere($rukunwarga,"rw","data_keluarga");

				echo '<option value="">Pilih Keluarahan</option>';
			foreach($kode as $kode) :
				echo $select = $kode->code == set_value('rukunrumahtangga') ? 'selected' : '';
				echo '<option value="'.$kode->rt.'" '.$select.'>' . $kode->rt . '</option>';
			endforeach;

			return FALSE;*/
		}
		

		//show_404();
	}
	}
	/*function get_rukunrumahtanggafilter(){
	if ($this->input->post('rukunrumahtangga')!="null") {
		if($_POST) {
			if($this->input->post('rukunrumahtangga') != '') {
				$this->session->set_userdata('filter_code_cl_rukunrumahtangga',$this->input->post('rukunrumahtangga'));
			}
		}
	}
	}*/
}
