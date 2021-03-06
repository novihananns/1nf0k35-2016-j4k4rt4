<?php
class Data_kepala_keluarga extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('bpjs');
		$this->load->model('morganisasi_model');
		$this->load->model('eform/datakeluarga_model');
		$this->load->model('eform/pembangunan_keluarga_model');
		$this->load->model('eform/anggota_keluarga_kb_model');
		$this->load->model('eform/dataform_model');

	    $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->helper('file');

		// $this->load->library('tools');


	}

	function urut($id_data_keluarga=0){
		$this->authentication->verify('eform','edit');

		$data 			= $this->datakeluarga_model->get_data_row($id_data_keluarga); 
		$data['vacant']	= $this->datakeluarga_model->get_urut_available($data); 

		die($this->parser->parse("eform/datakeluarga/urut",$data));
	}

	function nomor($id_data_keluarga=0,$nomor="000"){
		$this->authentication->verify('eform','edit');
		if($this->datakeluarga_model->nomor($id_data_keluarga,$nomor)){
			echo "OK";
		}else{
			echo "FAILED";
		}

	}


    function dataallexport(){
    	$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

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
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}
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
		$rows = $this->datakeluarga_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = $act->id_data_keluarga;
		}
		$keluarga = implode("','", $data);
		// die($keluarga);
		$profile 	 	= $this->datakeluarga_model->get_data_all_profile($keluarga);
		$kb 		 	= $this->datakeluarga_model->get_data_all_kb($keluarga);
		$pembangunan 	= $this->datakeluarga_model->get_data_all_pembangunan($keluarga);
		$anggota 		= $this->datakeluarga_model->get_data_all_anggota($keluarga);
		$anggota_pr 	= $this->datakeluarga_model->get_data_all_anggota_profile($keluarga);
		$rows 			= $this->datakeluarga_model->get_data_all($keluarga);
		$no=1;
		// die(print_r($rows));
		$data_tabel = array();
		foreach($rows as $act) {
			
			$id = $act['id'];
			$no_anggota = $act['no_anggota'];
			$datasumberpendaptan =(isset($profile[$id]['profile_c_2_a_radio']) && $profile[$id]['profile_c_2_a_radio']==1 ? "Pekerjaan;" : "").' '. (isset($profile[$id]['profile_c_2_b_radio']) && $profile[$id]['profile_c_2_b_radio']==1 ? "Sumbangan;" : "").' '.(isset($profile[$id]['profile_c_2_c_radio']) && $profile[$id]['profile_c_2_c_radio']== 1? "Lainnya" : "").' '.((!isset($profile[$id]['profile_c_2_a_radio']) && !isset($profile[$id]['profile_c_2_b_radio']) && !isset($profile[$id]['profile_c_2_c_radio'])) ? 'Tidak' : '');
$data_tabel[] = array(
	'namakepalakeluarga' => $act['namakepalakeluarga'],
	'no'			=> $no++,
	'nama'			=> ($act['nama'] 		=="" || $act['nama'] 		=="-" ? 'Tidak' : $act['nama']),
	'nourutkel'		=> ($act['nourutkel'] 	=="" || $act['nourutkel'] 	=="-" ? 'Tidak' : $act['nourutkel']),
	'nik'			=> ($act['nik'] 		=="" || $act['nik'] 	=="-"? 'Tidak' : $act['nik']),
	'tlp'			=> ($act['no_hp'] 		=="" || $act['no_hp'] 	=="-"?  'Tidak' : $act['no_hp']),
	'tmptlahir'		=> ($act['tmpt_lahir']	=="" || $act['tmpt_lahir'] 	=="-"? 'Tidak' :$act['tmpt_lahir'].", "),
	'tgllahir'		=> ($act['tgl_lahir']	=="" || $act['tgl_lahir'] 	=="-"? 'Tidak' : date("d-m-Y",strtotime($act['tgl_lahir']))),
	'umur'			=> ($act['usia'] 		=="" || $act['usia'] 	=="-"? 'Tidak' : $act['usia']." Thn"),
	'suku'			=> ($act['suku'] 		=="" || $act['usia'] 	=="-" ? "Tidak" : $act['suku']),
	'jmljiwa_l'		=> $act['jml_anaklaki']		!="" ? $act['jml_anaklaki'] : "0",
	'jmljiwa_p'		=> $act['jml_anakperempuan']!="" ? $act['jml_anakperempuan'] : "0",
	'pus_ikutkb'	=> $act['pus_ikutkb']		!="" ? $act['pus_ikutkb'] : "0",
	'pus_tidakikutkb'=> $act['pus_tidakikutkb']!="" ? $act['pus_tidakikutkb'] : "0",
	'beras'			=> isset($profile[$id]['profile_a_1_a_radio']) && $profile[$id]['profile_a_1_a_radio']==1? "Ya" : "Tidak",
	'nonberas'		=> isset($profile[$id]['profile_a_1_b_radio']) && $profile[$id]['profile_a_1_b_radio']==1? "Ya" : "Tidak",
	'sumber_air'	=> (isset($profile[$id]['profile_a_2_a_radio']) && $profile[$id]['profile_a_2_a_radio']==1? "PAM/Ledeng/Kemasan;" : "").' '.(isset($profile[$id]['profile_a_2_b_radio']) && $profile[$id]['profile_a_2_b_radio']==1? "Sumur Terlindung;" : "").' '.(isset($profile[$id]['profile_a_2_c_radio']) && $profile[$id]['profile_a_2_c_radio']==1? "Air Hujan/Sungai" : "").' '.(isset($profile[$id]['profile_a_1_h']) && $profile[$id]['profile_a_1_h'] !=""? $profile[$id]['profile_a_1_h'].';' : "").' '.(isset($profile[$id]['profile_a_2_d_lainnya']) && $profile[$id]['profile_a_2_d_lainnya'] !=""? $profile[$id]['profile_a_2_d_lainnya'].';' : "").' '.((!isset($profile[$id]['profile_a_2_a_radio']) && !isset($profile[$id]['profile_a_2_b_radio']) && !isset($profile[$id]['profile_a_2_c_radio']) && !isset($profile[$id]['profile_a_1_h']) && !isset($profile[$id]['profile_a_2_d_lainnya']) && !isset($profile[$id]['profile_a_1_h']))?'Tidak':''),
	'jamban'		=> isset($profile[$id]['profile_a_3_a_radio']) && $profile[$id]['profile_a_3_a_radio']==1? "Ya" : "Tidak",
	'sampah'		=> isset($profile[$id]['profile_a_4_a_radio']) && $profile[$id]['profile_a_4_a_radio']==1? "Ada" : "Tidak Ada",
	'limbah'		=> isset($profile[$id]['profile_a_5_a_radio']) && $profile[$id]['profile_a_5_a_radio']==1? "Ada" : "Tidak Ada",
	'stiker'		=> isset($profile[$id]['profile_a_6_a_radio']) && $profile[$id]['profile_a_6_a_radio']==1? "Ada" : "Tidak Ada",
	'up4k'			=> isset($profile[$id]['profile_b_1_a_radio']) && $profile[$id]['profile_b_1_a_radio']==1? "Ya" : "Tidak",
	'kesling'		=> isset($profile[$id]['profile_b_2_a_radio']) && $profile[$id]['profile_b_2_a_radio']==1? "Ya" : "Tidak",
	'pancasila'		=> ((isset($profile[$id]['profile_b_3_a_radio'])) ? ($profile[$id]['profile_b_3_a_radio']==1 ? "Ya" : ($profile[$id]['profile_b_3_a_radio']==0 ? "Tidak" : "Tidak")):'Tidak'),
	'kerjabakti'	=> ((isset($profile[$id]['profile_b_4_a_radio'])) ? ($profile[$id]['profile_b_4_a_radio']==1? "Ya" : ($profile[$id]['profile_b_4_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'rukunmati'		=> ((isset($profile[$id]['profile_b_5_a_radio'])) ? ($profile[$id]['profile_b_5_a_radio']==1? "Ya" : ($profile[$id]['profile_b_5_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'keagamaan'		=> ((isset($profile[$id]['profile_b_6_a_radio'])) ? ($profile[$id]['profile_b_6_a_radio']==1? "Ya" : ($profile[$id]['profile_b_6_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'jimpitan'		=> ((isset($profile[$id]['profile_b_7_a_radio'])) ? ($profile[$id]['profile_b_7_a_radio']==1? "Ya" : ($profile[$id]['profile_b_7_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'arisan'		=> ((isset($profile[$id]['profile_b_8_a_radio'])) ? ($profile[$id]['profile_b_8_a_radio']==1? "Ya" : ($profile[$id]['profile_b_8_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'koperasi'		=> ((isset($profile[$id]['profile_b_9_a_radio'])) ? ($profile[$id]['profile_b_9_a_radio']==1? "Ya" : ($profile[$id]['profile_b_9_a_radio']==0? "Tidak" : "Tidak")):'Tidak'),
	'kegiatanlain'	=> isset($profile[$id]['profile_b_10_a_radio']) && $profile[$id]['profile_b_10_a_radio']==1? "Ya" : "Tidak",
	'pendapatan'	=> isset($profile[$id]['profile_c_1_a_jumlah']) && $profile[$id]['profile_c_1_a_jumlah']!=""? $profile[$id]['profile_c_1_a_jumlah'] : "Tidak",
	'sumber_pendapatan'		=> (trim($datasumberpendaptan) == '' ? 'Tidak' : $datasumberpendaptan),
	'hubungan'		=> ((isset($anggota[$id][$no_anggota]['id_pilihan_hubungan'])) ? ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==1? "KK" : ($anggota[$id][$no_anggota]['id_pilihan_hubungan']== 2 ? "Istri" : ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==3? "Anak" : ($anggota[$id][$no_anggota]['id_pilihan_hubungan']==4? "Lain" : "Tidak")))):'Tidak'),
	'jenis_kelamin'	=> ((isset($anggota[$id][$no_anggota]['id_pilihan_kelamin'])) ? ($anggota[$id][$no_anggota]['id_pilihan_kelamin']==5? "L" : ($anggota[$id][$no_anggota]['id_pilihan_kelamin']==6 ? "P" : 'Tidak')):'Tidak'),
	'agama'			=> ((isset($anggota[$id][$no_anggota]['id_pilihan_agama'])) ? ($anggota[$id][$no_anggota]['id_pilihan_agama']==7? "Islam" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==8? "Kristen" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==9? "Katolik" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==10? "Hindu" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==11? "Budha" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==12? "Konghucu" : ($anggota[$id][$no_anggota]['id_pilihan_agama']==13? "Lain" : "Tidak"))))))):'Tidak'),
	'pendidikan'	=> ((isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan'])) ? ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']== 14 ? "Tidak Tamat SD/MI" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==15? "Masih SD/MI" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==16? "Tamat SD/MI" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==17? "Masih SLTP/MTs" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==18? "Tamat SLTP/MTs" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==19? "Masih SLTA/MA" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==20? "Tamat SLTA/MA" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==21? "Masih PT/Akademi" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==22? "Tamat PT/Akademi" : ($anggota[$id][$no_anggota]['id_pilihan_pendidikan']==23? "Tidak/Belum Sekolah" : "Tidak/Belum Sekolah")))))))))):'Tidak/Belum Sekolah'),
	'pekerjaan'		=> ((isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan'])) ? ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==24? "Petani" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==25? "Nelayan" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==46? "Pedagang" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==26? "PNS/TNI/Porli" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==27? "Pegawai Swasta" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==28? "Wiraswasta" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==29? "Pensiunan" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==30? "Pekerja Lepas" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==31? "Lainnya" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==32? "Tidak/Belum Bekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==42? "Bekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==43? "Belum Bekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==44? "TidakBekerja" : ($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==45? "IRT" : "Tidak")))))))))))))):'Tidak'),
	'status_kawin'	=> ((isset($anggota[$id][$no_anggota]['id_pilihan_kawin'])) ? ($anggota[$id][$no_anggota]['id_pilihan_kawin']==33? "Belum Kawin" : ($anggota[$id][$no_anggota]['id_pilihan_kawin']==34? "Kawin" : ($anggota[$id][$no_anggota]['id_pilihan_kawin']==35? "Janda/Duda" : "Tidak"))) :'Tidak'),
	'usaha_lingkungan'=> ((isset($anggota_pr[$id][$no_anggota]['profile_b_2_a_radio'])) ? ($anggota_pr[$id][$no_anggota]['profile_b_2_a_radio']==1? "Ya" : ($anggota_pr[$id][$no_anggota]['profile_b_2_a_radio']==0 ? "Tidak" : "Tidak")):'Tidak'),
	'bpjsjkn'		=> ((isset($anggota[$id][$no_anggota]['id_pilihan_jkn'])) ? ($anggota[$id][$no_anggota]['id_pilihan_jkn']==36 ? "BPJS-PBI" : ($anggota[$id][$no_anggota]['id_pilihan_jkn']==37? "BPJS-Non PBI" : ($anggota[$id][$no_anggota]['id_pilihan_jkn']==38? "Non BPJS" : ($anggota[$id][$no_anggota]['id_pilihan_jkn']==39? "Tidak Memiliki" : "Tidak")))):'Tidak'),
	'akte_lahir'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']==0? "Ada" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']==1? "Tidak Ada" : "Tidak")):'Tidak'),
	'wna_status'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'putus_sekolah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'paud_pernah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kelompok_bljr'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kelbel_a'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==0? "A" : "Tidak",
	'kelbel_b'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==1? "B" : "Tidak",
	'kelbel_c'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==2? "C" : "Tidak",
	'kelbel_kf'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==3? "KF" : "Tidak",
	'tabungan_punya'=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'koperasi_punya'=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'subur_usia'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'hamil_status'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'disabilitas_st'=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']== 0 ? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kb_suami'			=> ((isset($kb[$id]['berencana_II_1_suami']) && $kb[$id]['berencana_II_1_suami']!="") ? isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && (($anggota[$id][$no_anggota]['id_pilihan_hubungan']==1 || $anggota[$id][$no_anggota]['id_pilihan_hubungan']==2 ) ? $kb[$id]['berencana_II_1_suami'] : '0') : "0"),
	'kb_istri'			=> ((isset($kb[$id]['berencana_II_1_istri']) && $kb[$id]['berencana_II_1_istri']!="") ? isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && (($anggota[$id][$no_anggota]['id_pilihan_hubungan']==1 || $anggota[$id][$no_anggota]['id_pilihan_hubungan']==2 ) ? $kb[$id]['berencana_II_1_istri'] : '0') : "0"),
	'kb_lahir_l'		=> isset($kb[$id]['berencana_II_2_laki']) && $kb[$id]['berencana_II_2_laki']!=""? $kb[$id]['berencana_II_2_laki'] : "Tidak",
	'kb_lahir_p'		=> isset($kb[$id]['berencana_II_2_perempuan']) && $kb[$id]['berencana_II_2_perempuan']!=""? $kb[$id]['berencana_II_2_perempuan'] : "Tidak",
	'kb_hidup_l'		=> isset($kb[$id]['berencana_II_2_laki_hidup']) && $kb[$id]['berencana_II_2_laki_hidup']!=""? $kb[$id]['berencana_II_2_laki_hidup'] : "0",
	'kb_hidup_p'		=> isset($kb[$id]['berencana_II_2_perempuan_hidup']) && $kb[$id]['berencana_II_2_perempuan_hidup']!=""? $kb[$id]['berencana_II_2_perempuan_hidup'] : "0",
	'ikut_sertakb'		=> ((isset($kb[$id]['berencana_II_3_kb_radio'])) ? ($kb[$id]['berencana_II_3_kb_radio']==0? "Sedang" : ($kb[$id]['berencana_II_3_kb_radio']==1? "Pernah" : ($kb[$id]['berencana_II_3_kb_radio']==2? "Tidak Pernah" : "Tidak"))):'Tidak'),
	'metode_kb'			=> ((isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi'])) ? ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==0? "IUD" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==1? "MOW" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==2? "MOP" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==6? "Implan" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==3? "Suntik" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==7? "Pil" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==5? "Pil" : ($kb[$id]['berencana_II_4_kontrasepsi_sepsi']==8? "Tradisional" : "Tidak")))))))):'Tidak'),
	'lama_kb'			=> (isset($kb[$id]['berencana_II_5_tahun']) && $kb[$id]['berencana_II_5_tahun']!=""? $kb[$id]['berencana_II_5_tahun'].' Tahun' : "").' '.(isset($kb[$id]['berencana_II_5_bulan']) && $kb[$id]['berencana_II_5_bulan']!=""? $kb[$id]['berencana_II_5_bulan'].' Bulan' : "").' '.(((!isset($kb[$id]['berencana_II_5_tahun'])) && (!isset($kb[$id]['berencana_II_5_bulan'])) ) ? 'Tidak' : ""),
	'ingin_anak'		=> ((isset($kb[$id]['berencana_II_6_anak_radio'])) ? ($kb[$id]['berencana_II_6_anak_radio']==1 || $kb[$id]['berencana_II_6_anak_radio']==0 ? "Ya" : ($kb[$id]['berencana_II_6_anak_radio']==2? "Tidak" : "Tidak")):'Tidak'),
	'alasan_tdk_kb'		=> (isset($kb[$id]['berencana_II_7_berkb_hamil_cebox']) && $kb[$id]['berencana_II_7_berkb_hamil_cebox']==1? "Sedang Hamil;" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_fertilasi_cebox']) && $kb[$id]['berencana_II_7_berkb_fertilasi_cebox']==1? "Fertilitass" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']) && $kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']==1? "Tidak Setuju KB" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']) && $kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']==1? "Tidak Tahu KB" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_efeksamping_cebox']) && $kb[$id]['berencana_II_7_berkb_efeksamping_cebox']==1? "Takut Efeknya" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_pelayanan_cebox']) && $kb[$id]['berencana_II_7_berkb_pelayanan_cebox']==1? "Pelayanan KB Jauh" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']) && $kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']==1? "Tidak Mampu/Mahal" : "").' '.(isset($kb[$id]['berencana_II_7_berkb_lainya_cebox']) && $kb[$id]['berencana_II_7_berkb_lainya_cebox']==1? "Lainnya" : "").' '.((!isset($kb[$id]['berencana_II_7_berkb_hamil_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_fertilasi_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_efeksamping_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_pelayanan_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']) && !isset($kb[$id]['berencana_II_7_berkb_lainya_cebox'])) ? 'Tidak': ''),
	'tempat_kb'			=> ((isset($kb[$id]['berencana_II_8_pelayanan_radkb'])) ? ($kb[$id]['berencana_II_8_pelayanan_radkb']==0? "RSUP/RSUD" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==1? "RSU TNI" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==2? "RS Porli" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==3? "RS Swasta" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==4? "Klinik Umum" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==5? "Puskesmas" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==6? "Klinik Pratama" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==7? "Praktek Dokter" : ($kb[$id]['berencana_II_8_pelayanan_radkb']== 8 ? "RS Prtama" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==9? "Pustu/Pusling/Bides" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==10? "Poskesdes/Polindes" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==11? "Praktek Bidan" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==12? "Pelayanan Bergerak" : ($kb[$id]['berencana_II_8_pelayanan_radkb']==13? "Lainnya" : "Tidak")))))))))))))):'Tidak'),
	'beli_pakain'		=> ((isset($pembangunan[$id]['pembangunan_III_1_radio'])) ? ($pembangunan[$id]['pembangunan_III_1_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_1_radio']==1 ||$pembangunan[$id]['pembangunan_III_1_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'makansehari2x'		=> ((isset($pembangunan[$id]['pembangunan_III_2_radio'])) ? ($pembangunan[$id]['pembangunan_III_2_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_2_radio']==1 ||$pembangunan[$id]['pembangunan_III_2_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'berobat_faskes'		=> ((isset($pembangunan[$id]['pembangunan_III_3_radio'])) ?  ($pembangunan[$id]['pembangunan_III_3_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_3_radio']==1 ||$pembangunan[$id]['pembangunan_III_3_radio']==2 )? "Tidak" : "Tidak")):'Tidak'),
	'pakaian_beda'		=> ((isset($pembangunan[$id]['pembangunan_III_4_radio'])) ?($pembangunan[$id]['pembangunan_III_4_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_4_radio']==1 || $pembangunan[$id]['pembangunan_III_4_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_daging'		=> ((isset($pembangunan[$id]['pembangunan_III_5_radio'])) ? ($pembangunan[$id]['pembangunan_III_5_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_5_radio']==1||$pembangunan[$id]['pembangunan_III_5_radio']==2 ) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_ibadah'		=> ((isset($pembangunan[$id]['pembangunan_III_6_radio'])) ? ($pembangunan[$id]['pembangunan_III_6_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_6_radio']==1 || $pembangunan[$id]['pembangunan_III_6_radio']==2 ) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_kb_subur'		=> ((isset($pembangunan[$id]['pembangunan_III_7_radio'])) ? ($pembangunan[$id]['pembangunan_III_7_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_7_radio']==1 ||$pembangunan[$id]['pembangunan_III_7_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_tabung'		=> ((isset($pembangunan[$id]['pembangunan_III_8_radio'])) ? ($pembangunan[$id]['pembangunan_III_8_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_8_radio']==1 || $pembangunan[$id]['pembangunan_III_8_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_komunikasi'	=> ((isset($pembangunan[$id]['pembangunan_III_9_radio'])) ? ($pembangunan[$id]['pembangunan_III_9_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_9_radio']==1|| $pembangunan[$id]['pembangunan_III_9_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_sosial'		=> ((isset($pembangunan[$id]['pembangunan_III_10_radio'])) ? ($pembangunan[$id]['pembangunan_III_10_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_10_radio']==1 || $pembangunan[$id]['pembangunan_III_10_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_akses'			=> ((isset($pembangunan[$id]['pembangunan_III_11_radio'])) ? ($pembangunan[$id]['pembangunan_III_11_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_11_radio']==1 || $pembangunan[$id]['pembangunan_III_11_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_pengurus'		=> ((isset($pembangunan[$id]['pembangunan_III_12_radio'])) ? ($pembangunan[$id]['pembangunan_III_12_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_12_radio']==1 || $pembangunan[$id]['pembangunan_III_12_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_posyandu'		=> ((isset($pembangunan[$id]['pembangunan_III_13_radio'])) ? ($pembangunan[$id]['pembangunan_III_13_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_13_radio']==1|| $pembangunan[$id]['pembangunan_III_13_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_bkb'			=> ((isset($pembangunan[$id]['pembangunan_III_14_radio'])) ? ($pembangunan[$id]['pembangunan_III_14_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_14_radio']==1 || $pembangunan[$id]['pembangunan_III_14_radio']==2) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_bkr'			=> ((isset($pembangunan[$id]['pembangunan_III_15_radio'])) ? ($pembangunan[$id]['pembangunan_III_15_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_15_radio']==1 ||$pembangunan[$id]['pembangunan_III_15_radio']==2 ) ? "Tidak" : "Tidak")):'Tidak'),
	'pem_pik'			=> ((isset($pembangunan[$id]['pembangunan_III_16_radio'])) ? ($pembangunan[$id]['pembangunan_III_16_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_16_radio']==1 ||$pembangunan[$id]['pembangunan_III_16_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_bkl'			=> ((isset($pembangunan[$id]['pembangunan_III_17_radio'])) ? ($pembangunan[$id]['pembangunan_III_17_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_17_radio']==1|| $pembangunan[$id]['pembangunan_III_17_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_uppks'			=> ((isset($pembangunan[$id]['pembangunan_III_18_radio'])) ? ($pembangunan[$id]['pembangunan_III_18_radio']==0? "Ya" : (($pembangunan[$id]['pembangunan_III_18_radio']==1|| $pembangunan[$id]['pembangunan_III_18_radio']==2)? "Tidak" : "Tidak")):'Tidak'),
	'pem_atap_terluas'	=> (isset($pembangunan[$id]['pembangunan_III_1_19_cebo4']) && $pembangunan[$id]['pembangunan_III_1_19_cebo4']==0? "Daun;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_2_19_cebo4']) && $pembangunan[$id]['pembangunan_III_2_19_cebo4']==1? "Seng/Asbes;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_3_19_cebo4']) && $pembangunan[$id]['pembangunan_III_3_19_cebo4']==2? "Genteng;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_4_19_cebo4']) && $pembangunan[$id]['pembangunan_III_4_19_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_1_19_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_2_19_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_3_19_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_4_19_cebo4']))? 'Tidak':''),
	'pem_dinding_terluas'=> isset($pembangunan[$id]['pembangunan_III_1_20_cebo4']) && $pembangunan[$id]['pembangunan_III_1_20_cebo4']==0? "Tembok;" : "".' '.(isset($pembangunan[$id]['pembangunan_III_2_20_cebo4']) && $pembangunan[$id]['pembangunan_III_2_20_cebo4']==1? "Kayu/Seng;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_3_20_cebo4']) && $pembangunan[$id]['pembangunan_III_3_20_cebo4']==2? "Bambu;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_4_20_cebo4']) && $pembangunan[$id]['pembangunan_III_4_20_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_1_20_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_2_20_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_3_20_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_4_20_cebo4'])) ? 'Tidak':''),
	'pem_lantai_terluas'	=> (isset($pembangunan[$id]['pembangunan_III_1_21_cebo4']) && $pembangunan[$id]['pembangunan_III_1_21_cebo4']==0? "Ubin/Kramik/Marmer;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_2_21_cebo4']) && $pembangunan[$id]['pembangunan_III_2_21_cebo4']==1? "Semen/Papan;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_3_21_cebo4']) && $pembangunan[$id]['pembangunan_III_3_21_cebo4']==2? "Tanah;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_4_21_cebo4']) && $pembangunan[$id]['pembangunan_III_4_21_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_1_21_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_2_21_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_3_21_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_4_21_cebo4'])) ? 'Tidak' :''),
	'pem_terang_utama'=> (isset($pembangunan[$id]['pembangunan_III_22_1_cebo4']) && $pembangunan[$id]['pembangunan_III_22_1_cebo4']==0? "Listrik;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_22_2_cebo4']) && $pembangunan[$id]['pembangunan_III_22_2_cebo4']==1? "Genset/Disel;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_22_3_cebo4']) && $pembangunan[$id]['pembangunan_III_22_3_cebo4']==2? "Lampu Minyak;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_22_4_cebo4']) && $pembangunan[$id]['pembangunan_III_22_4_cebo4']==3? "Lainnya;" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_22_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_22_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_22_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_22_4_cebo4'])) ?'Tidak' :''),
	'air_minum'			=>(isset($pembangunan[$id]['pembangunan_III_23_1_cebo4']) && $pembangunan[$id]['pembangunan_III_23_1_cebo4']==0? "Ledeng/Kemasan;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_23_2_cebo4']) && $pembangunan[$id]['pembangunan_III_23_2_cebo4']==1? "Sumur/Pompa;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_23_3_cebo4']) && $pembangunan[$id]['pembangunan_III_23_3_cebo4']==2? "Air hujan/Sungai;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_23_4_cebo4']) && $pembangunan[$id]['pembangunan_III_23_4_cebo4']==3? "Lainnya; " : "").' '.((!isset($pembangunan[$id]['pembangunan_III_23_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_23_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_23_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_23_4_cebo4'])) ? 'Tidak' :''),
	'pem_bakar_bakar'	=> (isset($pembangunan[$id]['pembangunan_III_24_1_cebo4']) && $pembangunan[$id]['pembangunan_III_24_1_cebo4']==0? "Listrik/Gas;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_24_2_cebo4']) && $pembangunan[$id]['pembangunan_III_24_2_cebo4']==1? "Minyak Tanah;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_24_3_cebo4']) && $pembangunan[$id]['pembangunan_III_24_3_cebo4']==2? "Arang/Kayu;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_24_4_cebo4']) && $pembangunan[$id]['pembangunan_III_24_4_cebo4']==3? "Lainnya" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_24_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_24_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_24_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_24_4_cebo4'])) ? 'Tidak' :''),
	'pem_bab_fasilitas'	=> ((isset($pembangunan[$id]['pembangunan_III_25_radi4'])) ? ($pembangunan[$id]['pembangunan_III_25_radi4']==0? "Jamban Sendiri" : ($pembangunan[$id]['pembangunan_III_25_radi4']==1? "Jamban Bersama" : ($pembangunan[$id]['pembangunan_III_25_radi4']==2? "Jamban Umum" : ($pembangunan[$id]['pembangunan_III_25_radi4']==3? "Lainnya" : "Tidak")))):'Tidak'),
	'pem_rumah_milik'	=> (isset($pembangunan[$id]['pembangunan_III_26_1_cebo4']) && $pembangunan[$id]['pembangunan_III_26_1_cebo4']==0? "Sendiri" : "").' '.(isset($pembangunan[$id]['pembangunan_III_26_2_cebo4']) && $pembangunan[$id]['pembangunan_III_26_2_cebo4']==1? "Sewa/Kontrak;" : "").' '.(isset($pembangunan[$id]['pembangunan_III_26_3_cebo4']) && $pembangunan[$id]['pembangunan_III_26_3_cebo4']==2? "Menumpang" : "").' '.(isset($pembangunan[$id]['pembangunan_III_26_4_cebo4']) && $pembangunan[$id]['pembangunan_III_26_4_cebo4']==3? ":Lainnya" : "").' '.((!isset($pembangunan[$id]['pembangunan_III_26_1_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_26_2_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_26_3_cebo4']) && !isset($pembangunan[$id]['pembangunan_III_26_4_cebo4'])) ? 'Tidak' :''),
	'pem_luas'			=> isset($pembangunan[$id]['pembangunan_III_27_luas']) && $pembangunan[$id]['pembangunan_III_27_luas']!=""? $pembangunan[$id]['pembangunan_III_27_luas'] : "Tidak",
	'pem_menetap'		=> isset($pembangunan[$id]['pembangunan_III_28_orang']) && $pembangunan[$id]['pembangunan_III_28_orang']!=""? $pembangunan[$id]['pembangunan_III_28_orang'] : "Tidak",
	'kesehatan_1_g_1_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_a_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_b_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_c_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_d_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_e_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_1_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_f_cebox']==1? "Ya" : "Tidak",
	'kesehatan_bab_lokasi'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==0? "Jamban" : ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==1? "Kolam/Sawah/Selokan" : ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==2? "Sungai/Danau/Laut" : ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==4? "Pantai/Tanah Lapang/Kebun" : "Tidak")))):'Tidak'),
	'kes_sakit_gigi_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']==1? "Ya" : "Tidak",
	'kes_sakit_gigi_tidak'	=> !isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']) || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']!=1? "Tidak" : "Tidak",
	'kesehatan_1_g_4_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_a_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_b_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_c_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_d_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_e_cebox']==1? "Ya" : "Tidak",
	'kesehatan_1_g_4_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_f_cebox']==1? "Ya" : "Tidak",
	'kes_rokoksebulan'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==1)? "Ya" : (($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==2 || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==3 || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==4) ? "Tidak" : "Tidak")):'Tidak'),
	'kes_rokok_setiap'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text'] =="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']) : "Tidak"),
	'kes_rokok_pertama'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']) : "Tidak",
	'pneumonia_pernah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==1)? "Ya" : (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==2 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==3)? "Tidak" : "Tidak")):'Tidak'),
	'pneumonia_gejala'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==1)? "Ya" : (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==2|| $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==3)? "Tidak" : "Tidak")):'Tidak'),
	'kesulitangejala_pnomea'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']==1? "Napas Cepat;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']==1? "Napas Cuping Hidung" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']==1? "Tarikan dinding dada bawah ke dalam;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']))  ? 'Tidak':''),
	'kes_ginjal'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kes_batu'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kes_tb_batuk'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3'])) ? (($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==0 || $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==1)? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==2? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_gejala_batuk'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']==1? "Dahak;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']==1? "Darah/Dahak campur darah;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']==1? "Demam;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']==1? "Nyeri Dada;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']==1? "Sesak Nafas;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']==1? "Berkeringat malam hari tanpa kegiatan fisik;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']==1? "Nafsu Makan Menurun;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']==1? "Berta badan menurun/sulit bertambah" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']))?'Tidak':''),
	'perludidiagonosa'=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']==1? "Ya, kurang dari 1 tahun;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']==1? "Ya, Lebih dari 1 tahun" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox']==1? "Tidak" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox'])) ?'Tidak':''),
	'pemeriksaan_tb_gunakan'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']==1? "Pemeriksaan dahak; " : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox']==1? "Pemeriksaan foto dada (rontgen);" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox'])) ? 'Tidak' :''),
	'kanker_periksa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kanker_thn'				=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text'] =="-" ? "Tidak" : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']) : "Tidak",
	'jenis_kanker'				=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']==1? "Leher Rahim (Cervix Uteri);" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']==1? "Payudara;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']==1? "Prostat;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']==1? "Kolorektal/ Usus Besar;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']==1? "Paru dan Bronkus;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']==1? "Nasofaring;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']==1? "GetahBbening" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text']!=''? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text']=='-' ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text'].';') : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_h_text'])) ? 'Tidak':''),
	'kanker_tes_iva'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kanker_pap_smear'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_6_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_6_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_6_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'pengobatan_dijalani'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']==1? "Pembedahan/ operasi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']==1? "Radiasi/ penyinaran;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']==1? "Kemoterapi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']) : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']) &&  !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text'])) ? 'Tidak' :''),
	'ppok_pernah'	=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_gelaja_sesak'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']==1? "Terpapar Udara Dingin;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']==1? "Debu;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']==1? "Asap Rokok" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']==1? "Stress;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']==1? "Flu atau Infeksi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']==1? "Kelelahan;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']==1? "Alergi obat" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox']==1? "Alergi Makanan;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox'])) ?'Tidak':''),
	'gejala_sesak_disertai'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']==1? "Mengi;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']==1? "Sesak Napas Berkurang atau Menghilang dengan Pengobatan" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']==1? "Sesak Napas Berkurang atau Menghilang tanpa Pengobatan;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox']==1? " Sesak Napas Lebih Berat dirasakan pada Malam Hari atau Menjelang Pagi;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox'])) ? 'Tidak' :''),
	'pertama_kali_sesak'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']) : "Tidak"),
	'ppok_kambuh'				=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']))? ($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']==0? "Ya" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'diabet_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'pengendalian_dm'			=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']==1? "Diet;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']==1? "Olahraga;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']==1? "Minum obat anti diabetik;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']==1? "Injeksi Insulin;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']))?'Tidak':''),
	'gejala_dm_satubulan'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']==1? "Sering lapar;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']==1? "Sering Haus;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']==1? "Sering Buang Air Kecil & Jumlah Banyak" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox']==1? "Berat Badan turun;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox'])) ?'Tidak' :''),
	'darting_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_4_g_2_hp_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']) : "Tidak",
	'darting_obat'				=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'jantung_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_4_g_2_jk_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']) : "Tidak",
	'gejala_alami_jantung'		=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']==1? "Nyeri di dalam dada/ rasa tertekan berat/ tidak nyaman di dada ;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']==1? "Nyeri/ tidak nyaman di dada bagian tengah/ dada kiri depan/ menjalar ke lengan kiri;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']==1? "Nyeri/ tidak nyaman di dada dirasakan waktu endaki/ naik tangga/ berjalan tergesa-gesa;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox']==1? "Nyeri/ tidak nyaman di dada hilang ketika menghentikan aktivitas/ istirahat" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox'])) ? 'Tidak' :''),
	'stroke_diagnosa'			=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'kesehatan_4_g_2_sk_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']) : "Tidak",
	'gejala_struke_mendadak'	=> (isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']==1? "Kelumpuhan pada satu sisi tubuh;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']==1? "Kesemutan atau baal satu sisi tubuh;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']==1? " Mulut jadi mencong tanpa kelumpuhan otot mata;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']==1? "Bicara pelo;" : "").' '.(isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox']==1? "Sulit bicara/ komunikasi dan/atau tidak mengerti pembicaraan;" : "").' '.((!isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']) && !isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox'])) ? 'Tidak' : ''),
	'kesehatan_5_g_1_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_1_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_1_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_2_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_2_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_2_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_3_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_3_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_3_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_4_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_4_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_4_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_5_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_5_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_5_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_6_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_6_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_6_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_7_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_7_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_7_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_8_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_8_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_8_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_9_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_9_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_9_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_10_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_10_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_10_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_11_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_11_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_11_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_12_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_12_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_12_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_13_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_13_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_13_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_14_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_14_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_14_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_15_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_15_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_15_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_17_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_17_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_17_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_18_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_18_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_18_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_19_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_19_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_19_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_20_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_20_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_20_kk_cebox']==1? "Ya" : "Tidak",
	'kesehatan_5_g_23_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_23_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_23_kk_cebox']==1? "Ya" : "Tidak",
	'semua_20_obat'				=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'pernah_obat_faskes'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']==0? "Ya" : ($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']==1? "Tidak" : "Tidak")):'Tidak'),
	'stat_imunisasi'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==1? "Lengkap" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==2? "Tidak tahu" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==2? "Lengkap sesuai umur" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==3? "Tidak lengkap" : "Tidak"))),
	'kesehatan_6_g_2_ol_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']) : "Tidak",
	'kesehatan_6_g_2_td_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']) : "Tidak",
	'kesehatan_6_g_3_td_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']) : "Tidak",
	'kesehatan_6_g_3_tn_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']) : "Tidak",
	'kesehatan_6_g_3_p_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text'] =="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']) : "Tidak",
	'kesehatan_6_g_3_s_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']) : "Tidak",
	'kesehatan_6_g_4_at_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']) : "Tidak",
	'kesehatan_6_g_4_bb_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']) : "Tidak",
	'kesehatan_6_g_4_sg_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']) : "Tidak",
	'kesehatan_konjungtiva'		=> ((isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio'])) ? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']==0? "Pucat" : ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']==1? "Normal" : "Tidak")):'Tidak'),
	'kesehatan_6_g_6_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']=="-" ? 'Tidak' : $anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']) : "Tidak",
	'kesehatan_6_g_7_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']!=""? ($anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']=="-" ? 'Tidak' :$anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']) : "Tidak",
);
		}
// die(print_r($data_tabel));
		$kode='P'.$this->session->userdata('puskesmas');
		$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 1,2));
		$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 1,4));
		$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);

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
		
		$tanggal_export = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'kecamatan' => strtoupper($kecamatan),'kelurahan' => $kelurahan);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/data_detailall.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_kpldh_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
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
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}
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
		}
		 if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}
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
				$kd_prov = $this->morganisasi_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->morganisasi_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$nama  = $this->morganisasi_model->get_nama('value','cl_phc','code',$kode);
				$kd_kec  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->morganisasi_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));

		if ($this->input->post('kecamatan')!='' || $this->input->post('kecamatan')!='null' ) {
			$kecamatan = $this->input->post('kecamatan');

		}else{
			$kecamatan = '-';
		}
		if ($this->input->post('kelurahan')!='' && $this->input->post('kelurahan')!='null' && $this->input->post('kelurahan') !='Pilih Keluarahan') {
			$kelurahan = $this->input->post('kelurahan');
		}else{
			$kelurahan = '-';
		}
		if ($this->input->post('rukunwarga')!='' && $this->input->post('rukunwarga')!='null' && $this->input->post('rukunwarga')!='Pilih RW') {
			$rukunwarga = $this->input->post('rukunwarga');
		}else{
			$rukunwarga = '-';
		}
		if ($this->input->post('rukunrumahtangga')!='' && $this->input->post('rukunrumahtangga')!='null' && $this->input->post('rukunrumahtangga')!='Pilih RT') {
			$rukunrumahtangga = $this->input->post('rukunrumahtangga');
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
		$datajml = $this->datakeluarga_model->datajml();

		$jumlahjiwa = $datajml['jml_jiwa'];
		$jumlahlaki = $datajml['jml_laki'];
		$jumlahkk = $datajml['jml_kk'];
		$jumlahperempuan = $datajml['jml_perempuan'];
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kecamatan' => $kecamatan,'kelurahan' => $kelurahan,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'rw' => $rukunwarga,'rt' => $rukunrumahtangga,'tahunfilter' => $tahunfilter,'bulanfilter' => $bulanfilter,'jumlahjiwa' => $jumlahjiwa,'jumlahlaki' => $jumlahlaki,'jumlahperempuan' => $jumlahperempuan,'jumlahkk' => $jumlahkk);
		
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
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}

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
		}
		if($this->session->userdata('filter_code_kecamatan') != '') {
			$this->db->where('data_keluarga.id_kecamatan',$this->session->userdata('filter_code_kecamatan'));
		}
		if($this->session->userdata('filter_code_rukunwarga') != '') {
			$this->db->where('data_keluarga.rw',$this->session->userdata('filter_code_rukunwarga'));
		}
		if($this->session->userdata('filter_code_cl_rukunrumahtangga') != '') {
			$this->db->where('data_keluarga.rt',$this->session->userdata('filter_code_cl_rukunrumahtangga'));
		}
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
				'bpjs'					=> $act->bpjs,
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
		$rows = $this->datakeluarga_model->get_data_anggotaKeluarga();
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
				
		
		$datadetail = $this->datakeluarga_model->get_data_export_detail($anggota);
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
		$data['title_form'] = "Data Kepala Keluarga";
		$data['dataleveluser'] = $this->datakeluarga_model->get_dataleveluser();
		$this->session->set_userdata('filter_code_kecamatan','');
		$this->session->set_userdata('filter_code_kelurahan','');
		$this->session->set_userdata('filter_code_rukunwarga','');
		$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
		$this->session->set_userdata('filter_code_cl_bulandata','');
		$this->session->set_userdata('filter_code_cl_tahundata','');
		$kode_sess = $this->session->userdata("puskesmas");
		$data['datakecamatan'] = $this->datakeluarga_model->get_datawhere(substr($kode_sess, 0,7),"code","cl_kec");
		$data['content'] = $this->parser->parse("eform/datakeluarga/show",$data,true);
		$this->template->show($data,"home");
	}
	function adddataform_profile(){
		 $action = $this->dataform_model->insertdataform_profile();
		 die("$action");
	}

    function export_template(){
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0)
                                        ->setCellValue('A1', 'Keluaran')
                                        ->setCellValue('B1', 'RW')
                                        ->setCellValue('C1', 'RT')
                                        ->setCellValue('D1', 'URT')
                                        ->setCellValue('E1', 'Kode Pos')
                                        ->setCellValue('F1', 'Alamat')
                                        ->setCellValue('G1', 'Nama Komunitas')
                                        ->setCellValue('H1', 'Nama Kepala Rumah Tangga')
                                        ->setCellValue('I1', 'No. HP')
                                        ->setCellValue('J1', 'Nama Dasa Wisma')
                                        ->setCellValue('K1', 'Jml Anak Laki-laki')
                                        ->setCellValue('L1', 'Jml Anak Perempuan')
                                        ->setCellValue('M1', 'Jml Peserta KB')
                                        ->setCellValue('N1', 'Jml Bukan Peserta KB')
                                        ->setCellValue('O1', 'Jam Selesai Mendata')
                                        ->setCellValue('P1', 'Nama koordinator')
                                        ->setCellValue('Q1', 'Nama Pendata')
                                        ->setCellValue('R1', 'NIK')
                                        ->setCellValue('S1', 'BPJS')
                                        ->setCellValue('T1', 'Nama')
                                        ->setCellValue('U1', 'Jenis Kelamin')
                                        ->setCellValue('V1', 'Tempat Lahir')
                                        ->setCellValue('W1', 'Tanggal Lahir')
                                        ->setCellValue('X1', 'Hubungan')
                                        ->setCellValue('Y1', 'Agama')
                                        ->setCellValue('Z1', 'Pendidikan')
                                        ->setCellValue('AA1', 'Pekerjaan')
                                        ->setCellValue('AB1', 'Status Kawin')
                                        ->setCellValue('AC1', 'JKN')
                                        ->setCellValue('AD1', 'Suku')
                                        ->setCellValue('AE1', 'No HP')
                                        ->setCellValue('AF1', 'Makanan Pokok Sehari-hari ? Beras')
                                        ->setCellValue('AG1', 'Makanan Pokok Sehari-hari ? Non Beras')
                                        ->setCellValue('AH1', 'Makanan Pokok Sehari-hari ? Mie Instan')
                                        ->setCellValue('AI1', 'Makanan Pokok Sehari-hari ? Makanan Cepat Saji')
                                        ->setCellValue('AJ1', 'Makanan Pokok Sehari-hari ? Donut & Sejenisnya')
                                        ->setCellValue('AK1', 'Makanan Pokok Sehari-hari ? Biskuit kering')
                                        ->setCellValue('AL1', 'Makanan Pokok Sehari-hari ? Gorengan')
                                        ->setCellValue('AM1', 'Makanan Pokok Sehari-hari ? Lainya')
                                        ->setCellValue('AN1', 'Sumber Air Keluarga ? Pam/Ledeng/Kemasan')
                                        ->setCellValue('AO1', 'Sumber Air Keluarga ? Sumur Terlindung')
                                        ->setCellValue('AP1', 'Sumber Air Keluarga ? Air Hujan / Air Sungai')
                                        ->setCellValue('AQ1', 'Sumber Air Keluarga ? Lainnya')
                                        ->setCellValue('AR1', 'Jamban Keluarga')
                                        ->setCellValue('AS1', 'Saluran Pembuangan Sampah')
                                        ->setCellValue('AT1', 'Saluran Pembuangan Air Limbah')
                                        ->setCellValue('AU1', 'Stiker P4k')
                                        ->setCellValue('AV1', 'UP2K')
                                        ->setCellValue('AW1', 'Usaha Kesehatan Lingkungan')
                                        ->setCellValue('AX1', 'Pengahayatan Pengamalan Pancasila')
                                        ->setCellValue('AY1', 'Kerja Bakti')
                                        ->setCellValue('AZ1', 'Rukun Kematian')
                                        ->setCellValue('BA1', 'Keagamaan')
                                        ->setCellValue('BB1', 'Jimpitan')
                                        ->setCellValue('BC1', 'Arisan')
                                        ->setCellValue('BD1', 'Koperasi')
                                        ->setCellValue('BE1', 'Lainnya')
                                        ->setCellValue('BF1', 'Pendapatan Per Bulan')
                                        ->setCellValue('BG1', 'Sumber Pendapatan ? Pekerjaan')
                                        ->setCellValue('BH1', 'Sumber Pendapatan ? Sumbangan')
                                        ->setCellValue('BI1', 'Sumber Pendapatan ? Lainya2')
                                        ->setCellValue('BJ1', 'Usia Kawin : Suami')
                                        ->setCellValue('BK1', 'Usia Kawin : Istri')
                                        ->setCellValue('BL1', 'Jumlah Anak Laki-Laki')
                                        ->setCellValue('BM1', 'Jumlah Anak Perempuan')
                                        ->setCellValue('BN1', 'Kepersertaan KB')
                                        ->setCellValue('BO1', 'Metode KB yg sedang/ pernah dilakukan')
                                        ->setCellValue('BP1', 'Berapa Lama KB ? Tahun')
                                        ->setCellValue('BQ1', 'Berapa Lama KB ? Bulan')
                                        ->setCellValue('BR1', 'Ingin pny anak lagi ?')
                                        ->setCellValue('BS1', 'Alasan tidak ber-KB : Sedang Hamil')
                                        ->setCellValue('BT1', 'Alasan tidak ber-KB : fertilitas')
                                        ->setCellValue('BU1', 'Alasan tidak ber-KB :  Tidak menyetujui KB')
                                        ->setCellValue('BV1', 'Alasan tidak ber-KB :  Tidak tahu tentang KB')
                                        ->setCellValue('BW1', 'Alasan tidak ber-KB :  Takut efek samping')
                                        ->setCellValue('BX1', 'Alasan tidak ber-KB :  Pelayanan KB Jauh')
                                        ->setCellValue('BY1', 'Alasan tidak ber-KB : Tidak mampu/mahal')
                                        ->setCellValue('BX1', 'Alasan tidak ber-KB : Lainnya')
                                        ->setCellValue('CA1', 'Tempat pelayanan KB')
                                        ->setCellValue('CB1', 'Klrg beli satu stel pakaian baru u/ selruh anggota klrg 1th/x')
                                        ->setCellValue('CC1', 'Seluruh anggota klrg makan min 2x/hr')
                                        ->setCellValue('CD1', 'Slrh anggota klrg jika sakit berobat ke fasyankes')
                                        ->setCellValue('CE1', 'Slrh anggota klrg punya baju beda u/ di rumah/kerja/sekolah/pergi')
                                        ->setCellValue('CF1', 'Slrh anggota klrg mkn daging/telllur/ikan min 1mg/x')
                                        ->setCellValue('CG1', 'Slrh anggota klrg beribadah')
                                        ->setCellValue('CH1', 'Pasangan usia subur dgn 2 anak/> menjadi peserta KB')
                                        ->setCellValue('CI1', 'Klrg punya tabungan emas/ tanah/hewan min senilai Rp. 1 jt')
                                        ->setCellValue('CJ1', 'Klrg punya kebiasaan berkomunikasi dgn slrh anggota klrg')
                                        ->setCellValue('CK1', 'Klrg ikut kegiatan sosial di link. RT')
                                        ->setCellValue('CL1', 'Klrg punya akses informasi dr tv/koran/radio')
                                        ->setCellValue('CM1', 'Klrg punya anggota klrg yg jd pengurus keg. Sosial')
                                        ->setCellValue('CN1', 'Klrg punya balita yg ikut posyandu')
                                        ->setCellValue('CO1', 'Klrg punya balita yg ikut BKB')
                                        ->setCellValue('CP1', 'Klrg punya remaja yg ikut BKR')
                                        ->setCellValue('CQ1', 'Klrg punya remaja yg ikut PIK')
                                        ->setCellValue('CR1', 'Klrg punya lansia yg ikut BKL')
                                        ->setCellValue('CS1', 'Klrg mengikuti kegiatan UPPKS')
                                        ->setCellValue('CT1', 'Jenis atap rumah terluas ? Daun/Rumbia')
                                        ->setCellValue('CU1', 'Jenis atap rumah terluas ? Seng/Asbes')
                                        ->setCellValue('CV1', 'Jenis atap rumah terluas ? Genteng/Sirap')
                                        ->setCellValue('CW1', 'Jenis atap rumah terluas ? Lainnya')
                                        ->setCellValue('CX1', 'Jenis dinding terluas ? Tembok')
                                        ->setCellValue('CY1', 'Jenis dinding terluas ? Kayu/Seng')
                                        ->setCellValue('CZ1', 'Jenis dinding terluas ? Bambu')
                                        ->setCellValue('DA1', 'Jenis dinding terluas ? Lainnya')
                                        ->setCellValue('DB1', 'Jenis lantai rumah terluas ? Ubin/Kramik/Marmer')
                                        ->setCellValue('DC1', 'Jenis lantai rumah terluas ? Semen/Papan')
                                        ->setCellValue('DD1', 'Jenis lantai rumah terluas ? Tanah')
                                        ->setCellValue('DE1', 'Jenis lantai rumah terluas ? Lainnya')
                                        ->setCellValue('DF1', 'Sumber penerangan utama ? Listrik')
                                        ->setCellValue('DG1', 'Sumber penerangan utama ? Genset/Diesel')
                                        ->setCellValue('DH1', 'Sumber penerangan utama ? Lampu Minyak')
                                        ->setCellValue('DI1', 'Sumber penerangan utama ? Lainnya')
                                        ->setCellValue('DJ1', 'Sumber air minum utama ? Ledeng/Kemasan')
                                        ->setCellValue('DK1', 'Sumber air minum utama ? Sumur terlindung/Pompa')
                                        ->setCellValue('DL1', 'Sumber air minum utama ? Air hujan/sungai')
                                        ->setCellValue('DM1', 'Sumber air minum utama ? Lainnya')
                                        ->setCellValue('DN1', 'Bahan bakar utama untuk memasak ? Listri/Gas')
                                        ->setCellValue('DO1', 'Bahan bakar utama untuk memasak ? Minyak Tanah')
                                        ->setCellValue('DP1', 'Bahan bakar utama untuk memasak ? Arang/Kayu')
                                        ->setCellValue('DQ1', 'Bahan bakar utama untuk memasak ? Lainnya')
                                        ->setCellValue('DR1', 'Fasilitas tempat buang air besar')
                                        ->setCellValue('DS1', 'Status kepemilikan rumah/bangunan tempat tinggal ? Milik Sendiri')
                                        ->setCellValue('DT1', 'Status kepemilikan rumah/bangunan tempat tinggal ? Sewa/Kontrak')
                                        ->setCellValue('DU1', 'Status kepemilikan rumah/bangunan tempat tinggal ? Menumpang')
                                        ->setCellValue('DV1', 'Status kepemilikan rumah/bangunan tempat tinggal ? Lainnya')
                                        ->setCellValue('DW1', 'Luas rumah / banngunan keselurahan')
                                        ->setCellValue('DX1', 'Jumlah Org yg menetap dlm rumah')
                                        ->setCellValue('DY1', 'Akte lahir')
                                        ->setCellValue('DZ1', 'Tidak')
                                        ->setCellValue('EA1', 'Putus Sekolah')
                                        ->setCellValue('EB1', 'Ikut PAUD')
                                        ->setCellValue('EC1', 'Ikut Kel. Belajar, paket ?')
                                        ->setCellValue('ED1', 'Jika Ya, pilih jenis paket A, B, C atau KF')
                                        ->setCellValue('EE1', 'Punya tabungan')
                                        ->setCellValue('EF1', 'Ikut koperasi')
                                        ->setCellValue('EG1', 'Jika Ya, tuliskan jenis')
                                        ->setCellValue('EH1', 'Usia Subur')
                                        ->setCellValue('EI1', 'Hamil')
                                        ->setCellValue('EJ1', 'Disabilitas, sebutkan jenis')
                                        ->setCellValue('EK1', 'Jika Ya, tuliskan jenisnya')
                                        ->setCellValue('EL1', 'Cuci Tangan Pakai Sabun sebelum menyiapkan makanan')
                                        ->setCellValue('EM1', 'Cuci tangan Pakai Sabun sebelum mencebok bayi')
                                        ->setCellValue('EN1', 'Cuci tangan Pakai Sabun sebelum menyusui bayi')
                                        ->setCellValue('EO1', 'Cuci tangan Pakai Sabun setiap kali tangan kotor')
                                        ->setCellValue('EP1', 'Cuci tangan Pakai Sabun setelah buang air besar')
                                        ->setCellValue('EQ1', 'Cuci tangan Pakai Sabun setelah menggunakan pestisida')
                                        ->setCellValue('ER1', 'Lokasi BAB')
                                        ->setCellValue('ES1', 'Sikat gigi setiap hari? (Ya atau Tidak)?')
                                        ->setCellValue('ET1', 'Kapan menyikat gigi ? setelah mandi pagi')
                                        ->setCellValue('EU1', 'Menyikat gigi ? setelah mandi sore')
                                        ->setCellValue('EV1', 'Menyikat gigi ? setelah makan pagi')
                                        ->setCellValue('EW1', 'Menyikat gigi ? setelah makan malam')
                                        ->setCellValue('EX1', 'Menyikat gigi ? setelah makan siang')
                                        ->setCellValue('EY1', 'Menyikat gigi ?  ? sesudah bangun pagi')
                                        ->setCellValue('EZ1', 'Merokok selama 1 bln terakhir ?')
                                        ->setCellValue('DA1', 'Umur brp  ? ')
                                        ->setCellValue('FB1', 'Mulai merokok setiap hari?')
                                        ->setCellValue('FC1', 'Pertama kali merokok ?')
                                        ->setCellValue('FD1', 'Untuk jawaban "Ya" , Jumlah batang rokok dikonsumsi per hari ?')
                                        ->setCellValue('FE1', 'Minuman sehari-hari : Air putih')
                                        ->setCellValue('FF1', 'Minuman sehari-hari : Susu')
                                        ->setCellValue('FG1', 'Minuman sehari-hari : Kopi')
                                        ->setCellValue('FH1', 'Minuman sehari-hari : Teh Tawar')
                                        ->setCellValue('FI1', 'Minuman sehari-hari :  Teh Manis')
                                        ->setCellValue('FJ1', 'Minuman sehari-hari : Juice buah')
                                        ->setCellValue('FK1', 'Minuman sehari-hari : Minuman ber-soda')
                                        ->setCellValue('FL1', 'Minuman sehari-hari : Minuman ber-alkohol')
                                        ->setCellValue('FM1', 'Minuman sehari-hari : Lainnya')
                                        ->setCellValue('FN1', 'Pernah di Dx. Dgn/tanpa Rontgen')
                                        ->setCellValue('FO1', 'Mengalami  demam, batuk, sulit nafas Dgn/tanpa nyeri dada')
                                        ->setCellValue('FP1', 'Jika ya, kesulitan yg dialami ? Napas cepat')
                                        ->setCellValue('FQ1', 'Jika ya, kesulitan yg dialami ? Napas cuping hidung')
                                        ->setCellValue('FR1', 'Jika ya, kesulitan yg dialami ? Tarikan dinding dada bawah ke dalam')
                                        ->setCellValue('FS1', 'Pernah di Dx. Ginjal Kronis (min. sakit slm 3bln berturut2)')
                                        ->setCellValue('FT1', 'Pernah di Dx. Batu Ginjal ?')
                                        ->setCellValue('FU1', 'Akhir2 ini batuk ?')
                                        ->setCellValue('FV1', 'Jika ya, batuk disertai gejala ? Dahak')
                                        ->setCellValue('FW1', 'Jika ya, batuk disertai gejala ?Darah/ Dahak campur darah')
                                        ->setCellValue('FX1', 'Jika ya, batuk disertai gejala ? Demam')
                                        ->setCellValue('FY1', 'Jika ya, batuk disertai gejala ? Nyeri Dada')
                                        ->setCellValue('FX1', 'Jika ya, batuk disertai gejala ? Sesak Nafas')
                                        ->setCellValue('GA1', 'Jika ya, batuk disertai gejala ?  Berkeringat malam hari tanpa kegiatan fisik')
                                        ->setCellValue('GB1', 'Jika ya, batuk disertai gejala ?Nafsu Makan menurun')
                                        ->setCellValue('GC1', 'Jika ya, batuk disertai gejala ?Berat badan menurun/ sulit bertambah')
                                        ->setCellValue('GD1', 'Pernah di Dx. TB Paru ?Ya, dalam ≤ 1 tahun terakhir')
                                        ->setCellValue('GE1', 'Pernah di Dx. TB Paru ? Ya, > 1 tahun')
                                        ->setCellValue('GF1', 'Pernah di Dx. TB Paru ? Tidak')
                                        ->setCellValue('GG1', 'Pemeriksaan yg digunakan u/ Dx. TB ? Pemeriksaan dahak')
                                        ->setCellValue('GH1', 'Pemeriksaan yg digunakan u/ Dx. TB ? Pemeriksaan foto dada (Rontgen)')
                                        ->setCellValue('GI1', 'Pernah di Dx. Kangker ?')
                                        ->setCellValue('GJ1', 'Pertama kali Di Dx. tahun berapa ?')
                                        ->setCellValue('GK1', 'Jenis Kangker ? leher rahim (cervix uteri)')
                                        ->setCellValue('GL1', 'Jenis Kangker ?  Payudara')
                                        ->setCellValue('GM1', 'Jenis Kangker ? Prostat')
                                        ->setCellValue('GN1', 'Jenis Kangker ? kolorektal/ usus besar')
                                        ->setCellValue('GO1', 'Jenis Kangker ?paru dan bronkus')
                                        ->setCellValue('GP1', 'Jenis Kangker ? Nasofaring')
                                        ->setCellValue('GQ1', 'Jenis Kangker ? getah bening')
                                        ->setCellValue('GR1', 'Jenis Kangker ? Lainnya')
                                        ->setCellValue('GS1', 'Pernah test IVA ?')
                                        ->setCellValue('GT1', 'Pengobatan yg dijalani ? Pembedahan/ operasi')
                                        ->setCellValue('GU1', 'Pengobatan yg dijalani ? Radiasi/ penyinaran')
                                        ->setCellValue('GV1', 'Pengobatan yg dijalani ? Kemoterapi')
                                        ->setCellValue('GW1', 'Pengobatan yg dijalani ? Lainnya')
                                        ->setCellValue('GX1', 'Pernah Pap Smear ?')
                                        ->setCellValue('GY1', 'Pernah kena gejala sesak napas ?')
                                        ->setCellValue('GZ1', 'Gejala sesak terjadi pd kondisi ? Terpapar udara dingin')
                                        ->setCellValue('HA1', 'Gejala sesak terjadi pd kondisi ? Debu')
                                        ->setCellValue('HB1', 'Gejala sesak terjadi pd kondisi ? Asap rokok')
                                        ->setCellValue('HC1', 'Gejala sesak terjadi pd kondisi ? Stress')
                                        ->setCellValue('HD1', 'Gejala sesak terjadi pd kondisi ? Flu atau infeksi')
                                        ->setCellValue('HE1', 'Gejala sesak terjadi pd kondisi ? Kelelahan')
                                        ->setCellValue('HF1', 'Gejala sesak terjadi pd kondisi ? Alergi obat')
                                        ->setCellValue('HG1', 'Gejala sesak terjadi pd kondisi ? Alergi makanan')
                                        ->setCellValue('HH1', 'Gejala sesak disertai dgn kondisi ? Mengi')
                                        ->setCellValue('HI1', 'Gejala sesak disertai dgn kondisi ? Sesak napas berkurang atau menghilang dengan pengobatan')
                                        ->setCellValue('HJ1', 'Gejala sesak disertai dgn kondisi ? Sesak napas berkurang atau menghilang tanpa pengobatan')
                                        ->setCellValue('HK1', 'Gejala sesak disertai dgn kondisi ?Sesak napas lebih berat dirasakan pada malam hari atau menjelang pagi')
                                        ->setCellValue('HL1', 'Umur berapa sesak pertama kali ?')
                                        ->setCellValue('HM1', 'Sesak pernah kambuh dlm 12 bln terakhir ?')
                                        ->setCellValue('HN1', 'Pernah di Dx. DM ?')
                                        ->setCellValue('HO1', 'Usaha pengendalian DM ? Diet ')
                                        ->setCellValue('HP1', 'Usaha pengendalian DM ? Olah raga')
                                        ->setCellValue('HQ1', 'Usaha pengendalian DM ? Minum obat anti diabetik')
                                        ->setCellValue('HR1', 'Usaha pengendalian DM ? Injeksi insulin')
                                        ->setCellValue('HS1', 'Gejala yg dialami dlm 1bln terakhir ? Sering lapar')
                                        ->setCellValue('HT1', 'Gejala yg dialami dlm 1bln terakhir ? Sering haus')
                                        ->setCellValue('HU1', 'Gejala yg dialami dlm 1bln terakhir ? Sering buang air kecil & jumlah banyak')
                                        ->setCellValue('HV1', 'Gejala yg dialami dlm 1bln terakhir ? Berat badan turun')
                                        ->setCellValue('HW1', 'Pernah di Dx. HT ?')
                                        ->setCellValue('HX1', 'Tahun brp di Dx. Pertama kali ?')
                                        ->setCellValue('HY1', 'Sedang minum obat HT ?')
                                        ->setCellValue('HZ1', 'Pernah di Dx. Penyakit Jantung ?')
                                        ->setCellValue('IA1', 'Tahun brp pertama kali di Dx. ?')
                                        ->setCellValue('IB1', 'Gejala yg dialami ? Nyeri di dalam dada/ rasa tertekan berat/ tidak nyaman di dada')
                                        ->setCellValue('IC1', 'Gejala yg dialami ? Nyeri/ tidak nyaman di dada bagian tengah/ dada kiri depan/ menjalar ke lengan kiri')
                                        ->setCellValue('ID1', 'Gejala yg dialami ? Nyeri/ tidak nyaman di dada dirasakan waktu endaki/ naik tangga/ berjalan tergesa-gesa')
                                        ->setCellValue('IE1', 'Gejala yg dialami ? Nyeri/ tidak nyaman di dada hilang ketika menghentikan aktivitas/ istirahat')
                                        ->setCellValue('IF1', 'Pernah di Dx. Stroke ?')
                                        ->setCellValue('IG1', 'Tahun brp di Dx. ?')
                                        ->setCellValue('IH1', 'Pernah alami keluhan scr mendadak ? Kelumpuhan pada satu sisi tubuh')
                                        ->setCellValue('II1', 'Pernah alami keluhan scr mendadak ? Kesemutan atau baal satu sisi tubuh')
                                        ->setCellValue('IJ1', 'Pernah alami keluhan scr mendadak ? Mulut jadi mencong tanpa kelumpuhan otot mata')
                                        ->setCellValue('IK1', 'Pernah alami keluhan scr mendadak ? Bicara pelo')
                                        ->setCellValue('IL1', 'Pernah alami keluhan scr mendadak ? Sulit bicara/ komunikasi dan/atau tidak mengerti pembicaraan')
                                        ->setCellValue('IM1', 'Sering sakit kepala')
                                        ->setCellValue('IN1', 'Tdk nafsu makan')
                                        ->setCellValue('IO1', 'Sulit tidur')
                                        ->setCellValue('IP1', 'Mudah takut')
                                        ->setCellValue('IQ1', 'Merasa tegang/ cemas/ kuatir')
                                        ->setCellValue('IR1', 'Tangan gemetar')
                                        ->setCellValue('IS1', 'Pencernaan terganggu /buruk')
                                        ->setCellValue('IT1', 'Sulit berpikir jernih')
                                        ->setCellValue('IU1', 'Merasa tidak bahagia')
                                        ->setCellValue('IV1', 'Menangis lebih sering')
                                        ->setCellValue('IW1', 'Merasa sulit menikmati kegiatan sehari2')
                                        ->setCellValue('IX1', 'Sulit mengambil keputusan')
                                        ->setCellValue('IY1', 'Pekerjaan sehari2 terganggu')
                                        ->setCellValue('IZ1', 'Tdk mampu melakukan hal2 yg bermanfaat dlm hidup')
                                        ->setCellValue('JA1', 'Kehilangan minat dlm berbagai hal')
                                        ->setCellValue('JB1', 'Merasa tidak berharga')
                                        ->setCellValue('JC1', 'Mempunyai pikiran utk mengakhiri hidup')
                                        ->setCellValue('JD1', 'Merasa lelah sepanjang waktu')
                                        ->setCellValue('JE1', 'Mengalami rasa tidak enak di perut')
                                        ->setCellValue('JF1', 'Mudah lelah')
                                        ->setCellValue('JG1', 'Keluhan tsb pernah berobat ke fasyankes ?')
                                        ->setCellValue('JH1', 'Jika pernah, apakah dlm 2 mg terakhir ?')
                                        ->setCellValue('JI1', 'Status Imunisasi')
                                        ->setCellValue('JJ1', 'Aktivitas Olahraga')
                                        ->setCellValue('JK1', 'Aktivitas Tidur')
                                        ->setCellValue('JL1', 'TD')
                                        ->setCellValue('JM1', 'N')
                                        ->setCellValue('JN1', 'R')
                                        ->setCellValue('JO1', 'S')
                                        ->setCellValue('JP1', 'TB')
                                        ->setCellValue('JQ1', 'BB')
                                        ->setCellValue('JR1', 'Conjungtiva')
                                        ->setCellValue('JS1', 'Status Gizi')
                                        ->setCellValue('JT1', 'Riwayat Kesehatan Dulu')
                                        ->setCellValue('JU1', 'Riwayat Kesehatan Sekarang')
                                        ->setCellValue('JV1', 'Analisa Masalah Kesehatan');


            $objPHPExcel->getActiveSheet()->getStyle("A1:JX1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '92d050')
                    ),

                    'alignment' => array(
            		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			),

                    'font' => array(
                        'color' => array('rgb' => '000000')
                    )
                )
            );

            //Setting lebar cell
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('BZ')->setWidth(25);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('CA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('CZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('DZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('ED')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('ER')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('ES')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('ET')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('EZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('FZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('GZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('HZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('ID')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('II')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IU')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IV')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IX')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IW')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IY')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('IZ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JA')->setWidth(35); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JB')->setWidth(15); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JC')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JD')->setWidth(10); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JE')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JF')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JG')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JH')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JI')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JJ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JK')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JL')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JM')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JN')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JO')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JP')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JQ')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JR')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JS')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JT')->setWidth(25); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('JU')->setWidth(25); 


            $objPHPExcel->getActiveSheet()->setTitle('Excel Pertama');
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="TemplateExcel.xlsx"');
            $objWriter->save("php://output");
 
    }

    function import(){
        $fileName = time().$_FILES['file_excel']['name'];

        $config['upload_path'] = './assets/'; 
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file_excel') )
        $this->upload->display_errors();
             
        $media = $this->upload->data('file_excel');
        $inputFileName = './assets/'.$media['file_name'];
         
        try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
            	$this->session->set_flashdata('alert_fail', 'Silahkan Tentukan File Terlebih Dahulu');
	    		redirect(base_url()."eform/data_kepala_keluarga/import_add");
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow    = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();

            // print_r($highestRow);
            // print_r($highestColumn);
            // die();
            $temp = array();
            $data = array();
            for ($row = 2; $row <= $highestRow; $row++){  //  Read a row of data into an array                 
            	// $temp = $rowData;
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
        	    // print_r($rowData);
        	    $kk [$rowData[0][3]][] = $rowData;
       //  	    $id = $this->datakeluarga_model->getNourutkel($this->input->post('kelurahan'));
                
       //          $data = array(
       //              "alamat"             => $rowData[0][0],
       //              "id_kodepos"         => $rowData[0][1],
       //              "rw" 			     => $rowData[0][2],
       //              "rt"		         => $rowData[0][3],
       //              "norumah"            => $rowData[0][4],
       //              "namakepalakeluarga" => $rowData[0][5],
       //              "notlp"              => $rowData[0][6],
       //              "namadesawisma"      => $rowData[0][7],
       //              "nama_komunitas"     => $rowData[0][8],
       //              "id_pkk"             => $rowData[0][9],
       //              "nama_koordinator"   => $rowData[0][10],
       //              "nama_pendata"       => $rowData[0][11],
       //              "jam_selesai" 		 => $rowData[0][12],
       //              "jml_anaklaki"		 => $rowData[0][13],
       //              "jml_anakperempuan"  => $rowData[0][14],
       //              "pus_ikutkb"		 => $rowData[0][15],
       //              "pus_tidakikutkb"    => $rowData[0][16]
       //          );
			    //     $data['id_data_keluarga'] 	 = $id['id_data_keluarga'];
			    //     $data['nourutkel'] 	 		 = $id['nourutkel'];
			    //     $data['tanggal_pengisian']   = date("Y-m-d", strtotime($this->input->post('tgl_pengisian')));
			    //     $data['jam_data'] 			 = $this->input->post('jam_data');
			    //     $data['id_propinsi']         = $this->input->post('provinsi');
			    //     $data['id_kota'] 		     = $this->input->post('kota');
			    //     $data['id_kecamatan']        = $this->input->post('id_kecamatan');
			    //     $data['id_desa']             = $this->input->post('kelurahan');

			    // //insert to database
       //          $insert = $this->db->insert("data_keluarga",$data);
                delete_files($media['file_path']);
            }
            $provinsi 		= $this->input->post('provinsi');
            $kota 			= $this->input->post('kota');
            $id_kecamatan 	= $this->input->post('id_kecamatan');
            $kelurahan 		= $this->input->post('kelurahan');
            $id = $this->datakeluarga_model->getNourutkel($kelurahan);
            $tanggal_pengisian = date("d-m-Y");
            $jam_data = date("H:i:s");
            foreach ($kk as $anggota) {
            	foreach ($anggota as $act => $value) {
            		$data_keluarga = array(
            			'id_data_keluarga'	=> $id['id_data_keluarga'],
            			'nourutkel' 		=> $id['nourutkel'],
            			'tanggal_pengisian' => $tanggal_pengisian,
            			'jam_data' 			=> $jam_data,
            			'alamat' 			=> $value[0][6],
            			'id_propinsi' 		=>	$provinsi,
            			'id_kota'	 		=>	$kota,
            			'id_kecamatan' 		=>	$id_kecamatan,
            			'id_desa' 			=>	$kelurahan,
            			'id_kodepos' 		=>	$value[0][5],
            			'rw' 				=>	$value[0][1],
            			'rt' 				=>	$value[0][2],
            			'norumah' 			=>	$value[0][4],
            			'namakepalakeluarga' =>	$value[0][8],
            			'notlp' 			=>	$value[0][9],
            			'namadesawisma' 	=>	$value[0][10],
            			'nama_komunitas' 	=>	$value[0][7],
            			'id_pkk' 			=>	$value[0][24],
            			'nama_koordinator' 	=>	$value[0][16],
            			'nama_pendata' 		=>	$value[0][17],
            			'jam_selesai' 		=>	$jam_data,
            			'jml_anaklaki' 		=>	$value[0][11],
            			'jml_anakperempuan' =>	$value[0][12],
            			'pus_ikutkb' 		=>	$value[0][13],
            			'pus_tidakikutkb' 	=>	$value[0][14],
            		);
            		$noanggota = $this->datakeluarga_model->noanggota($data_keluarga['id_data_keluarga']);
            		$data_keluarga_anggota = array(
            			'id_data_keluarga'	=> $data_keluarga['id_data_keluarga'],
            			'no_anggota' 		=> $noanggota,
            			'nama' 				=> $value[0][20],
            			'nik' 				=> $value[0][18],
            			'tmpt_lahir' 			=> $value[0][22],
            			'tgl_lahir' 			=>	$value[0][23],
            			'id_pilihan_hubungan'	=>	$value[0][24],
            			'id_pilihan_kelamin' 	=>	$value[0][21],
            			'id_pilihan_agama' 		=>	$value[0][25],
            			'id_pilihan_pendidikan' =>	$value[0][26],
            			'id_pilihan_pekerjaan' 	=>	$value[0][27],
            			'id_pilihan_kawin' 	=>	$value[0][28],
            			'id_pilihan_jkn' 	=>	$value[0][29],
            			'bpjs' 				=>	$value[0][19],
            			'suku' 				=>	$value[0][30],
            			'no_hp' 			=>	$value[0][31],
            			);
            		if ($value[0][24]=='KK') {
            			$this->db->insert('data_keluarga',$data_keluarga);
            			$this->db->insert('data_keluarga_anggota',$data_keluarga_anggota);
            		}else{
            			$this->db->insert('data_keluarga_anggota',$data_keluarga_anggota);
            		}
            	}
            }
		$this->session->set_flashdata('alert', 'Import data successful');
	    redirect(base_url()."eform/data_kepala_keluarga/import_add");
    }

	function import_add(){
		$this->authentication->verify('eform','add');

        $this->form_validation->set_rules('filename', 'File Excel', 'trim|required');
        

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "eForm - Ketuk Pintu";
			$data['title_form']="Import Excel KPLDH";
			$data['action']="import_add";
			$data['id_data_keluarga']="";
          	$data['data_provinsi'] = $this->datakeluarga_model->get_provinsi();
          	$data['data_kotakab'] = $this->datakeluarga_model->get_kotakab();
          	$data['data_kecamatan'] = $this->datakeluarga_model->get_kecamatan();
          	$data['data_desa'] = $this->datakeluarga_model->get_desa();
          	$data['data_pos'] = $this->datakeluarga_model->get_pos();
          	$data['data_pkk'] = $this->datakeluarga_model->get_pkk();

			$data['content'] = $this->parser->parse("eform/datakeluarga/import_add",$data,true);
			$this->template->show($data,"home");
		}elseif($id = $this->datakeluarga_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'eform/data_kepala_keluarga/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/data_kepala_keluarga/");
		}

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
        $this->form_validation->set_rules('jml_anaklaki', 'Jumlah Laki-laki', 'trim|required');
        $this->form_validation->set_rules('jml_anakperempuan', 'Jumlah Perempuan', 'trim|required');
        $this->form_validation->set_rules('pus_ikutkb', 'Jumlah PUS Peserta KB', 'trim|required');
        $this->form_validation->set_rules('pus_tidakikutkb', 'Jumlah PUS Bukan Peserta KB', 'trim|required');
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
        $this->form_validation->set_rules('kelurahan', 'Kelurahan / Desa', 'trim|required');
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
        $this->form_validation->set_rules('jml_anaklaki', 'Jumlah Laki-laki', 'trim|required');
        $this->form_validation->set_rules('jml_anakperempuan', 'Jumlah Perempuan', 'trim|required');
        $this->form_validation->set_rules('pus_ikutkb', 'Jumlah PUS Peserta KB', 'trim|required');
        $this->form_validation->set_rules('pus_tidakikutkb', 'Jumlah PUS Bukan Peserta KB', 'trim|required');
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
		}elseif($id_data_keluarga = $this->datakeluarga_model->update_entry($id_data_keluarga)){
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
			$data['alert_form'] = 'Delete data ('.$idkeluarga.')';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}else{
			$data['alert_form'] = 'Delete data error';
			die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
		}
	}

	function anggota($kode=0)
	{
		$this->authentication->verify('eform','edit');
		$data['dataleveluser'] = $this->datakeluarga_model->get_dataleveluser();
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
        $this->form_validation->set_rules('bpjs', 'bpjs', 'trim');
        $this->form_validation->set_rules('providerPeserta', 'providerPeserta', 'trim');

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
        	$data['alert_form'] = '';
			die($this->parser->parse("eform/datakeluarga/form_anggota_add",$data));
		}elseif($noanggota=$this->datakeluarga_model->insert_dataAnggotaKeluarga($kode)){
			$this->anggota_edit($this->input->post('id_data_keluarga'),$noanggota);	
		}else{
			$data['alert_form'] = 'Save data failed...';
			die($this->parser->parse("eform/datakeluarga/form_anggota_add",$data));
		}
	}

	function cekkonek(){
		$data = $this->bpjs->get_data_bpjs();
		
		if (isset($data['code']) && isset($data['server']) && isset($data['username']) && isset($data['password']) && isset($data['consid']) && isset($data['secretkey'])) {
			die('ready');
		}else{
			die('off');
		}
	}
	function simpanbpjs($kode=0){
		$data = $this->bpjs->inserbpjs($kode);
		die($data);
	}
	function hapusbpjs($kode=0){
		$data = $this->bpjs->deletebpjs($kode);
		die($data);
	}
	function addanggotaprofile()
	{
	 	$actionprofile = $this->datakeluarga_model->addanggotaprofile();
	 	die("$actionprofile");
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

      	//$data['kdPoli'] = $this->bpjs->bpjs_option('poli');

      	$data['alert_form'] = '';

        $data['data_profile_anggota'] = $this->datakeluarga_model->get_data_anggotaprofile($idkeluarga,$noanggota);
		die($this->parser->parse("eform/datakeluarga/form_anggota_form",$data));
	}

	function bpjs_search($by = 'nik',$no){
      	$data = $this->bpjs->bpjs_search($by,$no);

      	echo json_encode($data);
	}

	function update_kepala(){
		$actionkepala = $this->datakeluarga_model->update_kepala();
		die("$actionkepala");
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
		$actionberencana= $this->anggota_keluarga_kb_model->insertDataKeluargaBerencana();
		die("$actionberencana");
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
		$actionpembangunan = $this->pembangunan_keluarga_model->insertdatatable_pembangunan();
		die("$actionpembangunan");
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
			if ($this->session->set_userdata('filter_code_rukunwarga')!=null) {
				$this->session->set_userdata('filter_code_rukunwarga','');
			}
			$kelurahan = $this->input->post('kelurahan');
			if ($kelurahan=='' || empty($kelurahan)) {
				echo '<option value="">Pilih Rukun Warga</option>';
				if ($this->session->set_userdata('filter_code_kelurahan')!=null) {
					$this->session->set_userdata('filter_code_kelurahan','');
				}
			}else{
				$this->session->set_userdata('filter_code_kelurahan',$this->input->post('kelurahan'));
				$this->db->group_by("rw");
				$kode 	= $this->datakeluarga_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

					echo '<option value="">Pilih RW</option>';
				foreach($kode as $kode) :
					echo $select = $kode->rw == set_value('rukuwarga') ? 'selected' : '';
					echo '<option value="'.$kode->rw.'" '.$select.'>' . $kode->rw . '</option>';
				endforeach;
			}

			return FALSE;
		}

		show_404();
	}
	}
	function get_rukunwargafilter(){
	if ($this->input->post('rukunwarga')!="null" || $this->input->post('kelurahan')!="null") {	
		if($this->input->is_ajax_request()) {
			if($this->input->post('rukunwarga') != '') {
				$this->session->set_userdata('filter_code_rukunwarga',$this->input->post('rukunwarga'));
			}else{
				$this->session->set_userdata('filter_code_rukunwarga','');
			}
			$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
			$rukunwarga = $this->input->post('rukunwarga');
			$kelurahan = $this->input->post('kelurahan');

			$this->db->where("rw",$rukunwarga);
			$this->db->group_by("rt");
			$kode 	= $this->datakeluarga_model->get_datawhere($kelurahan,"id_desa","data_keluarga");

			echo '<option value="">Pilih RT</option>';
			foreach($kode as $kode) :
				echo $select = $kode->rt == set_value('rukunrumahtangga') ? 'selected' : '';
				echo '<option value="'.$kode->rt.'" '.$select.'>' . $kode->rt . '</option>';
			endforeach;

			return FALSE;
		}
		

		show_404();
	}
	}
	function get_rukunrumahtanggafilter(){
	if ($this->input->post('rukunrumahtangga')!="null") {
		if($_POST) {
			if($this->input->post('rukunrumahtangga') != '') {
				$this->session->set_userdata('filter_code_cl_rukunrumahtangga',$this->input->post('rukunrumahtangga'));
			}else{
				$this->session->set_userdata('filter_code_cl_rukunrumahtangga','');
			}
		}
	}
	}
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
	function autocomplite_namakoordinator(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->where("nama_koordinator like '%".$search."%'");
		$this->db->limit(10,0);
		$this->db->group_by('nama_koordinator');
		$query= $this->db->get("data_keluarga")->result();
		foreach ($query as $q) {

			$nama[] = array(
				'nama_koordinator' 	=> $q->nama_koordinator,
			);
		}
		echo json_encode($nama);
	}
	function autocomplite_namapendata(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->where("nama_pendata like '%".$search."%'");
		$this->db->limit(10,0);
		$this->db->group_by('nama_pendata');
		$query= $this->db->get("data_keluarga")->result();
		foreach ($query as $q) {

			$namapendata[] = array(
				'nama_pendata' 	=> $q->nama_pendata,
			);
		}
		echo json_encode($namapendata);
	}
}
