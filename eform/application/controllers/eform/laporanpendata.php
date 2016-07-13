<?php
class Laporanpendata extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('bpjs');
		$this->load->model('morganisasi_model');
		$this->load->model('eform/laporanpendata_model');
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
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		// if($this->session->userdata('filter_code_rukunwarga') != '') {
		// 	$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		// }
		// if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
		// 	$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		// }
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows_all = $this->laporanpendata_model->get_data_export();

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
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		// if($this->session->userdata('filter_code_rukunwarga') != '') {
		// 	$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		// }
		// if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
		// 	$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		// }
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows = $this->laporanpendata_model->get_data_export(/*$this->input->post('recordstartindex'), $this->input->post('pagesize')*/);
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
				$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));

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
		if ($this->input->post('rukunrumahtangga')!='' || $this->input->post('rukunrumahtangga')!='null') {
			$rukunrumahtangga = ' / RT '.$this->input->post('rukunrumahtangga');
		}else{
			$rukunrumahtangga = '-';
		}
		if ($this->input->post('tahunfilter')!='' || $this->input->post('tahunfilter')!='null') {
			$tahunfilter = $this->input->post('tahunfilter');
		}else{
			$tahunfilter = date("Y");
		}
		if ($this->input->post('bulanfilter')!='' || $this->input->post('bulanfilter')!='null') {
			$bulanfilter = $this->input->post('bulanfilter');
		}else{
			$bulanfilter = date("M");
		}
		$tanggal_export = date("d-m-Y");
		$kodekecamatan 	= $this->input->post('kodekecamatan');
		$kodedesa 	   	= $this->input->post('kodedesa');
		$koderw 		= $this->input->post('koderw');
		$kodert 		= $this->input->post('kodert');
		$kodetahun 		= $this->input->post('kodetahun');
		$kodebulan 		= $this->input->post('kodebulan');
		if ($kodekecamatan!='null' && $kodekecamatan !='' && isset($kodekecamatan)) {
			$this->db->where('data_keluarga.id_kecamatan',$kodekecamatan);
		}
		if ($kodedesa != 'null' && $kodedesa !='' && isset($kodedesa)) {
			$this->db->where('data_keluarga.id_desa',$kodedesa);
		}
		if ($koderw != 'null' && $koderw !='' && isset($koderw)) {
			$this->db->where('data_keluarga.rw',$koderw);
		}
		if ($kodert != 'null' && $kodert !='' && isset($kodert)) {
			$this->db->where('data_keluarga.rt',$kodert);
		}
		if ($kodetahun != 'null' && $kodetahun !='' && isset($kodetahun)) {
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$kodetahun);	
		}
		if ($kodebulan != 'null' && $kodebulan !='' && isset($kodebulan)) {
			$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$kodebulan);
		}
		$datajml = $this->laporanpendata_model->datajml();

		$jumlahjiwa = $datajml['jml_jiwa'];
		$jumlahlaki = $datajml['jml_laki'];
		$jumlahkk = $datajml['jml_kk'];
		$jumlahperempuan = $datajml['jml_perempuan'];
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'rw' => $rukunwarga,'rt' => $rukunrumahtangga,'tahunfilter' => $tahunfilter,'bulanfilter' => $bulanfilter,'jumlahjiwa' => $jumlahjiwa,'jumlahlaki' => $jumlahlaki,'jumlahperempuan' => $jumlahperempuan,'jumlahkk' => $jumlahkk);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/data_kepala_keluarga.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_ketukpintu_'.$code.'.xlsx';
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
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		// if($this->session->userdata('filter_code_rukunwarga') != '') {
		// 	$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		// }
		// if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
		// 	$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		// }

		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows_all = $this->laporanpendata_model->get_data();

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
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		// if($this->session->userdata('filter_code_rukunwarga') != '') {
		// 	$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		// }
		// if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
		// 	$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		// }
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows = $this->laporanpendata_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
				'nama_pendata'			=> $act->nama_pendata,
				'totalkk'				=> $act->totalkk,
				// 'totalanggotakeluarga'	=> $act->totalanggotakeluarga,
				'nama_koordinator'		=> $act->nama_koordinator,
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
	function json_detailkk($nama_koordinator='',$nama_pendata=''){
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
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		// // if($this->session->userdata('filter_code_rukunwarga') != '') {
		// // 	$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		// // }
		// // if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
		// // 	$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		// // }

		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows_all = $this->laporanpendata_model->get_data_detail($nama_koordinator,$nama_pendata);

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
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		// // if($this->session->userdata('filter_code_rukunwarga') != '') {
		// // 	$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		// // }
		// // if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
		// // 	$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		// // }
		if($this->session->userdata('filter_code_cl_bulandata') != '') {
			if($this->session->userdata('filter_code_cl_bulandata') == 'all') {
			}else{
				$this->db->where('MONTH(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_bulandata'));
			}
		}
		if($this->session->userdata('filter_code_cl_tahundata') != '') {
			if($this->session->userdata('filter_code_cl_tahundata') == 'all') {
			}else{
				$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$this->session->userdata('filter_code_cl_tahundata'));	
			}
		}else{
			$thnda=date("Y");
			$this->db->where('YEAR(data_keluarga.tanggal_pengisian)',$thnda);	
		}
		$rows = $this->laporanpendata_model->get_data_detail($nama_koordinator,$nama_pendata,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
				'nama_pendata'			=> $act->nama_pendata,
				'value'				=> $act->value,
				// 'totalanggotakeluarga'	=> $act->totalanggotakeluarga,
				'nama_koordinator'		=> $act->nama_koordinator,
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
	function json_anggotaKeluargaexport($anggota){
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
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
		$rows_all = $this->laporanpendata_model->get_data_anggotaKeluarga();

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
		$rows = $this->laporanpendata_model->get_data_anggotaKeluarga();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
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
				'bpjs'					=> $act->bpjs,
				'usia'					=> $act->usia,
				'suku'					=> $act->suku,
				'agama'					=> $act->agama,
				'pendidikan'			=> $act->pendidikan,
				'pekerjaan'				=> $act->pekerjaan,
				'kawin'					=> $act->kawin,
				'jkn'					=> $act->jkn,
				'no_hp'					=> $act->no_hp,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}

		
		$kode='P'.$this->session->userdata('puskesmas');
				$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 1,2));
				$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 1,4));
				$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 1,7));
				$kd_upb  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 1,7));
				
		
		$datadetail = $this->laporanpendata_model->get_data_export_detail($anggota);
		$desa  = $this->morganisasi_model->get_nama('value','cl_village','code',$datadetail['id_desa']);
		$tanggal_export = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,
			'kepala_keluarga' => $datadetail['namakepalakeluarga'],'kecamatan' => $kd_kec,'desa' => $desa,'rw' => $datadetail['rw'],'rt' => $datadetail['rt'],'norumah' => $datadetail['norumah'],'kodepos' => $datadetail['id_kodepos'],'pendata' => $datadetail['nama_pendata'],'koordinator' => $datadetail['nama_koordinator'],'alamat' => $datadetail['alamat']);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/anggotakeluarga.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_anggotakeluarga_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}


	function index(){
		$this->authentication->verify('eform','edit');
		$data['title_group'] = "eForm - Ketuk Pintu";
		$data['title_form'] = "Laporan Pendata";
		$this->session->set_userdata('filter_code_kecamatan','');
		$this->session->set_userdata('filter_code_kelurahan','');
		// $this->session->set_userdata('filter_code_rukunwarga','');
		// $this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
		$this->session->set_userdata('filter_code_cl_bulandata','');
		$this->session->set_userdata('filter_code_cl_tahundata','');
		$kode_sess = $this->session->userdata("puskesmas");
		$data['datakecamatan'] = $this->laporanpendata_model->get_datawhere(substr($kode_sess, 0,7),"code","cl_kec");
		$data['content'] = $this->parser->parse("eform/laporanpendata/show",$data,true);
		$this->template->show($data,"home");
	}
	
	function detailkk($nama_koordinator='',$nama_pendata=''){
		$this->authentication->verify('eform','edit');
		$data['title_group'] = "eForm - Ketuk Pintu";
		$data['title_form'] = "Detail Laporan Pendata";
		$data['nama_koordinator'] = $nama_koordinator;
		$data['nama_pendata'] = $nama_pendata;
        
		die($this->parser->parse("eform/laporanpendata/form",$data));
	}

	
	function get_kecamatanfilter(){
	
	if ($this->input->post('kecamatan')!="null") {
		if($this->input->is_ajax_request()) {
			$kecamatan = $this->input->post('kecamatan');
			$this->session->set_userdata('filter_code_kecamatan',$this->input->post('kecamatan'));
			$kode 	= $this->laporanpendata_model->get_datawhere($kecamatan,"code","cl_village");

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
			// if ($this->session->set_userdata('filter_code_rukunwarga')!=null) {
			// 	$this->session->set_userdata('filter_code_rukunwarga','');
			// }
			$kelurahan = $this->input->post('kelurahan');
			if ($kelurahan=='' || empty($kelurahan)) {
				echo '<option value="">Pilih Rukun Warga</option>';
				if ($this->session->set_userdata('filter_code_kelurahan')!=null) {
					$this->session->set_userdata('filter_code_kelurahan','');
				}
			}else{
				$this->session->set_userdata('filter_code_kelurahan',$this->input->post('kelurahan'));
				// $this->db->group_by("rw");
				// $kode 	= $this->laporanpendata_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

				// 	echo '<option value="">Pilih RW</option>';
				// foreach($kode as $kode) :
				// 	echo $select = $kode->rw == set_value('rukuwarga') ? 'selected' : '';
				// 	echo '<option value="'.$kode->rw.'" '.$select.'>' . $kode->rw . '</option>';
				// endforeach;
			}

			return FALSE;
		}

		show_404();
	}
	}
	// function get_rukunwargafilter(){
	// if ($this->input->post('rukunwarga')!="null" || $this->input->post('kelurahan')!="null") {	
	// 	if($this->input->is_ajax_request()) {
	// 		if($this->input->post('rukunwarga') != '') {
	// 			$this->session->set_userdata('filter_code_rukunwarga',$this->input->post('rukunwarga'));
	// 		}else{
	// 			$this->session->set_userdata('filter_code_rukunwarga','');
	// 		}
	// 		// $this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
	// 		// // $rukunwarga = $this->input->post('rukunwarga');
	// 		// // $kelurahan = $this->input->post('kelurahan');

	// 		// // $this->db->where("rw",$rukunwarga);
	// 		// // $this->db->group_by("rt");
	// 		// // $kode 	= $this->laporanpendata_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

	// 		// // echo '<option value="">Pilih RT</option>';
	// 		// // foreach($kode as $kode) :
	// 		// // 	echo $select = $kode->rt == set_value('rukunrumahtangga') ? 'selected' : '';
	// 		// // 	echo '<option value="'.$kode->rt.'" '.$select.'>' . $kode->rt . '</option>';
	// 		// // endforeach;

	// 		return FALSE;
	// 	}
		

	// 	show_404();
	// }
	// }
	// function get_rukunrumahtanggafilter(){
	// if ($this->input->post('rukunrumahtangga')!="null") {
	// 	if($_POST) {
	// 		if($this->input->post('rukunrumahtangga') != '') {
	// 			$this->session->set_userdata('filter_code_cl_rukunrumahtangga',$this->input->post('rukunrumahtangga'));
	// 		}else{
	// 			$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
	// 		}
	// 	}
	// }
	// }
	function get_filterbulandata(){
		if ($this->input->post('bulanfilter')!="null") {
			if($_POST) {
				if($this->input->post('bulanfilter') != '') {
					$this->session->set_userdata('filter_code_cl_bulandata',$this->input->post('bulanfilter'));
				}else{
					$this->session->set_userdata('filter_code_cl_bulandata','');
				}
			}
		}
	}
	function get_filtertahundata(){
		if ($this->input->post('tahunfilter')!="null") {
			if($this->input->is_ajax_request()) {
				$tahunfilter = $this->input->post('tahunfilter');
				if ($tahunfilter=='' || empty($tahunfilter) || $tahunfilter=='all') {
					echo '<option value="all">Bulan</option>';
					if ($tahunfilter=='all') {
						$this->session->set_userdata('filter_code_cl_tahundata',$tahunfilter);
						$this->session->set_userdata('filter_code_cl_bulandata','');
					}else{
						$this->session->set_userdata('filter_code_cl_tahundata','');
						$this->session->set_userdata('filter_code_cl_bulandata','');
					}
				}else{
					$bln=array(1=>"Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember");
					$this->session->set_userdata('filter_code_cl_tahundata',$this->input->post('tahunfilter'));
					echo '<option value="all">All</option>';
					foreach ($bln as $key => $value) {
						echo $select = $key == set_value('bulanfilter') ? 'selected' : '';
						echo '<option value="'.$key.'" '.$select.'>' . $value . '</option>';
					}
				}

				return FALSE;
			}

			show_404();
		}
	}
	
}
