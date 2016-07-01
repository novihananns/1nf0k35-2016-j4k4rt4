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
	'kb_suami'			=> isset($kb[$id]['berencana_II_1_suami']) && $kb[$id]['berencana_II_1_suami']!=""? $kb[$id]['berencana_II_1_suami'] : "0",
	'kb_istri'			=> isset($kb[$id]['berencana_II_1_istri']) && $kb[$id]['berencana_II_1_istri']!=""? $kb[$id]['berencana_II_1_istri'] : "0",
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
		$datajml = $this->datakeluarga_model->datajml();

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
		 $this->dataform_model->insertdataform_profile();
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
						$this->session->set_userdata('filter_code_cl_tahundata','');
						$this->session->set_userdata('filter_code_cl_bulandata','');
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
