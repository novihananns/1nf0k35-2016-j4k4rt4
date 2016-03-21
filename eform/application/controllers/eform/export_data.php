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
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('inventory/inv_barang_model');

		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
	}

	
	function pilih_export($judul=0){
		$judul = $this->input->post("judul");
		$kecamatan = $this->input->post("kecamatan");
		$kelurahan = $this->input->post("kelurahan");
		$rw = $this->input->post("rw");
		if($judul=="Distribusi Penduduk Berdasarkan Jenis Kelamin"){
			$this->exportdatakelamin($kecamatan,$kelurahan,$rw);
		}else if($judul=="Distribusi Penduduk Menurut Usia"){
			$this->exportdatausia($kecamatan,$kelurahan,$rw);
		}else if($judul=="Distribusi Penduduk Menurut Tingkat Pendidikan"){
			$this->exportdatapendidikan($kecamatan,$kelurahan,$rw);
		}else if($judul=="Distribusi Penduduk Berdasarkan Pekerjaan"){
			$this->exportdatapekerjaan($kecamatan,$kelurahan,$rw);
		}else{
			return $judul;
		}
	}
	public function exportdatakelamin($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		$jmlkelamin = $this->laporan_kpldh_model->get_jum_kelamin($kecamatan,$kelurahan,$rw);
		//$total = 0;
		$no=1;
		foreach ($jmlkelamin as $row) {
			$bar[]=array(
				'no' =>$no++, 
				'kelamin' => $row->kelamin, 
				'jumlah' => $row->jumlah, 
				'persen' => number_format($row->jumlah/$row->total*100,2)
			);
		}
		print_r($bar);
		die();
		$data_total =array();
		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw);
		foreach ($totalorang as $rows) {
			if ($rows->totalorang!=0) {
			$data_total[]=array(
				'total' => $rows->totalorang,
				'puskesmas' => $rows->nama_kecamatan,
				'totalpersen' => $rows->totalorang/$rows->totalorang*100,
			);
			}
		}
		$data['color']	= $color;

		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',$kecamatan);
		$kd_kel  = $this->inv_barang_model->get_nama('value','cl_village','code',$kelurahan);
		$nama 	 = $this->inv_barang_model->get_nama('value','cl_phc','code',$kode_sess);
		$tanggal = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'provinsi' => $kd_prov,'kabkota' => $kd_kab,'kd_kec' => $kd_kec,'kd_kel' => $kd_kel,'rw' => $rw,'tanggal'=>$tanggal);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/eform/jeniskelamin.xlsx';		
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
	public function exportdatausia($kecamatan=0,$kelurahan=0,$rw=0)
	{

		/*$datapuskesmas = $this->laporan_kpldh_model->get_datawhere($kecamatan,'code','cl_kec');
		foreach ($datapuskesmas as $row) {
			$bar[$row->code]['puskesmas'] = $row->nama;
		}*/
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$bar = array();
		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw);
		$totaldata=array();
		foreach ($totalorang as $row) {
			$bar[]= array(
						'total' => $row->totalorang, 
						'puskesmas' => $row->nama_kecamatan, 
						'totalpersen' => ($row->totalorang!=0) ? $row->totalorang/$row->totalorang*100 : 0, 
				);
		}
		$infant = $this->laporan_kpldh_model->get_nilai_infant($kecamatan,$kelurahan,$rw);
		foreach ($infant as $row) {
			$bar[] =array(
				'jmlinfant' => $row->jumlah,
				);
		}
		/*$total = $this->laporan_kpldh_model->get_nilai_infant($kecamatan,$kelurahan,$rw);
		foreach ($total as $row) {
			$bar[$row->id_kecamatan]['total'] = $row->total;
		}*/

		$toddler = $this->laporan_kpldh_model->get_nilai_usia('1','3',$kecamatan,$kelurahan,$rw);
		foreach ($toddler as $row) {
			$bar[] =array(
				'jmltoddler' => $row->jumlah,
			);
		}


		$Preschool = $this->laporan_kpldh_model->get_nilai_usia('4','5',$kecamatan,$kelurahan,$rw);
		foreach ($Preschool as $row) {
			$bar[] = array(
					'jmlpreschool' => $row->jumlah,
				);
		}

		$sekolah = $this->laporan_kpldh_model->get_nilai_usia('6','12',$kecamatan,$kelurahan,$rw);
		foreach ($sekolah as $row) {
			$bar[] = array(
					'jmlsekolah' => $row->jumlah,
				);
		}


		$remaja = $this->laporan_kpldh_model->get_nilai_usia('13','20',$kecamatan,$kelurahan,$rw);
		foreach ($remaja as $row) {
			$bar[] = array(
				'jmlremaja' => $row->jumlah,
			);
		}

		$dewasa = $this->laporan_kpldh_model->get_nilai_usia('21','44',$kecamatan,$kelurahan,$rw);
		foreach ($dewasa as $row) {
			$bar[] = array(
				'jmldewasa' => $row->jumlah,
				);
		}


		$prelansia = $this->laporan_kpldh_model->get_nilai_usia('45','59',$kecamatan,$kelurahan,$rw);
		foreach ($prelansia as $row) {
			$bar[] = array(
				'jmlprelansia' => $row->jumlah,
				);
		}

		$lansia = $this->laporan_kpldh_model->get_nilai_lansia('60',$kecamatan,$kelurahan,$rw);
		foreach ($lansia as $row) {
			$bar[] = array(
				'jmllansia' => $row->jumlah,
				);
		}
		print_r($bar);
		die();
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',$kecamatan);
		$kd_kel  = $this->inv_barang_model->get_nama('value','cl_village','code',$kelurahan);
		$nama 	 = $this->inv_barang_model->get_nama('value','cl_phc','code',$kode_sess);
		$tanggal = date("d-m-Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'provinsi' => $kd_prov,'kabkota' => $kd_kab,'kd_kec' => $kd_kec,'kd_kel' => $kd_kel,'rw' => $rw,'tanggal'=>$tanggal);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/eform/datausia.xlsx';		
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
	public function datapendidikan($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		/*$datapuskesmas = $this->laporan_kpldh_model->get_datawhere($kecamatan,'code','cl_kec');
		foreach ($datapuskesmas as $row) {
			$bar[$row->code]['puskesmas'] = $row->nama;
		}*/
		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$bar[$row->id_kecamatan]['totalorang'] = $row->totalorang;
				$bar[$row->id_kecamatan]['puskesmas'] = $row->nama_kecamatan;
			}
		}


		$blm_sekolah = $this->laporan_kpldh_model->get_jml_pendidikan('40',$kecamatan,$kelurahan,$rw);
		foreach ($blm_sekolah as $row) {
			$bar[$row->id_kecamatan]['blmsekolah'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalblmsekolah'] = $row->total;
		}

		$tidak_sekolah = $this->laporan_kpldh_model->get_jml_pendidikan('41',$kecamatan,$kelurahan,$rw);
		foreach ($tidak_sekolah as $row) {
			$bar[$row->id_kecamatan]['tidaksekolah'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltidaksekolah'] = $row->total;
		}

		$tdk_tamatsd = $this->laporan_kpldh_model->get_jml_pendidikan('14',$kecamatan,$kelurahan,$rw);
		foreach ($tdk_tamatsd as $row) {
			$bar[$row->id_kecamatan]['tdktamatsd'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltdktamatsd'] = $row->total;
		}


		$masih_sd = $this->laporan_kpldh_model->get_jml_pendidikan('15',$kecamatan,$kelurahan,$rw);
		foreach ($masih_sd as $row) {
			$bar[$row->id_kecamatan]['masihsd'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalmasihsd'] = $row->total;
		}

		$tamat_sd = $this->laporan_kpldh_model->get_jml_pendidikan('16',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_sd as $row) {
			$bar[$row->id_kecamatan]['tamatsd'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltamatsd'] = $row->total;
		}


		$masih_smp = $this->laporan_kpldh_model->get_jml_pendidikan('17',$kecamatan,$kelurahan,$rw);
		foreach ($masih_smp as $row) {
			$bar[$row->id_kecamatan]['masihsmp'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalmasihsmp'] = $row->total;
		}

		$tamat_smp = $this->laporan_kpldh_model->get_jml_pendidikan('18',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_smp as $row) {
			$bar[$row->id_kecamatan]['tamatsmp'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltamatsmp'] = $row->total;
		}


		$masih_sma = $this->laporan_kpldh_model->get_jml_pendidikan('19',$kecamatan,$kelurahan,$rw);
		foreach ($masih_sma as $row) {
			$bar[$row->id_kecamatan]['masihsma'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalmasihsma'] = $row->total;
		}

		$tamat_sma = $this->laporan_kpldh_model->get_jml_pendidikan('20',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_sma as $row) {
			$bar[$row->id_kecamatan]['tamatsma'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltamatsma'] = $row->total;
		}

		$masih_pt = $this->laporan_kpldh_model->get_jml_pendidikan('21',$kecamatan,$kelurahan,$rw);
		foreach ($masih_pt as $row) {
			$bar[$row->id_kecamatan]['masihpt'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalmasihpt'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pendidikan('22',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[$row->id_kecamatan]['tamatpt'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltamatpt'] = $row->total;
		}
		
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartpendidikan",$data));
	}
	public function datapekerjaan($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

	/*	$datapuskesmas = $this->laporan_kpldh_model->get_datawhere($kecamatan,'code','cl_kec');
		foreach ($datapuskesmas as $row) {
			$bar[$row->code]['puskesmas'] = $row->nama;
			$bar[$row->code]['totalorang'] = 0;
		}*/
		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$bar[$row->id_kecamatan]['totalorang'] = $row->totalorang;
				$bar[$row->id_kecamatan]['puskesmas'] = $row->nama_kecamatan;
			}
		}

		$blm_sekolah = $this->laporan_kpldh_model->get_jml_pekerjaan('24',$kecamatan,$kelurahan,$rw);
		foreach ($blm_sekolah as $row) {
			$bar[$row->id_kecamatan]['petani'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalpetani'] = $row->total;
		}

		$tidak_sekolah = $this->laporan_kpldh_model->get_jml_pekerjaan('25',$kecamatan,$kelurahan,$rw);
		foreach ($tidak_sekolah as $row) {
			$bar[$row->id_kecamatan]['nelayan'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalnelayan'] = $row->total;
		}

		$tdk_tamatsd = $this->laporan_kpldh_model->get_jml_pekerjaan('26',$kecamatan,$kelurahan,$rw);
		foreach ($tdk_tamatsd as $row) {
			$bar[$row->id_kecamatan]['pnstniporli'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalpnstniporli'] = $row->total;
		}


		$masih_sd = $this->laporan_kpldh_model->get_jml_pekerjaan('27',$kecamatan,$kelurahan,$rw);
		foreach ($masih_sd as $row) {
			$bar[$row->id_kecamatan]['swasta'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalswasta'] = $row->total;
		}

		$tamat_sd = $this->laporan_kpldh_model->get_jml_pekerjaan('28',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_sd as $row) {
			$bar[$row->id_kecamatan]['wiraswasta'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalwiraswasta'] = $row->total;
		}


		$masih_smp = $this->laporan_kpldh_model->get_jml_pekerjaan('29',$kecamatan,$kelurahan,$rw);
		foreach ($masih_smp as $row) {
			$bar[$row->id_kecamatan]['pensiunan'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalpensiunan'] = $row->total;
		}

		$tamat_smp = $this->laporan_kpldh_model->get_jml_pekerjaan('30',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_smp as $row) {
			$bar[$row->id_kecamatan]['pekerjalepas'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalpekerjalepas'] = $row->total;
		}


		$masih_sma = $this->laporan_kpldh_model->get_jml_pekerjaan('31',$kecamatan,$kelurahan,$rw);
		foreach ($masih_sma as $row) {
			$bar[$row->id_kecamatan]['lainnya'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totallainnya'] = $row->total;
		}

		$tamat_sma = $this->laporan_kpldh_model->get_jml_pekerjaan('32',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_sma as $row) {
			$bar[$row->id_kecamatan]['tidakbelumkerja'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltidakbelumkerja'] = $row->total;
		}

		$masih_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('42',$kecamatan,$kelurahan,$rw);
		foreach ($masih_pt as $row) {
			$bar[$row->id_kecamatan]['bekerja'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalbekerja'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('43',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[$row->id_kecamatan]['belumkerja'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totalbelumkerja'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('44',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[$row->id_kecamatan]['tidakkerja'] = $row->jumlah;
			$bar[$row->id_kecamatan]['totaltidakkerja'] = $row->total;
		}
		$tamat_pt = $this->laporan_kpldh_model->get_jml_pekerjaan('45',$kecamatan,$kelurahan,$rw);
		foreach ($tamat_pt as $row) {
			$bar[$row->id_kecamatan]['irt'] = $row->jumlah;
			$bar[$row->id_kecamatan]['irt'] = $row->total;
		}
		
		
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartpekerjaan",$data));
	}
	
}
