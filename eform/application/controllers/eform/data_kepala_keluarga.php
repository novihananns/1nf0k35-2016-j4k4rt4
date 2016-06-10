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

		$rows = $this->datakeluarga_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = $act->id_data_keluarga;
		}
		$keluarga = implode("','", $data);

		$profile 	 	= $this->datakeluarga_model->get_data_all_profile($keluarga);
		$kb 		 	= $this->datakeluarga_model->get_data_all_kb($keluarga);
		$pembangunan 	= $this->datakeluarga_model->get_data_all_pembangunan($keluarga);
		$anggota 		= $this->datakeluarga_model->get_data_all_anggota($keluarga);
		$anggota_pr 	= $this->datakeluarga_model->get_data_all_anggota_profile($keluarga);
		$rows 			= $this->datakeluarga_model->get_data_all($keluarga);
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$id = $act['id'];
			$no_anggota = $act['no_anggota'];
$data_tabel[] = array(
	'namakepalakeluarga' => $act['namakepalakeluarga'],
	'no'			=> $no++,
	'nama'			=> $act['nama'] 		!="" ? $act['nama'] : "-",
	'nik'			=> $act['nik'] 			!="" ? $act['nik'] : "-",
	'tlp'			=> $act['no_hp'] 		!="" ? $act['no_hp'] : "-",
	'tmptlahir'		=> $act['tmpt_lahir']	!="" ? $act['tmpt_lahir'].", " : "-",
	'tgllahir'		=> $act['tgl_lahir']	!="" ? date("d-m-Y",strtotime($act['tgl_lahir'])) : " ",
	'umur'			=> $act['usia'] 		!="" ? $act['usia']." Thn" : "-",
	'suku'			=> $act['suku'] 		!="" ? $act['suku'] : "-",
	'beras'			=> isset($profile[$id]['profile_a_1_a_radio']) && $profile[$id]['profile_a_1_a_radio']==1? "Ya" : "Tidak",
	'nonberas'		=> isset($profile[$id]['profile_a_1_b_radio']) && $profile[$id]['profile_a_1_b_radio']==1? "Ya" : "Tidak",
	'ledeng'		=> isset($profile[$id]['profile_a_2_a_radio']) && $profile[$id]['profile_a_2_a_radio']==1? "Ya" : "Tidak",
	'sumur'			=> isset($profile[$id]['profile_a_2_b_radio']) && $profile[$id]['profile_a_2_b_radio']==1? "Ya" : "Tidak",
	'hujan'			=> isset($profile[$id]['profile_a_2_c_radio']) && $profile[$id]['profile_a_2_c_radio']==1? "Ya" : "Tidak",
	'airlain'		=> isset($profile[$id]['profile_a_1_h']) && $profile[$id]['profile_a_1_h'] !=""? $profile[$id]['profile_a_1_h'] : "-",
	'jamban'		=> isset($profile[$id]['profile_a_3_a_radio']) && $profile[$id]['profile_a_3_a_radio']==1? "Ya" : "Tidak",
	'sampah'		=> isset($profile[$id]['profile_a_4_a_radio']) && $profile[$id]['profile_a_4_a_radio']==1? "Ya" : "Tidak",
	'limbah'		=> isset($profile[$id]['profile_a_5_a_radio']) && $profile[$id]['profile_a_5_a_radio']==1? "Ya" : "Tidak",
	'stiker'		=> isset($profile[$id]['profile_a_6_a_radio']) && $profile[$id]['profile_a_6_a_radio']==1? "Ya" : "Tidak",
	'up4k'			=> isset($profile[$id]['profile_b_1_a_radio']) && $profile[$id]['profile_b_1_a_radio']==1? "Ya" : "Tidak",
	'kesling'		=> isset($profile[$id]['profile_b_2_a_radio']) && $profile[$id]['profile_b_2_a_radio']==1? "Ya" : "Tidak",
	'pancasila'		=> isset($profile[$id]['profile_b_3_a_radio']) && $profile[$id]['profile_b_3_a_radio']==1? "Ya" : "Tidak",
	'kerjabakti'	=> isset($profile[$id]['profile_b_4_a_radio']) && $profile[$id]['profile_b_4_a_radio']==1? "Ya" : "Tidak",
	'rukunmati'		=> isset($profile[$id]['profile_b_5_a_radio']) && $profile[$id]['profile_b_5_a_radio']==1? "Ya" : "Tidak",
	'keagamaan'		=> isset($profile[$id]['profile_b_6_a_radio']) && $profile[$id]['profile_b_6_a_radio']==1? "Ya" : "Tidak",
	'jimpitan'		=> isset($profile[$id]['profile_b_7_a_radio']) && $profile[$id]['profile_b_7_a_radio']==1? "Ya" : "Tidak",
	'arisan'		=> isset($profile[$id]['profile_b_8_a_radio']) && $profile[$id]['profile_b_8_a_radio']==1? "Ya" : "Tidak",
	'koperasi'		=> isset($profile[$id]['profile_b_9_a_radio']) && $profile[$id]['profile_b_9_a_radio']==1? "Ya" : "Tidak",
	'kegiatanlain'	=> isset($profile[$id]['profile_b_10_a_radio']) && $profile[$id]['profile_b_10_a_radio']==1? "Ya" : "Tidak",
	'pendapatan'	=> isset($profile[$id]['profile_c_1_a_jumlah']) && $profile[$id]['profile_c_1_a_jumlah']!=""? $profile[$id]['profile_c_1_a_jumlah'] : "-",
	'pekerjaan'		=> isset($profile[$id]['profile_c_2_a_radio']) && $profile[$id]['profile_c_2_a_radio']==1? "Ya" : "Tidak",
	'sumbangan'		=> isset($profile[$id]['profile_c_2_b_radio']) && $profile[$id]['profile_c_2_b_radio']==1? "Ya" : "Tidak",
	'pendapatanlain'=> isset($profile[$id]['profile_c_2_c_radio']) && $profile[$id]['profile_c_2_c_radio']==1? "Ya" : "Tidak",
	'hub_kk'		=> isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && $anggota[$id][$no_anggota]['id_pilihan_hubungan']==1? "KK" : "-",
	'hub_istri'		=> isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && $anggota[$id][$no_anggota]['id_pilihan_hubungan']==2? "Istri" : "-",
	'hub_anak'		=> isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && $anggota[$id][$no_anggota]['id_pilihan_hubungan']==3? "Anak" : "-",
	'hub_lain'		=> isset($anggota[$id][$no_anggota]['id_pilihan_hubungan']) && $anggota[$id][$no_anggota]['id_pilihan_hubungan']==4? "Lain" : "-",
	'jk_l'			=> isset($anggota[$id][$no_anggota]['id_pilihan_kelamin']) && $anggota[$id][$no_anggota]['id_pilihan_kelamin']==5? "L" : "-",
	'jk_p'			=> isset($anggota[$id][$no_anggota]['id_pilihan_kelamin']) && $anggota[$id][$no_anggota]['id_pilihan_kelamin']==6? "P" : "-",
	'agama_is'		=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==7? "Is" : "-",
	'agama_kris'	=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==8? "Kris" : "-",
	'agama_kat'		=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==9? "Kat" : "-",
	'agama_hin'		=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==10? "Hin" : "-",
	'agama_bud'		=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==11? "Bud" : "-",
	'agama_kong'	=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==12? "Kong" : "-",
	'agama_lain'	=> isset($anggota[$id][$no_anggota]['id_pilihan_agama']) && $anggota[$id][$no_anggota]['id_pilihan_agama']==13? "Lain" : "-",
	'pendidikan_sd_tidaktamat'	=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==14? "v" : "-",
	'pendidikan_sd'			=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==15? "v" : "-",
	'pendidikan_sd_tamat'	=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==16? "v" : "-",
	'pendidikan_smp'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==17? "v" : "-",
	'pendidikan_smp_tamat'	=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==18? "v" : "-",
	'pendidikan_sma'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==19? "v" : "-",
	'pendidikan_sma_tamat'	=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==20? "v" : "-",
	'pendidikan_pt'			=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==21? "v" : "-",
	'pendidikan_pt_tamat'	=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==22? "v" : "-",
	'pendidikan_tidak'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pendidikan']) && $anggota[$id][$no_anggota]['id_pilihan_pendidikan']==23? "v" : "-",
	'kerja_petani'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==24? "v" : "-",
	'kerja_nelayan'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==25? "v" : "-",
	'kerja_dagang'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==46? "v" : "-",
	'kerja_pns'			=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==26? "v" : "-",
	'kerja_swasta'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==27? "v" : "-",
	'kerja_wiraswasta'	=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==28? "v" : "-",
	'kerja_pensiun'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==29? "v" : "-",
	'kerja_lepas'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==30? "v" : "-",
	'kerja_lain'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==31? "v" : "-",
	'kerja_tidak'		=> isset($anggota[$id][$no_anggota]['id_pilihan_pekerjaan']) && $anggota[$id][$no_anggota]['id_pilihan_pekerjaan']==32? "v" : "-",
	'kawin_belum'		=> isset($anggota[$id][$no_anggota]['id_pilihan_kawin']) && $anggota[$id][$no_anggota]['id_pilihan_kawin']==33? "v" : "-",
	'kawin_kawin'		=> isset($anggota[$id][$no_anggota]['id_pilihan_kawin']) && $anggota[$id][$no_anggota]['id_pilihan_kawin']==34? "v" : "-",
	'kawin_janda'		=> isset($anggota[$id][$no_anggota]['id_pilihan_kawin']) && $anggota[$id][$no_anggota]['id_pilihan_kawin']==35? "v" : "-",
	'jkn_bpjs'			=> isset($anggota[$id][$no_anggota]['id_pilihan_jkn']) && $anggota[$id][$no_anggota]['id_pilihan_jkn']==36? "v" : "-",
	'jkn_bpjsnon'		=> isset($anggota[$id][$no_anggota]['id_pilihan_jkn']) && $anggota[$id][$no_anggota]['id_pilihan_jkn']==37? "v" : "-",
	'jkn_non'			=> isset($anggota[$id][$no_anggota]['id_pilihan_jkn']) && $anggota[$id][$no_anggota]['id_pilihan_jkn']==38? "v" : "-",
	'jkn_tidak'			=> isset($anggota[$id][$no_anggota]['id_pilihan_jkn']) && $anggota[$id][$no_anggota]['id_pilihan_jkn']==39? "v" : "-",
	'akte_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']==1? "Tidak" : "-",
	'akte_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_1_radio']==0? "Ya" : "-",
	'wna_tidak'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']==1? "Tidak" : "-",
	'wna_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_2_radio']==0? "Ya" : "-",
	'putus_sekolah_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']==1? "Tidak" : "-",
	'putus_sekolah_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_3_radio']==0? "Ya" : "-",
	'paud_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']==1? "Tidak" : "-",
	'paud_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_4_radio']==0? "Ya" : "-",
	'kelbel_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radio']==1? "Tidak" : "-",
	'kelbel_a'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==0? "A" : "-",
	'kelbel_b'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==1? "B" : "-",
	'kelbel_c'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==2? "C" : "-",
	'kelbel_kf'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_5_radi4']==3? "KF" : "-",
	'tabungan_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']==1? "Tidak" : "-",
	'tabungan_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_6_radio']==0? "Ya" : "-",
	'koperasi_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']==1? "Tidak" : "-",
	'koperasi_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_7_radio']==0? "Ya" : "-",
	'subur_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']==1? "Tidak" : "-",
	'subur_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_8_radio']==0? "Ya" : "-",
	'hamil_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']==1? "Tidak" : "-",
	'hamil_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_9_radio']==0? "Ya" : "-",
	'disabilitas_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']==1? "Tidak" : "-",
	'disabilitas_ya'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_0_g_10_radio']==0? "Ya" : "-",
	'kb_suami'			=> isset($kb[$id]['berencana_II_1_suami']) && $kb[$id]['berencana_II_1_suami']!=""? $kb[$id]['berencana_II_1_suami'] : "-",
	'kb_istri'			=> isset($kb[$id]['berencana_II_1_istri']) && $kb[$id]['berencana_II_1_istri']!=""? $kb[$id]['berencana_II_1_istri'] : "-",
	'kb_lahir_l'		=> isset($kb[$id]['berencana_II_2_laki']) && $kb[$id]['berencana_II_2_laki']!=""? $kb[$id]['berencana_II_2_laki'] : "-",
	'kb_lahir_p'		=> isset($kb[$id]['berencana_II_2_perempuan']) && $kb[$id]['berencana_II_2_perempuan']!=""? $kb[$id]['berencana_II_2_perempuan'] : "-",
	'kb_hidup_l'		=> isset($kb[$id]['berencana_II_2_laki_hidup']) && $kb[$id]['berencana_II_2_laki_hidup']!=""? $kb[$id]['berencana_II_2_laki_hidup'] : "-",
	'kb_hidup_p'		=> isset($kb[$id]['berencana_II_2_perempuan_hidup']) && $kb[$id]['berencana_II_2_perempuan_hidup']!=""? $kb[$id]['berencana_II_2_perempuan_hidup'] : "-",
	'kb_hidup_p'		=> isset($kb[$id]['berencana_II_2_perempuan_hidup']) && $kb[$id]['berencana_II_2_perempuan_hidup']!=""? $kb[$id]['berencana_II_2_perempuan_hidup'] : "-",
	'kb_sedang'			=> isset($kb[$id]['berencana_II_3_kb_radio']) && $kb[$id]['berencana_II_3_kb_radio']==0? "v" : "-",
	'kb_pernah'			=> isset($kb[$id]['berencana_II_3_kb_radio']) && $kb[$id]['berencana_II_3_kb_radio']==1? "v" : "-",
	'kb_tidakpernah'	=> isset($kb[$id]['berencana_II_3_kb_radio']) && $kb[$id]['berencana_II_3_kb_radio']==2? "v" : "-",
	'kb_iud'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==0? "v" : "-",
	'kb_mow'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==1? "v" : "-",
	'kb_mop'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==2? "v" : "-",
	'kb_implan'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==6? "v" : "-",
	'kb_suntik'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==3? "v" : "-",
	'kb_pil'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==7? "v" : "-",
	'kb_kondom'			=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==5? "v" : "-",
	'kb_tradisional'	=> isset($kb[$id]['berencana_II_4_kontrasepsi_sepsi']) && $kb[$id]['berencana_II_4_kontrasepsi_sepsi']==8? "v" : "-",
	'kb_tahun'			=> isset($kb[$id]['berencana_II_5_tahun']) && $kb[$id]['berencana_II_5_tahun']!=""? $kb[$id]['berencana_II_5_tahun'] : "-",
	'kb_bulan'			=> isset($kb[$id]['berencana_II_5_bulan']) && $kb[$id]['berencana_II_5_bulan']!=""? $kb[$id]['berencana_II_5_bulan'] : "-",
	'kb_ingin2'			=> isset($kb[$id]['berencana_II_6_anak_radio']) && $kb[$id]['berencana_II_6_anak_radio']==1? "v" : "-",
	'kb_ingin1'			=> isset($kb[$id]['berencana_II_6_anak_radio']) && $kb[$id]['berencana_II_6_anak_radio']==0? "v" : "-",
	'kb_ingintidak'		=> isset($kb[$id]['berencana_II_6_anak_radio']) && $kb[$id]['berencana_II_6_anak_radio']==2? "v" : "-",
	'kbno_hamil'		=> isset($kb[$id]['berencana_II_7_berkb_hamil_cebox']) && $kb[$id]['berencana_II_7_berkb_hamil_cebox']==1? "v" : "-",
	'kbno_fertilitas'	=> isset($kb[$id]['berencana_II_7_berkb_fertilasi_cebox']) && $kb[$id]['berencana_II_7_berkb_fertilasi_cebox']==1? "v" : "-",
	'kbno_tdksetuju'	=> isset($kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']) && $kb[$id]['berencana_II_7_berkb_tidaksetuju_cebox']==1? "v" : "-",
	'kbno_tdktahu'		=> isset($kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']) && $kb[$id]['berencana_II_7_berkb_tidaktahu_cebox']==1? "v" : "-",
	'kbno_takut'		=> isset($kb[$id]['berencana_II_7_berkb_efeksamping_cebox']) && $kb[$id]['berencana_II_7_berkb_efeksamping_cebox']==1? "v" : "-",
	'kbno_pelayanan'	=> isset($kb[$id]['berencana_II_7_berkb_pelayanan_cebox']) && $kb[$id]['berencana_II_7_berkb_pelayanan_cebox']==1? "v" : "-",
	'kbno_mahal'		=> isset($kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']) && $kb[$id]['berencana_II_7_berkb_tidakmampu_cebox']==1? "v" : "-",
	'kbno_lain'			=> isset($kb[$id]['berencana_II_7_berkb_lainya_cebox']) && $kb[$id]['berencana_II_7_berkb_lainya_cebox']==1? "v" : "-",
	'kbtmpt_rsu'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_tni'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_polri'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_swasta'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_umum'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_pusk'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_klinik'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_dokter'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_rspratama'	=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_pustu'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_poskes'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_bidan'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_bergerak'	=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'kbtmpt_lain'		=> isset($kb[$id]['berencana_II_8_pelayanan_radkb']) && $kb[$id]['berencana_II_8_pelayanan_radkb']==0? "v" : "-",
	'pem_pakaian_ya'	=> isset($pembangunan[$id]['pembangunan_III_1_radio']) && $pembangunan[$id]['pembangunan_III_1_radio']==0? "v" : "-",
	'pem_pakaian_tidak'	=> isset($pembangunan[$id]['pembangunan_III_1_radio']) && $pembangunan[$id]['pembangunan_III_1_radio']==1? "v" : "-",
	'pem_pakaian_tb'	=> isset($pembangunan[$id]['pembangunan_III_1_radio']) && $pembangunan[$id]['pembangunan_III_1_radio']==2? "v" : "-",
	'pem_makan_ya'		=> isset($pembangunan[$id]['pembangunan_III_2_radio']) && $pembangunan[$id]['pembangunan_III_2_radio']==0? "v" : "-",
	'pem_makan_tidak'	=> isset($pembangunan[$id]['pembangunan_III_2_radio']) && $pembangunan[$id]['pembangunan_III_2_radio']==1? "v" : "-",
	'pem_makan_tb'		=> isset($pembangunan[$id]['pembangunan_III_2_radio']) && $pembangunan[$id]['pembangunan_III_2_radio']==2? "v" : "-",
	'pem_obat_ya'		=> isset($pembangunan[$id]['pembangunan_III_3_radio']) && $pembangunan[$id]['pembangunan_III_3_radio']==0? "v" : "-",
	'pem_obat_tidak'	=> isset($pembangunan[$id]['pembangunan_III_3_radio']) && $pembangunan[$id]['pembangunan_III_3_radio']==1? "v" : "-",
	'pem_obat_tb'		=> isset($pembangunan[$id]['pembangunan_III_3_radio']) && $pembangunan[$id]['pembangunan_III_3_radio']==2? "v" : "-",
	'pem_beda_ya'		=> isset($pembangunan[$id]['pembangunan_III_4_radio']) && $pembangunan[$id]['pembangunan_III_4_radio']==0? "v" : "-",
	'pem_beda_tidak'	=> isset($pembangunan[$id]['pembangunan_III_4_radio']) && $pembangunan[$id]['pembangunan_III_4_radio']==1? "v" : "-",
	'pem_beda_tb'		=> isset($pembangunan[$id]['pembangunan_III_4_radio']) && $pembangunan[$id]['pembangunan_III_4_radio']==2? "v" : "-",
	'pem_daging_ya'		=> isset($pembangunan[$id]['pembangunan_III_5_radio']) && $pembangunan[$id]['pembangunan_III_5_radio']==0? "v" : "-",
	'pem_daging_tidak'	=> isset($pembangunan[$id]['pembangunan_III_5_radio']) && $pembangunan[$id]['pembangunan_III_5_radio']==1? "v" : "-",
	'pem_daging_tb'		=> isset($pembangunan[$id]['pembangunan_III_5_radio']) && $pembangunan[$id]['pembangunan_III_5_radio']==2? "v" : "-",
	'pem_ibadah_ya'		=> isset($pembangunan[$id]['pembangunan_III_6_radio']) && $pembangunan[$id]['pembangunan_III_6_radio']==0? "v" : "-",
	'pem_ibadah_tidak'	=> isset($pembangunan[$id]['pembangunan_III_6_radio']) && $pembangunan[$id]['pembangunan_III_6_radio']==1? "v" : "-",
	'pem_ibadah_tb'		=> isset($pembangunan[$id]['pembangunan_III_6_radio']) && $pembangunan[$id]['pembangunan_III_6_radio']==2? "v" : "-",
	'pem_kb_ya'			=> isset($pembangunan[$id]['pembangunan_III_7_radio']) && $pembangunan[$id]['pembangunan_III_7_radio']==0? "v" : "-",
	'pem_kb_tidak'		=> isset($pembangunan[$id]['pembangunan_III_7_radio']) && $pembangunan[$id]['pembangunan_III_7_radio']==1? "v" : "-",
	'pem_kb_tb'			=> isset($pembangunan[$id]['pembangunan_III_7_radio']) && $pembangunan[$id]['pembangunan_III_7_radio']==2? "v" : "-",
	'pem_tabung_ya'		=> isset($pembangunan[$id]['pembangunan_III_8_radio']) && $pembangunan[$id]['pembangunan_III_8_radio']==0? "v" : "-",
	'pem_tabung_tidak'	=> isset($pembangunan[$id]['pembangunan_III_8_radio']) && $pembangunan[$id]['pembangunan_III_8_radio']==1? "v" : "-",
	'pem_tabung_tb'		=> isset($pembangunan[$id]['pembangunan_III_8_radio']) && $pembangunan[$id]['pembangunan_III_8_radio']==2? "v" : "-",
	'pem_komunikasi_ya'	=> isset($pembangunan[$id]['pembangunan_III_9_radio']) && $pembangunan[$id]['pembangunan_III_9_radio']==0? "v" : "-",
	'pem_komunikasi_tidak'	=> isset($pembangunan[$id]['pembangunan_III_9_radio']) && $pembangunan[$id]['pembangunan_III_9_radio']==1? "v" : "-",
	'pem_komunikasi_tb'	=> isset($pembangunan[$id]['pembangunan_III_9_radio']) && $pembangunan[$id]['pembangunan_III_9_radio']==2? "v" : "-",
	'pem_sosial_ya'		=> isset($pembangunan[$id]['pembangunan_III_10_radio']) && $pembangunan[$id]['pembangunan_III_10_radio']==0? "v" : "-",
	'pem_sosial_tidak'	=> isset($pembangunan[$id]['pembangunan_III_10_radio']) && $pembangunan[$id]['pembangunan_III_10_radio']==1? "v" : "-",
	'pem_sosial_tb'		=> isset($pembangunan[$id]['pembangunan_III_10_radio']) && $pembangunan[$id]['pembangunan_III_10_radio']==2? "v" : "-",
	'pem_akses_ya'		=> isset($pembangunan[$id]['pembangunan_III_11_radio']) && $pembangunan[$id]['pembangunan_III_11_radio']==0? "v" : "-",
	'pem_akses_tidak'	=> isset($pembangunan[$id]['pembangunan_III_11_radio']) && $pembangunan[$id]['pembangunan_III_11_radio']==1? "v" : "-",
	'pem_akses_tb'		=> isset($pembangunan[$id]['pembangunan_III_11_radio']) && $pembangunan[$id]['pembangunan_III_11_radio']==2? "v" : "-",
	'pem_pengurus_ya'	=> isset($pembangunan[$id]['pembangunan_III_12_radio']) && $pembangunan[$id]['pembangunan_III_12_radio']==0? "v" : "-",
	'pem_pengurus_tidak'=> isset($pembangunan[$id]['pembangunan_III_12_radio']) && $pembangunan[$id]['pembangunan_III_12_radio']==1? "v" : "-",
	'pem_pengurus_tb'	=> isset($pembangunan[$id]['pembangunan_III_12_radio']) && $pembangunan[$id]['pembangunan_III_12_radio']==2? "v" : "-",
	'pem_posyandu_ya'	=> isset($pembangunan[$id]['pembangunan_III_13_radio']) && $pembangunan[$id]['pembangunan_III_13_radio']==0? "v" : "-",
	'pem_posyandu_tidak'=> isset($pembangunan[$id]['pembangunan_III_13_radio']) && $pembangunan[$id]['pembangunan_III_13_radio']==1? "v" : "-",
	'pem_posyandu_tb'	=> isset($pembangunan[$id]['pembangunan_III_13_radio']) && $pembangunan[$id]['pembangunan_III_13_radio']==2? "v" : "-",
	'pem_bkb_ya'		=> isset($pembangunan[$id]['pembangunan_III_14_radio']) && $pembangunan[$id]['pembangunan_III_14_radio']==0? "v" : "-",
	'pem_bkb_tidak'		=> isset($pembangunan[$id]['pembangunan_III_14_radio']) && $pembangunan[$id]['pembangunan_III_14_radio']==1? "v" : "-",
	'pem_bkb_tb'		=> isset($pembangunan[$id]['pembangunan_III_14_radio']) && $pembangunan[$id]['pembangunan_III_14_radio']==2? "v" : "-",
	'pem_bkr_ya'		=> isset($pembangunan[$id]['pembangunan_III_15_radio']) && $pembangunan[$id]['pembangunan_III_15_radio']==0? "v" : "-",
	'pem_bkr_tidak'		=> isset($pembangunan[$id]['pembangunan_III_15_radio']) && $pembangunan[$id]['pembangunan_III_15_radio']==1? "v" : "-",
	'pem_bkr_tb'		=> isset($pembangunan[$id]['pembangunan_III_15_radio']) && $pembangunan[$id]['pembangunan_III_15_radio']==2? "v" : "-",
	'pem_pik_ya'		=> isset($pembangunan[$id]['pembangunan_III_16_radio']) && $pembangunan[$id]['pembangunan_III_16_radio']==0? "v" : "-",
	'pem_pik_tidak'		=> isset($pembangunan[$id]['pembangunan_III_16_radio']) && $pembangunan[$id]['pembangunan_III_16_radio']==1? "v" : "-",
	'pem_pik_tb'		=> isset($pembangunan[$id]['pembangunan_III_16_radio']) && $pembangunan[$id]['pembangunan_III_16_radio']==2? "v" : "-",
	'pem_bkl_ya'		=> isset($pembangunan[$id]['pembangunan_III_17_radio']) && $pembangunan[$id]['pembangunan_III_17_radio']==0? "v" : "-",
	'pem_bkl_tidak'		=> isset($pembangunan[$id]['pembangunan_III_17_radio']) && $pembangunan[$id]['pembangunan_III_17_radio']==1? "v" : "-",
	'pem_bkl_tb'		=> isset($pembangunan[$id]['pembangunan_III_17_radio']) && $pembangunan[$id]['pembangunan_III_17_radio']==2? "v" : "-",
	'pem_uppks_ya'		=> isset($pembangunan[$id]['pembangunan_III_18_radio']) && $pembangunan[$id]['pembangunan_III_18_radio']==0? "v" : "-",
	'pem_uppks_tidak'	=> isset($pembangunan[$id]['pembangunan_III_18_radio']) && $pembangunan[$id]['pembangunan_III_18_radio']==1? "v" : "-",
	'pem_uppks_tb'		=> isset($pembangunan[$id]['pembangunan_III_18_radio']) && $pembangunan[$id]['pembangunan_III_18_radio']==2? "v" : "-",
	'pem_atap_daun'		=> isset($pembangunan[$id]['pembangunan_III_1_19_cebo4']) && $pembangunan[$id]['pembangunan_III_1_19_cebo4']==0? "v" : "-",
	'pem_atap_seng'		=> isset($pembangunan[$id]['pembangunan_III_2_19_cebo4']) && $pembangunan[$id]['pembangunan_III_2_19_cebo4']==1? "v" : "-",
	'pem_atap_genteng'	=> isset($pembangunan[$id]['pembangunan_III_3_19_cebo4']) && $pembangunan[$id]['pembangunan_III_3_19_cebo4']==2? "v" : "-",
	'pem_atap_lain'		=> isset($pembangunan[$id]['pembangunan_III_4_19_cebo4']) && $pembangunan[$id]['pembangunan_III_4_19_cebo4']==3? "v" : "-",
	'pem_dinding_tembok'=> isset($pembangunan[$id]['pembangunan_III_1_20_cebo4']) && $pembangunan[$id]['pembangunan_III_1_20_cebo4']==0? "v" : "-",
	'pem_dinding_kayu'	=> isset($pembangunan[$id]['pembangunan_III_2_20_cebo4']) && $pembangunan[$id]['pembangunan_III_2_20_cebo4']==1? "v" : "-",
	'pem_dinding_bambu'	=> isset($pembangunan[$id]['pembangunan_III_3_20_cebo4']) && $pembangunan[$id]['pembangunan_III_3_20_cebo4']==2? "v" : "-",
	'pem_dinding_lain'	=> isset($pembangunan[$id]['pembangunan_III_4_20_cebo4']) && $pembangunan[$id]['pembangunan_III_4_20_cebo4']==3? "v" : "-",
	'pem_lantai_ubin'	=> isset($pembangunan[$id]['pembangunan_III_1_21_cebo4']) && $pembangunan[$id]['pembangunan_III_1_21_cebo4']==0? "v" : "-",
	'pem_lantai_semen'	=> isset($pembangunan[$id]['pembangunan_III_2_21_cebo4']) && $pembangunan[$id]['pembangunan_III_2_21_cebo4']==1? "v" : "-",
	'pem_lantai_tanah'	=> isset($pembangunan[$id]['pembangunan_III_3_21_cebo4']) && $pembangunan[$id]['pembangunan_III_3_21_cebo4']==2? "v" : "-",
	'pem_lantai_lain'	=> isset($pembangunan[$id]['pembangunan_III_4_21_cebo4']) && $pembangunan[$id]['pembangunan_III_4_21_cebo4']==3? "v" : "-",
	'pem_terang_listrik'=> isset($pembangunan[$id]['pembangunan_III_22_1_cebo4']) && $pembangunan[$id]['pembangunan_III_22_1_cebo4']==0? "v" : "-",
	'pem_terang_genset'	=> isset($pembangunan[$id]['pembangunan_III_22_2_cebo4']) && $pembangunan[$id]['pembangunan_III_22_2_cebo4']==1? "v" : "-",
	'pem_terang_minyak'	=> isset($pembangunan[$id]['pembangunan_III_22_3_cebo4']) && $pembangunan[$id]['pembangunan_III_22_3_cebo4']==2? "v" : "-",
	'pem_terang_lain'	=> isset($pembangunan[$id]['pembangunan_III_22_4_cebo4']) && $pembangunan[$id]['pembangunan_III_22_4_cebo4']==3? "v" : "-",
	'pem_minum_ledeng'	=> isset($pembangunan[$id]['pembangunan_III_23_1_cebo4']) && $pembangunan[$id]['pembangunan_III_23_1_cebo4']==0? "v" : "-",
	'pem_minum_sumur'	=> isset($pembangunan[$id]['pembangunan_III_23_2_cebo4']) && $pembangunan[$id]['pembangunan_III_23_2_cebo4']==1? "v" : "-",
	'pem_minum_hujan'	=> isset($pembangunan[$id]['pembangunan_III_23_3_cebo4']) && $pembangunan[$id]['pembangunan_III_23_3_cebo4']==2? "v" : "-",
	'pem_minum_lain'	=> isset($pembangunan[$id]['pembangunan_III_23_4_cebo4']) && $pembangunan[$id]['pembangunan_III_23_4_cebo4']==3? "v" : "-",
	'pem_bakar_listrik'	=> isset($pembangunan[$id]['pembangunan_III_24_1_cebo4']) && $pembangunan[$id]['pembangunan_III_24_1_cebo4']==0? "v" : "-",
	'pem_bakar_minyak'	=> isset($pembangunan[$id]['pembangunan_III_24_2_cebo4']) && $pembangunan[$id]['pembangunan_III_24_2_cebo4']==1? "v" : "-",
	'pem_bakar_arang'	=> isset($pembangunan[$id]['pembangunan_III_24_3_cebo4']) && $pembangunan[$id]['pembangunan_III_24_3_cebo4']==2? "v" : "-",
	'pem_bakar_lain'	=> isset($pembangunan[$id]['pembangunan_III_24_4_cebo4']) && $pembangunan[$id]['pembangunan_III_24_4_cebo4']==3? "v" : "-",
	'pem_bab_sendiri'	=> isset($pembangunan[$id]['pembangunan_III_25_radi4']) && $pembangunan[$id]['pembangunan_III_25_radi4']==0? "v" : "-",
	'pem_bab_bersama'	=> isset($pembangunan[$id]['pembangunan_III_25_radi4']) && $pembangunan[$id]['pembangunan_III_25_radi4']==1? "v" : "-",
	'pem_bab_umum'		=> isset($pembangunan[$id]['pembangunan_III_25_radi4']) && $pembangunan[$id]['pembangunan_III_25_radi4']==2? "v" : "-",
	'pem_bab_lain'		=> isset($pembangunan[$id]['pembangunan_III_25_radi4']) && $pembangunan[$id]['pembangunan_III_25_radi4']==3? "v" : "-",
	'pem_rumah_sendiri'	=> isset($pembangunan[$id]['pembangunan_III_26_1_cebo4']) && $pembangunan[$id]['pembangunan_III_26_1_cebo4']==0? "v" : "-",
	'pem_rumah_sewa'	=> isset($pembangunan[$id]['pembangunan_III_26_2_cebo4']) && $pembangunan[$id]['pembangunan_III_26_2_cebo4']==1? "v" : "-",
	'pem_rumah_numpang'	=> isset($pembangunan[$id]['pembangunan_III_26_3_cebo4']) && $pembangunan[$id]['pembangunan_III_26_3_cebo4']==2? "v" : "-",
	'pem_rumah_lain'	=> isset($pembangunan[$id]['pembangunan_III_26_4_cebo4']) && $pembangunan[$id]['pembangunan_III_26_4_cebo4']==3? "v" : "-",
	'pem_luas'			=> isset($pembangunan[$id]['pembangunan_III_27_luas']) && $pembangunan[$id]['pembangunan_III_27_luas']!=""? $pembangunan[$id]['pembangunan_III_27_luas'] : "-",
	'pem_menetap'		=> isset($pembangunan[$id]['pembangunan_III_28_orang']) && $pembangunan[$id]['pembangunan_III_28_orang']!=""? $pembangunan[$id]['pembangunan_III_28_orang'] : "-",
	'kesehatan_1_g_1_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_a_cebox']==1? "v" : "-",
	'kesehatan_1_g_1_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_b_cebox']==1? "v" : "-",
	'kesehatan_1_g_1_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_c_cebox']==1? "v" : "-",
	'kesehatan_1_g_1_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_d_cebox']==1? "v" : "-",
	'kesehatan_1_g_1_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_e_cebox']==1? "v" : "-",
	'kesehatan_1_g_1_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_f_cebox']==1? "v" : "-",
	'kesehatan_bab0'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==0? "v" : "-",
	'kesehatan_bab1'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==1? "v" : "-",
	'kesehatan_bab2'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==2? "v" : "-",
	'kesehatan_bab4'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_radi5']==4? "v" : "-",
	'kes_sakit_gigi_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']==1? "Ya" : "-",
	'kes_sakit_gigi_tidak'	=> !isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']) || $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_f_cebox']!=1? "Tidak" : "-",
	'kesehatan_1_g_4_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_a_cebox']==1? "v" : "-",
	'kesehatan_1_g_4_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_b_cebox']==1? "v" : "-",
	'kesehatan_1_g_4_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_c_cebox']==1? "v" : "-",
	'kesehatan_1_g_4_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_d_cebox']==1? "v" : "-",
	'kesehatan_1_g_4_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_e_cebox']==1? "v" : "-",
	'kesehatan_1_g_4_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_4_f_cebox']==1? "v" : "-",
	'kes_rokok0'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==0? "v" : "-",
	'kes_rokok1'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==1? "v" : "-",
	'kes_rokok2'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==2? "v" : "-",
	'kes_rokok3'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==3? "v" : "-",
	'kes_rokok4'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_1_radi5']==4? "v" : "-",
	'kes_rokok_setiap'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_1_g_2_text'] : "-",
	'kes_rokok_pertama'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_1_g_3_text'] : "-",
	'pneumonia_pernah0'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==0? "v" : "-",
	'pneumonia_pernah1'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==1? "v" : "-",
	'pneumonia_pernah2'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==2? "v" : "-",
	'pneumonia_pernah3'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radi4']==3? "v" : "-",
	'pneumonia_gejala0'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==0? "v" : "-",
	'pneumonia_gejala1'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==1? "v" : "-",
	'pneumonia_gejala2'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==2? "v" : "-",
	'pneumonia_gejala3'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radi4']==3? "v" : "-",
	'kesehatan_2_g_3_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_cebox']==1? "v" : "-",
	'kesehatan_2_g_3_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_cebox']==1? "v" : "-",
	'kesehatan_2_g_3_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_cebox']==1? "v" : "-",
	'kes_ginjal_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==0? "Ya" : "-",
	'kes_ginjal_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==1? "Tidak" : "-",
	'kes_batu_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_radio']==0? "Ya" : "-",
	'kes_batu_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_radio']==1? "Tidak" : "-",
	'kes_tb_batuk0'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==0? "v" : "-",
	'kes_tb_batuk1'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==1? "v" : "-",
	'kes_tb_batuk2'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_1_tb_radi3']==2? "v" : "-",
	'kesehatan_2_g_2_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_a_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_b_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_c_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_d_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_e_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_e_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_f_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_f_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_g_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_g_cebox']==1? "v" : "-",
	'kesehatan_2_g_2_h_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_2_h_cebox']==1? "v" : "-",
	'kesehatan_2_g_3_a_tb_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_a_tb_cebox']==1? "v" : "-",
	'kesehatan_2_g_3_b_tb_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_b_tb_cebox']==1? "v" : "-",
	'kesehatan_2_g_3_c_tb_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_3_c_tb_cebox']==1? "v" : "-",
	'kesehatan_2_g_4_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_a_cebox']==1? "v" : "-",
	'kesehatan_2_g_4_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_2_g_4_b_cebox']==1? "v" : "-",
	'kanker_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']==0? "Ya" : "-",
	'kanker_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_kk_radio']==1? "Tidak" : "-",
	'kanker_thn'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_kk_text'] : "-",
	'kesehatan_3_g_3_kk_a_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_a_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_kk_b_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_b_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_kk_c_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_c_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_kk_d_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_d_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_kk_e_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_e_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_kk_f_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_f_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_kk_g_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_kk_g_cebox']==1? "v" : "-",
	'kanker_tes_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']==0? "Ya" : "-",
	'kanker_tes_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_radio']==1? "Tidak" : "-",
	'kesehatan_3_g_5_kk_a_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_a_cebox']==1? "v" : "-",
	'kesehatan_3_g_5_kk_b_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_b_cebox']==1? "v" : "-",
	'kesehatan_3_g_5_kk_c_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_kk_c_cebox']==1? "v" : "-",
	'kesehatan_3_g_d_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_3_g_d_text'] : "-",
	'ppok_pernah_ya'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']==0? "Ya" : "-",
	'ppok_pernah_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_1_radio']==1? "Tidak" : "-",
	'kesehatan_3_g_2_sn_a_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_a_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_b_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_b_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_c_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_c_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_d_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_d_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_e_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_e_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_f_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_f_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_g_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_g_cebox']==1? "v" : "-",
	'kesehatan_3_g_2_sn_h_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_2_sn_h_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_mg_a_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_a_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_mg_b_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_b_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_mg_c_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_c_cebox']==1? "v" : "-",
	'kesehatan_3_g_3_mg_d_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_3_mg_d_cebox']==1? "v" : "-",
	'kesehatan_3_g_4_mg_d_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_3_g_4_mg_d_text'] : "-",
	'ppok_kambuh_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']==0? "Ya" : "-",
	'ppok_kambuh_tidak'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_3_g_5_radio']==1? "Tidak" : "-",
	'diabet_diagnosa_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']==0? "Ya" : "-",
	'diabet_diagnosa_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_radio']==1? "Tidak" : "-",
	'kesehatan_4_g_2_p_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_a_cebox']==1? "v" : "-",
	'kesehatan_4_g_2_p_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_b_cebox']==1? "v" : "-",
	'kesehatan_4_g_2_p_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_c_cebox']==1? "v" : "-",
	'kesehatan_4_g_2_p_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_p_d_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_p_a_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_a_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_p_b_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_b_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_p_c_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_c_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_p_d_cebox'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_p_d_cebox']==1? "v" : "-",
	'darting_diagnosa_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']==0? "Ya" : "-",
	'darting_diagnosa_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_hp_radio']==1? "Tidak" : "-",
	'kesehatan_4_g_2_hp_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_hp_text'] : "-",
	'darting_obat_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']==0? "Ya" : "-",
	'darting_obat_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_hp_radio']==1? "Tidak" : "-",
	'jantung_diagnosa_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']==0? "Ya" : "-",
	'jantung_diagnosa_tidak'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_jk_radio']==1? "Tidak" : "-",
	'kesehatan_4_g_2_jk_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_jk_text'] : "-",
	'kesehatan_4_g_3_jk_a_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_a_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_jk_b_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_b_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_jk_c_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_c_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_jk_d_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_jk_d_cebox']==1? "v" : "-",
	'stroke_diagnosa_ya'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']==0? "Ya" : "-",
	'stroke_diagnosa_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_1_sk_radio']==1? "Tidak" : "-",
	'kesehatan_4_g_2_sk_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_4_g_2_sk_text'] : "-",
	'kesehatan_4_g_3_sk_a_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_a_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_sk_b_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_b_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_sk_c_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_c_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_sk_d_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_d_cebox']==1? "v" : "-",
	'kesehatan_4_g_3_sk_e_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_4_g_3_sk_e_cebox']==1? "v" : "-",
	'kesehatan_5_g_1_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_1_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_1_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_2_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_2_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_2_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_3_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_3_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_3_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_4_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_4_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_4_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_5_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_5_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_5_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_6_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_6_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_6_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_7_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_7_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_7_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_8_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_8_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_8_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_9_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_9_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_9_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_10_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_10_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_10_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_11_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_11_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_11_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_12_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_12_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_12_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_13_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_13_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_13_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_14_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_14_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_14_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_15_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_15_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_15_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_17_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_17_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_17_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_18_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_18_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_18_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_19_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_19_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_19_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_20_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_20_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_20_kk_cebox']==1? "v" : "-",
	'kesehatan_5_g_23_kk_cebox'=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_23_kk_cebox']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_23_kk_cebox']==1? "v" : "-",
	'semua_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']==0? "Ya" : "-",
	'semua_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_21_radio']==1? "Tidak" : "-",
	'pernah_ya'			=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']==0? "Ya" : "-",
	'pernah_tidak'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_5_g_22_radio']==1? "Tidak" : "-",
	'stat_imunisasi'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==1? "Lengkap" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==2? "Tidak tahu" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==2? "Lengkap sesuai umur" : (isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_1_radi4']==3? "Tidak lengkap" : "-"))),
	'kesehatan_6_g_2_ol_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_ol_text'] : "-",
	'kesehatan_6_g_2_td_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_2_td_text'] : "-",
	'kesehatan_6_g_3_td_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_td_text'] : "-",
	'kesehatan_6_g_3_tn_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_tn_text'] : "-",
	'kesehatan_6_g_3_p_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_p_text'] : "-",
	'kesehatan_6_g_3_s_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_3_s_text'] : "-",
	'kesehatan_6_g_4_at_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_at_text'] : "-",
	'kesehatan_6_g_4_bb_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_bb_text'] : "-",
	'kesehatan_6_g_4_sg_text'	=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_4_sg_text'] : "-",
	'kesehatan_6_g_5_radio'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']==0? "Ya" : "-",
	'kesehatan_6_g_5_radio'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_5_radio']==0? "Ya" : "-",
	'kesehatan_6_g_6_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_6_text'] : "-",
	'kesehatan_6_g_7_text'		=> isset($anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']) && $anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text']!=""? $anggota_pr[$id][$no_anggota]['kesehatan_6_g_7_text'] : "-",
);
		}

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
		$template = $dir.'public/files/template/data_all.xlsx';		
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
		
		$tanggal_export = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tanggal_export' => $tanggal_export,'kd_kab' => $kd_kab,'rw' => $rukunwarga,'rt' => $rukunrumahtangga);
		
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
}
