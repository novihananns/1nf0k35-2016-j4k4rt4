<?php
class Export_data extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('eform/datakeluarga_model');
		$this->load->model('eform/pembangunan_keluarga_model');
		$this->load->model('eform/anggota_keluarga_kb_model');
		$this->load->model('eform/dataform_model');
		$this->load->model('eform/laporan_kpldh_model');
		$this->load->model('admin_model');
		
		$this->load->model('inventory/laporan_kpldh_model');

		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
	}

	
	function pilih_export($judul=0){
		$judul = $this->input->post("judul");
		$kecamatan = $this->input->post("kecamatan");
		$kelurahan = $this->input->post("kelurahan");
		$rw = $this->input->post("rw");
		$rt = $this->input->post("rt");
		if($judul=="Distribusi Penduduk Berdasarkan Jenis Kelamin"){
			$this->exportdatakelamin($kecamatan,$kelurahan,$rw,$rt);
		}else if($judul=="Distribusi Penduduk Menurut Usia"){
			$this->exportdatausia($kecamatan,$kelurahan,$rw,$rt);
		}else if($judul=="Distribusi Penduduk Menurut Tingkat Pendidikan"){
			$this->exportdatapendidikan($kecamatan,$kelurahan,$rw,$rt);
		}else if($judul=="Distribusi Penduduk Berdasarkan Pekerjaan"){
			$this->exportdatapekerjaan($kecamatan,$kelurahan,$rw,$rt);
		}else{
			return $judul;
		}
	}
	public function exportdatakelamin($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
	{
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$bar = array();
		$data_total['jumlahorang'] = $this->laporan_kpldh_model->jumlahorang($kecamatan,$kelurahan,$rw,$rt);
		$jmlkelamin = $this->laporan_kpldh_model->get_jum_kelamin($kecamatan,$kelurahan,$rw,$rt);
		$no = 1;
		foreach ($jmlkelamin as $row) {
			$bar[]=array(
				'no' 		=> $no++,
				'kelamin' 	=> $row->kelamin,
				'jumlah' 	=> $row->jumlah,
				'totalkel' 	=> number_format($row->jumlah/$data_total['jumlahorang']*100,2).' %',
			);
		}
		//$data['jumlahorang'] = $this->laporan_kpldh_model->jumlahorang($kecamatan,$kelurahan,$rw,$rt);
		//print_r($bar);
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->laporan_kpldh_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->laporan_kpldh_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->laporan_kpldh_model->get_nama('nama','cl_kec','code',$kecamatan);
		$kd_kel  = $this->laporan_kpldh_model->get_nama('value','cl_village','code',$kelurahan);
		$nama 	 = $this->laporan_kpldh_model->get_nama('value','cl_phc','code',$kode_sess);
		$tanggal = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_kel' => $kd_kel,'rt' => $rt,'rw' => $rw,'tanggal_export'=>$tanggal);
		//print_r($bar);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/laporan/jeniskelamin.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
		$TBS->MergeBlock('a', $bar);
		$TBS->MergeBlock('b', $data_puskesmas);
		$TBS->MergeBlock('c', $data_total);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_datakelamin_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	public function exportdatausia($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
	{

		/*$datapuskesmas = $this->laporan_kpldh_model->get_datawhere($kecamatan,'code','cl_kec');
		foreach ($datapuskesmas as $row) {
			$bar[$row->code]['puskesmas'] = $row->nama;
		}*/
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$bar = array();
		$kecamatan = substr($this->session->userdata("puskesmas"), 0,7);
		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw,$rt);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$dataor['total'] = $row->totalorang;
				$dataor['puskesmas'] = $this->laporan_kpldh_model->get_nama('nama','cl_kec','code',$kecamatan);
			}
		}
		
		$infant = $this->laporan_kpldh_model->get_nilai_infant($kecamatan,$kelurahan,$rw,$rt);
		foreach ($infant as $row) {
			$bar[0]['no'] = '1';
			$bar[0]['nama'] = 'Infant (0-12 bulan)';
			$bar[0]['jumlah'] = $row->jumlah;
			$bar[0]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
		}
		
		$toddler = $this->laporan_kpldh_model->get_nilai_usia('1','3',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($toddler as $row) {
			$bar[1]['no'] = '2';
			$bar[1]['totalkel'] = number_format($row->jumlah / $dataor['total']*100,2).' %';
			$bar[1]['nama'] = 'Toddler (1-3 tahun)';
			$bar[1]['jumlah'] = $row->jumlah;
		}


		$Preschool = $this->laporan_kpldh_model->get_nilai_usia('4','5',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($Preschool as $row) {
			$bar[2]['no'] = '3';
			$bar[2]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
			$bar[2]['nama'] = 'Preschool (4 tahun-5 tahun)';
			$bar[2]['jumlah'] = $row->jumlah;
		}

		$sekolah = $this->laporan_kpldh_model->get_nilai_usia('6','12',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($sekolah as $row) {
			$bar[3]['no'] = '4';
			$bar[3]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
			$bar[3]['nama'] = 'Usia sekolah (6 tahun- 12 tahun)';
			$bar[3]['jumlah'] = $row->jumlah;
		}


		$remaja = $this->laporan_kpldh_model->get_nilai_usia('13','20',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($remaja as $row) {
			$bar[4]['no'] = '5';
			$bar[4]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
			$bar[4]['nama'] = 'Remaja ( 13 tahun-20 tahun)';
			$bar[4]['jumlah'] = $row->jumlah;
		}

		$dewasa = $this->laporan_kpldh_model->get_nilai_usia('21','44',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($dewasa as $row) {
			$bar[5]['no'] = '6';
			$bar[5]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
			$bar[5]['nama'] = 'Dewasa (21 tahun-44 tahun)';
			$bar[5]['jumlah'] = $row->jumlah;
		}


		$prelansia = $this->laporan_kpldh_model->get_nilai_usia('45','59',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($prelansia as $row) {
			$bar[6]['no'] = '7';
			$bar[6]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
			$bar[6]['nama'] = 'Prelansia (45 tahun-59 tahun)';
			$bar[6]['jumlah'] = $row->jumlah;
		}

		$lansia = $this->laporan_kpldh_model->get_nilai_lansia('60',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($lansia as $row) {
			$bar[7]['no'] = '8';
			$bar[7]['totalkel'] = number_format($row->jumlah/$dataor['total']*100,2).' %';
			$bar[7]['nama'] = 'Lansia (>60 tahun)';
			$bar[7]['jumlah'] = $row->jumlah;
		}
		 //print_r($bar);
		// die();
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->laporan_kpldh_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->laporan_kpldh_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->laporan_kpldh_model->get_nama('nama','cl_kec','code',$kecamatan);
		$kd_kel  = $this->laporan_kpldh_model->get_nama('value','cl_village','code',$kelurahan);
		$nama 	 = $this->laporan_kpldh_model->get_nama('value','cl_phc','code',$kode_sess);
		$tanggal = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_kel' => $kd_kel,'rt' => $rt,'rw' => $rw,'tanggal_export'=>$tanggal);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/laporan/datausia.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
		$TBS->MergeBlock('a', $bar);
		$TBS->MergeBlock('b', $data_puskesmas);
		//$TBS->MergeBlock('c', $data_total);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_datausia_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	public function exportdatapendidikan($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
	{
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw,$rt);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$bardata['totalorang'] = $row->totalorang;
				$bardata['puskesmas'] = $this->laporan_kpldh_model->get_nama('nama','cl_kec','code',$kecamatan);
			}
		}

		$blm_sekolah = $this->laporan_kpldh_model->get_jml_pendidikan('40',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($blm_sekolah as $row) {
			$bar[0]['no'] = '1';
			$bar[0]['nama'] = 'Belum Sekolah';
			$bar[0]['jumlah'] = $row->jumlah;
			$bar[0]['totalkel'] =number_format( $row->jumlah / $bardata['totalorang']*100,2).' %';
		}

		$tidak_sekolah = $this->laporan_kpldh_model->get_jml_pendidikan('41',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($tidak_sekolah as $row) {
			$bar[1]['no'] = '2';
			$bar[1]['nama'] = 'Tidak Sekolah';
			$bar[1]['jumlah'] = $row->jumlah;
			$bar[1]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}

		$tdk_tamatsd = $this->laporan_kpldh_model->get_jml_pendidikan('14',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($tdk_tamatsd as $row) {
			$bar[2]['no'] = '3';
			$bar[2]['nama'] = 'Tidak Tamat SD';
			$bar[2]['jumlah'] = $row->jumlah;
			$bar[2]['totalkel'] = number_format( $row->jumlah / $bardata['totalorang']*100,2).' %';
		}


		$masih_sd = $this->laporan_kpldh_model->get_jml_pendidikan('15',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($masih_sd as $row) {
			$bar[3]['no'] = '4';
			$bar[3]['nama'] = 'Masih SD';
			$bar[3]['jumlah'] = $row->jumlah;
			$bar[3]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}

		$tamat_sd = $this->laporan_kpldh_model->get_jml_pendidikan('16',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($tamat_sd as $row) {
			$bar[4]['no'] = '5';
			$bar[4]['nama'] = 'Tamat SD';
			$bar[4]['jumlah'] = $row->jumlah;
			$bar[4]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}


		$masih_smp = $this->laporan_kpldh_model->get_jml_pendidikan('17',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($masih_smp as $row) {
			$bar[5]['no'] = '6';
			$bar[5]['nama'] = 'Masih SMP';
			$bar[5]['jumlah'] = $row->jumlah;
			$bar[5]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}

		$tamat_smp = $this->laporan_kpldh_model->get_jml_pendidikan('18',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($tamat_smp as $row) {
			$bar[6]['no'] = '7';
			$bar[6]['nama'] = 'Tamat SMP';
			$bar[6]['jumlah'] = $row->jumlah;
			$bar[6]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}


		$masih_sma = $this->laporan_kpldh_model->get_jml_pendidikan('19',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($masih_sma as $row) {
			$bar[7]['no'] = '8';
			$bar[7]['nama'] = 'Masih SMA';
			$bar[7]['jumlah'] = $row->jumlah;
			$bar[7]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}

		$tamat_sma = $this->laporan_kpldh_model->get_jml_pendidikan('20',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($tamat_sma as $row) {
			$bar[8]['no'] = '9';
			$bar[8]['nama'] = 'Tamat SMA';
			$bar[8]['jumlah'] = $row->jumlah;
			$bar[8]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}

		$masih_pt = $this->laporan_kpldh_model->get_jml_pendidikan('21',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($masih_pt as $row) {
			$bar[9]['no'] = '10';
			$bar[9]['nama'] = 'Masih PT/Akademi';
			$bar[9]['jumlah'] = $row->jumlah;
			$bar[9]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pendidikan('22',$kecamatan,$kelurahan,$rw,$rt);
		foreach ($tamat_pt as $row) {
			$bar[10]['no'] = '11';
			$bar[10]['nama'] = 'Tamat PT/Akademi';
			$bar[10]['jumlah'] = $row->jumlah;
			$bar[10]['totalkel'] = number_format( $row->jumlah /$bardata['totalorang']*100,2).' %';
		}
		
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->laporan_kpldh_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->laporan_kpldh_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->laporan_kpldh_model->get_nama('nama','cl_kec','code',$kecamatan);
		$kd_kel  = $this->laporan_kpldh_model->get_nama('value','cl_village','code',$kelurahan);
		$nama 	 = $this->laporan_kpldh_model->get_nama('value','cl_phc','code',$kode_sess);
		$tanggal = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_kel' => $kd_kel,'rt' => $rt,'rw' => $rw,'tanggal_export'=>$tanggal);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/laporan/datapendidikan.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
		$TBS->MergeBlock('a', $bar);
		$TBS->MergeBlock('b', $data_puskesmas);
		//$TBS->MergeBlock('c', $data_total);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_datausia_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	public function datapekerjaan($kecamatan=0,$kelurahan=0,$rw=0,$rt=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$bardata['totalorang'] = $row->totalorang;
				$bardata['puskesmas'] = $row->nama_kecamatan;
			}
		}

		$blm_sekolah = $this->laporan_kpldh_model->get_jml_pekerjaan('24',$kecamatan,$kelurahan,$rw);
		foreach ($blm_sekolah as $row) {
			$bar[0]['petani'] = $row->jumlah;
			$bar[0]['totalpetani'] = $row->total;
		}

		$tidak_sekolah = $this->laporan_kpldh_model->get_jml_pekerjaan('25',$kecamatan,$kelurahan,$rw);
		foreach ($tidak_sekolah as $row) {
			$bar[1]['nelayan'] = $row->jumlah;
			$bar[1]['totalnelayan'] = $row->total;
		}

		$tdk_tamatsd = $this->laporan_kpldh_model->get_jml_pekerjaan('26',$kecamatan,$kelurahan,$rw);
		foreach ($tdk_tamatsd as $row) {
			$bar[2]['pnstniporli'] = $row->jumlah;
			$bar[2]['totalpnstniporli'] = $row->total;
		}


		$masih_sd = $this->laporan_kpldh_model->get_jml_pekerjaan('27',$kecamatan,$kelurahan,$rw);
		foreach ($masih_sd as $row) {
			$bar[3]['swasta'] = $row->jumlah;
			$bar[3]['totalswasta'] = $row->total;
		}

		$tamat_sd = $this->laporan_kpldh_model->get_jml_pekerjaan('28',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_sd as $row) {
			$bar[4]['wiraswasta'] = $row->jumlah;
			$bar[4]['totalwiraswasta'] = $row->total;
		}


		$masih_smp = $this->laporan_kpldh_model->get_jml_pekerjaan('29',$kecamatan,$kelurahan,$rw);
		foreach ($masih_smp as $row) {
			$bar[5]['pensiunan'] = $row->jumlah;
			$bar[5]['totalpensiunan'] = $row->total;
		}

		$tamat_smp = $this->laporan_kpldh_model->get_jml_pekerjaan('30',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_smp as $row) {
			$bar[6]['pekerjalepas'] = $row->jumlah;
			$bar[6]['totalpekerjalepas'] = $row->total;
		}


		$masih_sma = $this->laporan_kpldh_model->get_jml_pekerjaan('31',$kecamatan,$kelurahan,$rw);
		foreach ($masih_sma as $row) {
			$bar[7]['lainnya'] = $row->jumlah;
			$bar[7]['totallainnya'] = $row->total;
		}

		$tamat_sma = $this->laporan_kpldh_model->get_jml_pekerjaan('32',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_sma as $row) {
			$bar[8]['tidakbelumkerja'] = $row->jumlah;
			$bar[8]['totaltidakbelumkerja'] = $row->total;
		}

		$masih_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('42',$kecamatan,$kelurahan,$rw);
		foreach ($masih_pt as $row) {
			$bar[9]['bekerja'] = $row->jumlah;
			$bar[9]['totalbekerja'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('43',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[10]['belumkerja'] = $row->jumlah;
			$bar[10]['totalbelumkerja'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('44',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[11]['tidakkerja'] = $row->jumlah;
			$bar[11]['totaltidakkerja'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('45',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[12]['irt'] = $row->jumlah;
			$bar[12]['irt'] = $row->total;
		}
		
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->laporan_kpldh_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->laporan_kpldh_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->laporan_kpldh_model->get_nama('nama','cl_kec','code',$kecamatan);
		$kd_kel  = $this->laporan_kpldh_model->get_nama('value','cl_village','code',$kelurahan);
		$nama 	 = $this->laporan_kpldh_model->get_nama('value','cl_phc','code',$kode_sess);
		$tanggal = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_kel' => $kd_kel,'rt' => $rt,'rw' => $rw,'tanggal_export'=>$tanggal);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/laporan/datausia.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
		$TBS->MergeBlock('a', $bar);
		$TBS->MergeBlock('b', $data_puskesmas);
		//$TBS->MergeBlock('c', $data_total);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_datausia_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	
}
