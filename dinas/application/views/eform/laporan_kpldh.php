<?php
class Laporan_kpldh extends CI_Controller {

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
	}

	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "Ketuk Pintu Layani Dengan Hati";;

		$kode_sess = $this->session->userdata("puskesmas");
      	$data['datakecamatan'] = $this->datakeluarga_model->get_datawhere(substr($kode_sess, 0,7),"code","cl_kec");
      	$data['data_desa'] = $this->datakeluarga_model->get_desa();
      	
		$data['content'] = $this->parser->parse("eform/laporan/show",$data,true);

		$this->template->show($data,"home");
	}
	function get_kecamatanfilter(){
		if($this->input->is_ajax_request()) {
			$kecamatan = $this->input->post('kecamatan');
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
	function get_kelurahanfilter(){
	if ($this->input->post('kelurahan')!="null") {
		if($this->input->is_ajax_request()) {
			$kelurahan = $this->input->post('kelurahan');
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
	public function datachart($value='')
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		$jmlkelamin = $this->Laporan_kpldh_model->get_jum_kelamin();
		foreach ($jmlkelamin as $row) {
			$bar[$row->nama_kecamatan]['kelamin'] = $row->kelamin;
			$bar[$row->nama_kecamatan]['jumlah'] = $row->jumlah;
		}

		$data['bar']	= $bar;
		$data['color']	= $color;
		return print_r($data);
	}
	function pilihchart($judul=0){
		$judul = $this->input->post("judul");
		$kecamatan = $this->input->post("kecamatan");
		$kelurahan = $this->input->post("kelurahan");
		$rw = $this->input->post("rw");
		$id_judul = $this->input->post("id_judul");

		if($id_judul=="1"){
			$this->datakelamin($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="2"){
			$this->datausia($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="3"){
			$this->datapendidikan($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="4"){
			$this->datapekerjaan($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="5"){
			$this->datakegiatanposyandu($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="6"){
			$this->datadisabilitas($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="7"){
			$this->datajaminankesehatan($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="8"){
			$this->datakeikutsertaankb($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="9"){
			$this->dataalsantidakkb($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="10"){
			$this->datakepemilikanrumah($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="11"){
			$this->dataataprumah($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="12"){
			$this->datadindingrumah($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="13"){
			$this->datalantairumah($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="14"){
			$this->datasumberpenerangan($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="15"){
			$this->datasumberairminum($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="16"){
			$this->databahanbakar($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="17"){
			$this->datafasilitasbab($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="18"){
			$this->datakebiasaancucitangan($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="19"){
			$this->datalokasibab($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="20"){
			$this->datasikatgigi($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="21"){
			$this->datamerokok($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="22"){
			$this->datausiamerokok($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="23"){
			$this->dataginjal($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="24"){
			$this->datatbparu($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="25"){
			$this->datadm($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="26"){
			$this->datahipertensi($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="27"){
			$this->jantungkoroner($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="28"){
			$this->storke($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="29"){
			$this->kanker($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="30"){
			$this->asma($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="31"){
			$this->sulittidur($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="32"){
			$this->mudahtakut($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="33"){
			$this->berfikirjernih($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="34"){
			$this->tidakbahagia($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="35"){
			$this->menangis($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="36"){
			$this->mengakhirihidup($kecamatan,$kelurahan,$rw);
		}else if($id_judul=="37"){
			$this->hilangminat($kecamatan,$kelurahan,$rw);
		}else{
			return $judul;
		}
	}
	
	public function datakelamin($kecamatan=0,$kelurahan=0,$rw=0)
	{
		
		//$data['data_formprofile']  = $this->dataform_model->get_data_formprofile($kode); 
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		$jmlkelamin = $this->laporan_kpldh_model->get_jum_kelamin($kecamatan,$kelurahan,$rw);
		//$total = 0;
		foreach ($jmlkelamin as $row) {
			//$total = $total+$row->jumlah;
			$bar[$row->nama_kecamatan]['kelamin'] = $row->kelamin;
			$bar[$row->nama_kecamatan]['jumlah'] = $row->jumlah;
		//	$bar[$row->nama_kecamatan]['totalkel'] = $row->jumlah/$total*100;
		}
		$data['jumlahorang'] = $this->laporan_kpldh_model->jumlahorang($kecamatan,$kelurahan,$rw);
		$data['showkelamin'] = $jmlkelamin;
		$data['bar']	= $bar;
	//	print_r($bar);
	//	die();
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartkelamin",$data));
	}
	public function datausia($kecamatan=0,$kelurahan=0,$rw=0)
	{

		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		/*$datapuskesmas = $this->laporan_kpldh_model->get_datawhere($kecamatan,'code','cl_kec');
		foreach ($datapuskesmas as $row) {
			$bar[$row->code]['puskesmas'] = $row->nama;
		}*/
		$kecamatan = substr($this->session->userdata("puskesmas"), 0,7);
		$totalorang = $this->laporan_kpldh_model->totaljumlah($kecamatan,$kelurahan,$rw);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$bar[/*$row->id_kecamatan*/$kecamatan]['total'] = $row->totalorang;
				$bar[/*$row->id_kecamatan*/$kecamatan]['puskesmas'] = $row->nama_kecamatan;
			}
		}
		
		$infant = $this->laporan_kpldh_model->get_nilai_infant($kecamatan,$kelurahan,$rw);
		foreach ($infant as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmlinfant'] = $row->jumlah;
		}
		/*$total = $this->laporan_kpldh_model->get_nilai_infant($kecamatan,$kelurahan,$rw);
		foreach ($total as $row) {
			$bar[$row->id_kecamatan]['total'] = $row->total;
		}*/

		$toddler = $this->laporan_kpldh_model->get_nilai_usia('1','3',$kecamatan,$kelurahan,$rw);
		foreach ($toddler as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmltoddler'] = $row->jumlah;
		}


		$Preschool = $this->laporan_kpldh_model->get_nilai_usia('4','5',$kecamatan,$kelurahan,$rw);
		foreach ($Preschool as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmlpreschool'] = $row->jumlah;
		}

		$sekolah = $this->laporan_kpldh_model->get_nilai_usia('6','12',$kecamatan,$kelurahan,$rw);
		foreach ($sekolah as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmlsekolah'] = $row->jumlah;
		}


		$remaja = $this->laporan_kpldh_model->get_nilai_usia('13','20',$kecamatan,$kelurahan,$rw);
		foreach ($remaja as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmlremaja'] = $row->jumlah;
		}

		$dewasa = $this->laporan_kpldh_model->get_nilai_usia('21','44',$kecamatan,$kelurahan,$rw);
		foreach ($dewasa as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmldewasa'] = $row->jumlah;
		}


		$prelansia = $this->laporan_kpldh_model->get_nilai_usia('45','59',$kecamatan,$kelurahan,$rw);
		foreach ($prelansia as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmlprelansia'] = $row->jumlah;
		}

		$lansia = $this->laporan_kpldh_model->get_nilai_lansia('60',$kecamatan,$kelurahan,$rw);
		foreach ($lansia as $row) {
			$bar[/*$row->id_kecamatan*/$kecamatan]['jmllansia'] = $row->jumlah;
		}
		$data['jumlahorang'] = $this->laporan_kpldh_model->jumlahorang($kecamatan,$kelurahan,$rw);
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartusia",$data));
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
	public function datakegiatanposyandu($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');
		
		$kecamatan = substr($this->session->userdata("puskesmas"), 0,7);
		
		$data['tdkposyandu'] = $this->laporan_kpldh_model->get_data_posyandu('1',$kecamatan,$kelurahan,$rw);
		$data['ikutposyandu']= $this->laporan_kpldh_model->get_data_posyandu('0',$kecamatan,$kelurahan,$rw);
		
		$data['jumlahorang'] = $this->laporan_kpldh_model->get_data_jmlposyandu($kecamatan,$kelurahan,$rw);
		$data['bar']	= $bar;
		//print_r($data);
		//die();
	//	print_r($bar);
	//	die();
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartposyandu",$data));
	}
	function datadisabilitas($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');
		
		$kecamatan = substr($this->session->userdata("puskesmas"), 0,7);
		
		$data['tdkdisabilitas'] = $this->laporan_kpldh_model->get_data_disabilitas('1',$kecamatan,$kelurahan,$rw);
		$data['disabilitas']= $this->laporan_kpldh_model->get_data_disabilitas('0',$kecamatan,$kelurahan,$rw);
		
		$data['jumlahorang'] = $this->laporan_kpldh_model->totaljmldisabilitas($kecamatan,$kelurahan,$rw);
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartdisabilitas",$data));
	}
	function datajaminankesehatan($kecamatan=0,$kelurahan=0,$rw=0)
	{
		
		//$data['data_formprofile']  = $this->dataform_model->get_data_formprofile($kode); 
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');

		$jmljkn = $this->laporan_kpldh_model->get_data_kesehatan($kecamatan,$kelurahan,$rw);
		//$total = 0;
		foreach ($jmljkn as $row) {
			$bar[$row->nama_kecamatan]['jkn'] = $row->jkn;
			$bar[$row->nama_kecamatan]['jumlah'] = $row->jumlah;
		}
		$data['jumlahorang'] = $this->laporan_kpldh_model->jumlahorang($kecamatan,$kelurahan,$rw);
		$data['showjkn'] = $jmljkn;
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartkesehatan",$data));
	}
	function datakeikutsertaankb($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');
		
		$kecamatan = substr($this->session->userdata("puskesmas"), 0,7);
		
		$data['sedang'] = $this->laporan_kpldh_model->get_data_ikutkb('0',$kecamatan,$kelurahan,$rw);
		$data['pernah']= $this->laporan_kpldh_model->get_data_ikutkb('1',$kecamatan,$kelurahan,$rw);
		$data['tidakpernah']= $this->laporan_kpldh_model->get_data_ikutkb('2',$kecamatan,$kelurahan,$rw);
		
		$data['jumlahorang'] = $this->laporan_kpldh_model->totaljmlkb($kecamatan,$kelurahan,$rw);
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chatikutkb",$data));
	}
	function dataalsantidakkb($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();
		$color = array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');
		
		$sedang_hamil = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_hamil_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($sedang_hamil as $row) {
			$bar[$kecamatan]['sedanghamil'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp1=$row->jumlah;
			}else{
				$temp1=0;
			}
		}

		$tidak_setuju = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_tidaksetuju_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($tidak_setuju as $row) {
			$bar[$kecamatan]['tidaksetuju'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp2=$row->jumlah;
			}else{
				$temp2=0;
			}
		}


		$tidak_tahu = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_tidaktahu_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($tidak_tahu as $row) {
			$bar[$kecamatan]['tidaktahu'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp3=$row->jumlah;
			}else{
				$temp3=0;
			}
		}

		$takut_efekkb = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_efeksamping_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($takut_efekkb as $row) {
			$bar[$kecamatan]['takutefekkb'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp4=$row->jumlah;
			}else{
				$temp4=0;
			}
		}


		$pelayanan_kb = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_pelayanan_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($pelayanan_kb as $row) {
			$bar[$kecamatan]['pelayanankb'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp5=$row->jumlah;
			}else{
				$temp5=0;
			}
		}

		$mahal_kb = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_tidakmampu_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($mahal_kb as $row) {
			$bar[$kecamatan]['mahalkb'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp6=$row->jumlah;
			}else{
				$temp6=0;
			}
		}

		$fertilasi_kb = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_fertilasi_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($fertilasi_kb as $row) {
			$bar[$kecamatan]['fertilasi'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp7=$row->jumlah;
			}else{
				$temp7=0;
			}
		}


		$lainnya_kb = $this->laporan_kpldh_model->get_data_alasankb('berencana_II_7_berkb_lainya_cebox',$kecamatan,$kelurahan,$rw);
		foreach ($lainnya_kb as $row) {
			$bar[$kecamatan]['lainnyakb'] = $row->jumlah;
			if($row->jumlah!=0){
				$temp8=$row->jumlah;
			}else{
				$temp8=0;
			}
		}
		$kecamatan = substr($this->session->userdata("puskesmas"), 0,7);
		$totalorang = $this->laporan_kpldh_model->totalorang($kecamatan,$kelurahan,$rw);
		if ($totalorang!=0) {
			foreach ($totalorang as $row) {
				$bar[$kecamatan]['total'] = $temp1+$temp2+$temp3+$temp4+$temp5+$temp6+$temp7+$temp8;
				$bar[$kecamatan]['puskesmas'] = $row->nama_kecamatan;
			}
		}
		$data['jumlahorang'] = $this->laporan_kpldh_model->jumlahorang($kecamatan,$kelurahan,$rw);
		$data['bar']	= $bar;
		$data['color']	= $color;
		die($this->parser->parse("eform/laporan/chartalasankb",$data));
	}
	function datakepemilikanrumah($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		
		
		$data['miliksendiri'] = $this->laporan_kpldh_model->get_data_kepemilikan('pembangunan_III_26_1_cebo4',$kecamatan,$kelurahan,$rw);
		$data['sewa']= $this->laporan_kpldh_model->get_data_kepemilikan('pembangunan_III_26_2_cebo4',$kecamatan,$kelurahan,$rw);
		$data['menumpang']= $this->laporan_kpldh_model->get_data_kepemilikan('pembangunan_III_26_3_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_kepemilikan('pembangunan_III_26_4_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['miliksendiri']!=0) {
			$temp1=$data['miliksendiri'];
		}else{
			$temp1=0;
		}
		if ($data['sewa']!=0) {
			$temp2=$data['sewa'];
		}else{
			$temp2=0;
		}
		if ($data['menumpang']!=0) {
			$temp3=$data['menumpang'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartmilikrumah",$data));
	}
	function dataataprumah($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['daun'] = $this->laporan_kpldh_model->get_data_ataprumah('pembangunan_III_1_19_cebo4',$kecamatan,$kelurahan,$rw);
		$data['seng']= $this->laporan_kpldh_model->get_data_ataprumah('pembangunan_III_2_19_cebo4',$kecamatan,$kelurahan,$rw);
		$data['genteng']= $this->laporan_kpldh_model->get_data_ataprumah('pembangunan_III_3_19_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_ataprumah('pembangunan_III_4_19_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['daun']!=0) {
			$temp1=$data['daun'];
		}else{
			$temp1=0;
		}
		if ($data['seng']!=0) {
			$temp2=$data['seng'];
		}else{
			$temp2=0;
		}
		if ($data['genteng']!=0) {
			$temp3=$data['genteng'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartataprumah",$data));
	}
	function datadindingrumah($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['tembok'] = $this->laporan_kpldh_model->get_data_dindingrumah('pembangunan_III_1_20_cebo4',$kecamatan,$kelurahan,$rw);
		$data['kayu']= $this->laporan_kpldh_model->get_data_dindingrumah('pembangunan_III_2_20_cebo4',$kecamatan,$kelurahan,$rw);
		$data['bambu']= $this->laporan_kpldh_model->get_data_dindingrumah('pembangunan_III_3_20_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_dindingrumah('pembangunan_III_4_20_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['tembok']!=0) {
			$temp1=$data['tembok'];
		}else{
			$temp1=0;
		}
		if ($data['kayu']!=0) {
			$temp2=$data['kayu'];
		}else{
			$temp2=0;
		}
		if ($data['bambu']!=0) {
			$temp3=$data['bambu'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartdindingrumah",$data));
	}
	function datalantairumah($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['ubin'] = $this->laporan_kpldh_model->get_data_jenislantairumah('pembangunan_III_1_21_cebo4',$kecamatan,$kelurahan,$rw);
		$data['semen']= $this->laporan_kpldh_model->get_data_jenislantairumah('pembangunan_III_2_21_cebo4',$kecamatan,$kelurahan,$rw);
		$data['tanah']= $this->laporan_kpldh_model->get_data_jenislantairumah('pembangunan_III_3_21_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_jenislantairumah('pembangunan_III_4_21_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['ubin']!=0) {
			$temp1=$data['ubin'];
		}else{
			$temp1=0;
		}
		if ($data['semen']!=0) {
			$temp2=$data['semen'];
		}else{
			$temp2=0;
		}
		if ($data['tanah']!=0) {
			$temp3=$data['tanah'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartlantairumah",$data));
	}
	function datasumberpenerangan($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['listrik'] = $this->laporan_kpldh_model->get_data_jenispeneranganrumah('pembangunan_III_22_1_cebo4',$kecamatan,$kelurahan,$rw);
		$data['genset']= $this->laporan_kpldh_model->get_data_jenispeneranganrumah('pembangunan_III_22_2_cebo4',$kecamatan,$kelurahan,$rw);
		$data['minyak']= $this->laporan_kpldh_model->get_data_jenispeneranganrumah('pembangunan_III_22_3_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_jenispeneranganrumah('pembangunan_III_22_4_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['listrik']!=0) {
			$temp1=$data['listrik'];
		}else{
			$temp1=0;
		}
		if ($data['genset']!=0) {
			$temp2=$data['genset'];
		}else{
			$temp2=0;
		}
		if ($data['minyak']!=0) {
			$temp3=$data['minyak'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartpeneranganrumah",$data));
	}
	function datasumberairminum($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['ledeng'] = $this->laporan_kpldh_model->get_data_sumberair('pembangunan_III_23_1_cebo4',$kecamatan,$kelurahan,$rw);
		$data['sumur']= $this->laporan_kpldh_model->get_data_sumberair('pembangunan_III_23_2_cebo4',$kecamatan,$kelurahan,$rw);
		$data['hujan']= $this->laporan_kpldh_model->get_data_sumberair('pembangunan_III_23_3_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_sumberair('pembangunan_III_23_4_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['ledeng']!=0) {
			$temp1=$data['ledeng'];
		}else{
			$temp1=0;
		}
		if ($data['sumur']!=0) {
			$temp2=$data['sumur'];
		}else{
			$temp2=0;
		}
		if ($data['hujan']!=0) {
			$temp3=$data['hujan'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartsumberair",$data));
	}
	function databahanbakar($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['listrik'] = $this->laporan_kpldh_model->get_data_bahanbakar('pembangunan_III_24_1_cebo4',$kecamatan,$kelurahan,$rw);
		$data['minyak']= $this->laporan_kpldh_model->get_data_bahanbakar('pembangunan_III_24_2_cebo4',$kecamatan,$kelurahan,$rw);
		$data['arang']= $this->laporan_kpldh_model->get_data_bahanbakar('pembangunan_III_24_3_cebo4',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_bahanbakar('pembangunan_III_24_4_cebo4',$kecamatan,$kelurahan,$rw);	
		if ($data['listrik']!=0) {
			$temp1=$data['listrik'];
		}else{
			$temp1=0;
		}
		if ($data['minyak']!=0) {
			$temp2=$data['minyak'];
		}else{
			$temp2=0;
		}
		if ($data['arang']!=0) {
			$temp3=$data['arang'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartbahanbakar",$data));
	}
	function datafasilitasbab($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['sendiri'] = $this->laporan_kpldh_model->get_data_fasilitasbab('pembangunan_III_25_radi4','0',$kecamatan,$kelurahan,$rw);
		$data['bersama']= $this->laporan_kpldh_model->get_data_fasilitasbab('pembangunan_III_25_radi4','1',$kecamatan,$kelurahan,$rw);
		$data['umum']= $this->laporan_kpldh_model->get_data_fasilitasbab('pembangunan_III_25_radi4','2',$kecamatan,$kelurahan,$rw);
		$data['lainnya']= $this->laporan_kpldh_model->get_data_fasilitasbab('pembangunan_III_25_radi4','3',$kecamatan,$kelurahan,$rw);	
		if ($data['sendiri']!=0) {
			$temp1=$data['sendiri'];
		}else{
			$temp1=0;
		}
		if ($data['bersama']!=0) {
			$temp2=$data['bersama'];
		}else{
			$temp2=0;
		}
		if ($data['umum']!=0) {
			$temp3=$data['umum'];
		}else{
			$temp3=0;
		}
		if ($data['lainnya']!=0) {
			$temp4=$data['lainnya'];
		}else{
			$temp4=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4;
		die($this->parser->parse("eform/laporan/chartfasilitasbab",$data));
	}
	function datakebiasaancucitangan($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$bar = array();

		$bar['pakaisabun'] = $this->laporan_kpldh_model->get_data_cucitangan('kesehatan_1_g_1_a_cebox',$kecamatan,$kelurahan,$rw);
		$bar['tangankotor']= $this->laporan_kpldh_model->get_data_cucitangan('kesehatan_1_g_1_b_cebox',$kecamatan,$kelurahan,$rw);
		$bar['bab']= $this->laporan_kpldh_model->get_data_cucitangan('kesehatan_1_g_1_c_cebox',$kecamatan,$kelurahan,$rw);
		$bar['cebok']= $this->laporan_kpldh_model->get_data_cucitangan('kesehatan_1_g_1_d_cebox',$kecamatan,$kelurahan,$rw);	
		$bar['pestisida']= $this->laporan_kpldh_model->get_data_cucitangan('kesehatan_1_g_1_e_cebox',$kecamatan,$kelurahan,$rw);	
		$bar['menyusui']= $this->laporan_kpldh_model->get_data_cucitangan('kesehatan_1_g_1_f_cebox',$kecamatan,$kelurahan,$rw);	
		/*if ($bar['pakaisabun']!=0) {
			$temp1=$bar['pakaisabun'];
		}else{
			$temp1=0;
		}
		if ($bar['tangankotor']!=0) {
			$temp2=$bar['tangankotor'];
		}else{
			$temp2=0;
		}
		if ($bar['bab']!=0) {
			$temp3=$bar['bab'];
		}else{
			$temp3=0;
		}
		if ($bar['cebok']!=0) {
			$temp4=$bar['cebok'];
		}else{
			$temp4=0;
		}
		if ($bar['pestisida']!=0) {
			$temp5=$bar['pestisida'];
		}else{
			$temp5=0;
		}
		if ($bar['menyusui']!=0) {
			$temp6=$bar['menyusui'];
		}else{
			$temp6=0;
		}*/
		$kode_sess = $this->session->userdata("puskesmas");
		$bar['puskesmas'] = $this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$bar['totalorang'] = $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);//$temp1+$temp2+$temp3+$temp4+$temp5+$temp6;
		die($this->parser->parse("eform/laporan/chartcucitangan",$bar));

	}
	function datalokasibab($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['jamban'] = $this->laporan_kpldh_model->get_data_lokasibab('kesehatan_1_g_2_radi5','0',$kecamatan,$kelurahan,$rw);
		$data['kolam']= $this->laporan_kpldh_model->get_data_lokasibab('kesehatan_1_g_2_radi5','1',$kecamatan,$kelurahan,$rw);
		$data['sungai']= $this->laporan_kpldh_model->get_data_lokasibab('kesehatan_1_g_2_radi5','2',$kecamatan,$kelurahan,$rw);
		$data['lubang']= $this->laporan_kpldh_model->get_data_lokasibab('kesehatan_1_g_2_radi5','3',$kecamatan,$kelurahan,$rw);	
		$data['pantai']= $this->laporan_kpldh_model->get_data_lokasibab('kesehatan_1_g_2_radi5','4',$kecamatan,$kelurahan,$rw);	
		if ($data['jamban']!=0) {
			$temp1=$data['jamban'];
		}else{
			$temp1=0;
		}
		if ($data['kolam']!=0) {
			$temp2=$data['kolam'];
		}else{
			$temp2=0;
		}
		if ($data['sungai']!=0) {
			$temp3=$data['sungai'];
		}else{
			$temp3=0;
		}
		if ($data['lubang']!=0) {
			$temp4=$data['lubang'];
		}else{
			$temp4=0;
		}
		if ($data['pantai']!=0) {
			$temp5=$data['pantai'];
		}else{
			$temp5=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3+$temp4+$temp5;
		die($this->parser->parse("eform/laporan/chartlokasibab",$data));
	}
	function datasikatgigi($kecamatan=0,$kelurahan=0,$rw=0){
		$bar = array();

		$bar['mandipagi'] = $this->laporan_kpldh_model->get_data_sikatgigi('kesehatan_1_g_4_a_cebox',$kecamatan,$kelurahan,$rw);
		$bar['mandisore']= $this->laporan_kpldh_model->get_data_sikatgigi('kesehatan_1_g_4_b_cebox',$kecamatan,$kelurahan,$rw);
		$bar['makanpagi']= $this->laporan_kpldh_model->get_data_sikatgigi('kesehatan_1_g_4_c_cebox',$kecamatan,$kelurahan,$rw);
		$bar['banguntidur']= $this->laporan_kpldh_model->get_data_sikatgigi('kesehatan_1_g_4_d_cebox',$kecamatan,$kelurahan,$rw);	
		$bar['sebelumtidur']= $this->laporan_kpldh_model->get_data_sikatgigi('kesehatan_1_g_4_e_cebox',$kecamatan,$kelurahan,$rw);	
		$bar['sesudahmakan']= $this->laporan_kpldh_model->get_data_sikatgigi('kesehatan_1_g_4_f_cebox',$kecamatan,$kelurahan,$rw);	
		/*if ($bar['mandipagi']!=0) {
			$temp1=$bar['mandipagi'];
		}else{
			$temp1=0;
		}
		if ($bar['mandisore']!=0) {
			$temp2=$bar['mandisore'];
		}else{
			$temp2=0;
		}
		if ($bar['makanpagi']!=0) {
			$temp3=$bar['makanpagi'];
		}else{
			$temp3=0;
		}
		if ($bar['banguntidur']!=0) {
			$temp4=$bar['banguntidur'];
		}else{
			$temp4=0;
		}
		if ($bar['sebelumtidur']!=0) {
			$temp5=$bar['sebelumtidur'];
		}else{
			$temp5=0;
		}
		if ($bar['sesudahmakan']!=0) {
			$temp6=$bar['sesudahmakan'];
		}else{
			$temp6=0;
		}*/
		$kode_sess = $this->session->userdata("puskesmas");
		$bar['puskesmas'] = $this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$bar['totalorang'] = $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);//$temp1+$temp2+$temp3+$temp4+$temp5+$temp6;
		die($this->parser->parse("eform/laporan/chartsikatgigi",$bar));
	}
	function datamerokok($kecamatan=0,$kelurahan=0,$rw=0){
		$bar = array();

		$bar['tiaphari'] = $this->laporan_kpldh_model->get_data_merokok('kesehatan_1_g_1_radi5','0',$kecamatan,$kelurahan,$rw);
		$bar['kadang']= $this->laporan_kpldh_model->get_data_merokok('kesehatan_1_g_1_radi5','1',$kecamatan,$kelurahan,$rw);
		$bar['dulu']= $this->laporan_kpldh_model->get_data_merokok('kesehatan_1_g_1_radi5','2',$kecamatan,$kelurahan,$rw);
		$bar['dulukadang']= $this->laporan_kpldh_model->get_data_merokok('kesehatan_1_g_1_radi5','3',$kecamatan,$kelurahan,$rw);	
		$bar['tidakpernah']= $this->laporan_kpldh_model->get_data_merokok('kesehatan_1_g_1_radi5','4',$kecamatan,$kelurahan,$rw);	
		if ($bar['tiaphari']!=0) {
			$temp1=$bar['tiaphari'];
		}else{
			$temp1=0;
		}
		if ($bar['kadang']!=0) {
			$temp2=$bar['kadang'];
		}else{
			$temp2=0;
		}
		if ($bar['dulu']!=0) {
			$temp3=$bar['dulu'];
		}else{
			$temp3=0;
		}
		if ($bar['dulukadang']!=0) {
			$temp4=$bar['dulukadang'];
		}else{
			$temp4=0;
		}
		if ($bar['tidakpernah']!=0) {
			$temp5=$bar['tidakpernah'];
		}else{
			$temp5=0;
		}
		$kode_sess = $this->session->userdata("puskesmas");
		$bar['puskesmas'] = $this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$bar['totalorang'] = $temp1+$temp2+$temp3+$temp4+$temp5;
		die($this->parser->parse("eform/laporan/chartkebiasaanmerokok",$bar));
	}
	public function datausiamerokok($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['remaja'] = $this->laporan_kpldh_model->get_data_usiamerokok('13','20',$kecamatan,$kelurahan,$rw);
		$data['dewasa']= $this->laporan_kpldh_model->get_data_usiamerokok('21','40',$kecamatan,$kelurahan,$rw);
		if ($data['remaja']!=0) {
			$temp1=$data['remaja'];
		}else{
			$temp1=0;
		}
		if ($data['dewasa']!=0) {
			$temp2=$data['dewasa'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chatpertamamerokok",$data));
	}
	function dataginjal($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_ginjal('kesehatan_2_g_2_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_ginjal('kesehatan_2_g_2_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chatginjal",$data));
	}
	function datatbparu($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['kurang'] = $this->laporan_kpldh_model->get_data_paru('kesehatan_2_g_1_tb_radi3','0',$kecamatan,$kelurahan,$rw);
		$data['lebih']= $this->laporan_kpldh_model->get_data_paru('kesehatan_2_g_1_tb_radi3','1',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_paru('kesehatan_2_g_1_tb_radi3','2',$kecamatan,$kelurahan,$rw);
		if ($data['kurang']!=0) {
			$temp1=$data['kurang'];
		}else{
			$temp1=0;
		}
		if ($data['lebih']!=0) {
			$temp2=$data['lebih'];
		}else{
			$temp2=0;
		}
		if ($data['tidak']!=0) {
			$temp3=$data['tidak'];
		}else{
			$temp3=0;
		}
		$data['jumlahorang'] = $temp1+$temp2+$temp3;
		die($this->parser->parse("eform/laporan/chartparu",$data));
	}
	function datadm($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_dm('kesehatan_4_g_1_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_dm('kesehatan_4_g_1_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chartdm",$data));
	}
	function datahipertensi($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_hipertensi('kesehatan_4_g_1_hp_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_hipertensi('kesehatan_4_g_1_hp_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/charthipertensi",$data));
	}
	function jantungkoroner($kecamatan=0,$kelurahan=0,$rw=0)
	{
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_jantung('kesehatan_4_g_1_jk_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_jantung('kesehatan_4_g_1_jk_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chartjantung",$data));
	}
	function storke($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_stroke('kesehatan_4_g_1_sk_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_stroke('kesehatan_4_g_1_sk_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chartstroke",$data));
	}
	function kanker($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_kanker('kesehatan_3_g_1_kk_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_kanker('kesehatan_3_g_1_kk_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chartkanker",$data));
	}
	function asma($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_asma('kesehatan_3_g_1_radio','0',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_asma('kesehatan_3_g_1_radio','1',$kecamatan,$kelurahan,$rw);
		if ($data['ya']!=0) {
			$temp1=$data['ya'];
		}else{
			$temp1=0;
		}
		if ($data['tidak']!=0) {
			$temp2=$data['tidak'];
		}else{
			$temp2=0;
		}
		$data['jumlahorang'] = $temp1+$temp2;
		die($this->parser->parse("eform/laporan/chartasma",$data));
	}
	function sulittidur($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_sulittidur('kesehatan_5_g_3_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_sulittidur('kesehatan_5_g_3_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/chartsulittidur",$data));
	}
	function mudahtakut($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_mudahtakut('kesehatan_5_g_4_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_mudahtakut('kesehatan_5_g_4_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/chartmudahtakut",$data));
	}
	function berfikirjernih($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_berfikirjernih('kesehatan_5_g_8_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_berfikirjernih('kesehatan_5_g_8_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/chartberfikir",$data));
	}
	function tidakbahagia($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_tidakbahagia('kesehatan_5_g_9_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_tidakbahagia('kesehatan_5_g_9_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/charttidakbahagia",$data));
	}
	function menangis($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_menagis('kesehatan_5_g_10_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_menagis('kesehatan_5_g_10_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/chartmenangis",$data));
	}
	function hilangminat($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_hilangminat('kesehatan_5_g_15_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_hilangminat('kesehatan_5_g_15_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/charthilang",$data));
	}
	function mengakhirihidup($kecamatan=0,$kelurahan=0,$rw=0){
		$data = array();
		$data['ya'] = $this->laporan_kpldh_model->get_data_mengakhirihidup('kesehatan_5_g_18_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['tidak']= $this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw)-$this->laporan_kpldh_model->get_data_mengakhirihidup('kesehatan_5_g_18_kk_cebox',$kecamatan,$kelurahan,$rw);
		$data['jumlahorang'] =$this->laporan_kpldh_model->get_data_anggotaprofile($kecamatan,$kelurahan,$rw);
		die($this->parser->parse("eform/laporan/charthidup",$data));
	}
}
