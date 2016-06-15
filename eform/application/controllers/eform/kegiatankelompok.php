<?php
class Kegiatankelompok extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('eform/kegiatankelompok_model');
	}

	function pengadaan_export(){
		$this->authentication->verify('eform','show');
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if ($this->session->userdata('puskesmas')!='' ) {
			$this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
		}
		$rows_all = $this->kegiatankelompok_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}

			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if ($this->session->userdata('puskesmas')!='') {
			$this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
		}
		//$rows = $this->kegiatankelompok_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$rows = $this->kegiatankelompok_model->get_data();
		$data = array();
		$no=1;
		

		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'tgl_pengadaan' 			=> date("d-m-Y",strtotime($act->tgl_pengadaan)),
				'nomor_kontrak' 			=> $act->nomor_kontrak,
				'nomor_kwitansi' 			=> $act->nomor_kwitansi,
				'tgl_kwitansi' 				=> date("d-m-Y",strtotime($act->tgl_kwitansi)),
				'pilihan_status_pengadaan' 	=> $this->kegiatankelompok_model->getPilihan("status_pengadaan",$act->pilihan_status_pengadaan),
				'jumlah_unit'				=> $act->jumlah_unit,
				'nilai_pengadaan'			=> number_format($act->nilai_pengadaan,2),
				'keterangan'				=> $act->keterangan,
				'detail'					=> 1,
				'edit'						=> 1,
				'delete'					=> 1
			);
		}


		$puskes = $this->input->post('puskes');
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){
			$nama = 'Semua Data Puskesmas';
		}else{
			$nama = $this->input->post('puskes');
		}
		$data_puskesmas[] = array('nama_puskesmas' => $nama);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/eform/pengadaan_peserta.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_export_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function pengadaan_detail_export(){
		$this->authentication->verify('eform','show');

		$id 	= $this->input->post('kode');

		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		$data = array();

		$activity = $this->kegiatankelompok_model->getItem('inv_inventaris_peserta', array('id_pengadaan'=>$id))->result();
		foreach($activity as $act) {
			$data[] = array(
				'id_inventaris_peserta'   		=> $act->id_inventaris_peserta,
				'id_mst_inv_peserta'   			=> substr(chunk_split($act->id_mst_inv_peserta, 2, '.'),0,14),
				'nama_peserta'					=> $act->nama_peserta,
				'jumlah'						=> $act->jumlah,
				'harga'							=> number_format($act->harga,2),
				'totalharga'					=> number_format($act->totalharga,2),
				'keterangan'					=> $act->keterangan_pengadaan,
				'pilihan_status_invetaris'		=> $this->kegiatankelompok_model->getPilihan("status_inventaris",$act->pilihan_status_invetaris),
				'barang_kembar_proc'			=> $act->barang_kembar_proc,
				'tanggal_diterima'				=> date("d-m-Y",strtotime($act->tanggal_diterima)),
				'waktu_dibuat'					=> $act->waktu_dibuat,
				'terakhir_diubah'				=> $act->terakhir_diubah,
				'value'							=> $act->value
			);
		}

		$data_puskesmas	= $this->kegiatankelompok_model->get_data_row($id);
		$nama_puskesmas	= $this->kegiatankelompok_model->get_data_nama($data_puskesmas['code_cl_phc']);
		$data_puskesmas['puskesmas']		= $nama_puskesmas['value'];
		$data_puskesmas['tgl_pengadaan']	= date("d-m-Y",strtotime($data_puskesmas['tgl_pengadaan']));
		$data_puskesmas['tgl_kwitansi']		= date("d-m-Y",strtotime($data_puskesmas['tgl_kwitansi']));
		$data_puskesmas['nomor_kwitansi']	= $data_puskesmas['nomor_kwitansi'];
		$data_puskesmas['nilai_pengadaan']	= number_format($data_puskesmas['nilai_pengadaan'],2);
		$data_puskesmas['pilihan_status_pengadaan']	= $this->kegiatankelompok_model->getPilihan("status_pengadaan",$data_puskesmas['pilihan_status_pengadaan']);

		$TBS->ResetVarRef(false);
		$TBS->VarRef =  &$data_puskesmas;	
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/eform/pengadaan_peserta_detail.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		
		$TBS->MergeBlock('a', $data);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_detail_export_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function autocomplite_peserta(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->like("code",$search);
		$this->db->or_like("uraian",$search);
		$this->db->order_by('code','asc');
		$this->db->limit(10,0);
		$query= $this->db->get("mst_inv_peserta")->result();
		foreach ($query as $q) {
			$s = array();
			$s[0] = substr($q->code, 0,2);
			$s[1] = substr($q->code, 2,2);
			$s[2] = substr($q->code, 4,2);
			$s[3] = substr($q->code, 6,2);
			$s[4] = substr($q->code, 8,2);
			$barang[] = array(
				'code_tampil' 	=> implode(".", $s), 
				'code' 			=> $q->code , 
				'uraian' 		=> $q->uraian, 
			);
		}
		echo json_encode($barang);
	}
	
	function json(){
		$this->authentication->verify('eform','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		// if ($this->session->userdata('puskesmas')!='') {
		// 	$this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
		// }

		$rows_all = $this->kegiatankelompok_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		// if ($this->session->userdata('puskesmas')!='') {
		// 	$this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
		// }
		$rows = $this->kegiatankelompok_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		foreach($rows as $act) {
			$data[] = array(
				'id_data_kegiatan' 			=> $act->id_data_kegiatan,
				'tgl' 						=> $act->tgl,
				'kode_kelompok' 			=> $act->kode_kelompok,
				'kode_club' 				=> $act->kode_club,
				'status_penyuluhan' 		=> $act->status_penyuluhan,
				'status_senam'				=> $act->status_senam,
				'materi'					=> $act->materi,
				'pembicara'					=> $act->pembicara,
				'namakelompok'				=> $act->namakelompok,
				'lokasi'					=> $act->lokasi,
				'alamat'					=> $act->alamat,
				'biaya'						=> number_format($act->biaya,2),
				'keterangan'				=> $act->keterangan,
				'edit'						=> 1,//$unlock,
				'delete'					=> 1//$unlock
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
		$data['title_group'] = "eform";
		$data['title_form'] = "Daftar Pengadaan Barang";

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$data['unlock'] = 1;
		}else{
			$data['unlock'] = 0;
		}
		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}

		$data['datapuskesmas'] 	= $this->kegiatankelompok_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("eform/kegiatankelompok/show",$data,true);
		$this->template->show($data,"home");
	}

	public function getdatakelompok()
	{
		if($this->input->is_ajax_request()) {
			$datakelom = $this->input->post('datakelom');

			$kode 	= $this->kegiatankelompok_model->getSelectedData('mas_club',array('kdProgram'=>$datakelom))->result();
			$kode_club='';
			'<option value="">Pilih Ruangan</option>';
			foreach($kode as $kode) :
				echo $select = $kode->clubId == $kode_club ? 'selected' : '';
				echo '<option value="'.$kode->clubId.'" '.$select.'>' . $kode->alamat . '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}

	function add(){
		$this->authentication->verify('eform','add');

		$this->form_validation->set_rules('kode_kelompok', 'Jenis Kelompok', 'trim|required');
        $this->form_validation->set_rules('tgl', 'Tanggal Pelaksanaan', 'trim|required');
        $this->form_validation->set_rules('jenis_kelompok', 'Club Ploranis', 'trim|required');
        $this->form_validation->set_rules('edukasi', 'Edukasi', 'trim');
        $this->form_validation->set_rules('senam', 'Senam', 'trim');
        $this->form_validation->set_rules('jenis_kelompok', 'Club Ploranis', 'trim|required');
        $this->form_validation->set_rules('materi', 'Materi', 'trim|required');
        $this->form_validation->set_rules('pembicara', 'Pembicara', 'trim|required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
        $this->form_validation->set_rules('biaya', 'Biaya', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "eform";
			$data['title_form']="Tambah Pengadaan Barang";
			$data['action']="add";
			$data['kode']="";

			$kodepuskesmas = $this->session->userdata('puskesmas');
			if(strlen($kodepuskesmas) == 4){
				$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
			}else {
				$this->db->where('code','P'.$kodepuskesmas);
			}

			$data['kodepuskesmas'] = $this->kegiatankelompok_model->get_data_puskesmas();
			$data['jeniskelompok'] = array('00' =>'Non-Prolanis' , '01' =>'Diabetes Melitus','02' =>'Hipertensi');
		
			$data['content'] = $this->parser->parse("eform/kegiatankelompok/form",$data,true);
		}elseif($id = $this->kegiatankelompok_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'eform/kegiatankelompok/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/kegiatankelompok/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id_kegiatan=0){
		$this->authentication->verify('eform','edit');
		$this->form_validation->set_rules('id_data_kegiatan', 'id_data_kegiatan', 'trim|required');
        $this->form_validation->set_rules('kode_kelompok', 'Jenis Kelompok', 'trim|required');
        $this->form_validation->set_rules('tgl', 'Tanggal Pelaksanaan', 'trim|required');
        $this->form_validation->set_rules('jenis_kelompok', 'Club Ploranis', 'trim|required');
        $this->form_validation->set_rules('edukasi', 'Edukasi', 'trim');
        $this->form_validation->set_rules('senam', 'Senam', 'trim');
        $this->form_validation->set_rules('jenis_kelompok', 'Club Ploranis', 'trim|required');
        $this->form_validation->set_rules('materi', 'Materi', 'trim|required');
        $this->form_validation->set_rules('pembicara', 'Pembicara', 'trim|required');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
        $this->form_validation->set_rules('biaya', 'Biaya', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data 	= $this->kegiatankelompok_model->get_data_row($id_kegiatan);
			$data['title_group'] 	= "eform";
			$data['title_form']		= "Ubah Pengadaan Barang";
			$data['action']			= "edit";
			$data['kode']			= $id_kegiatan;
			$kodepuskesmas = $this->session->userdata('puskesmas');
			if(strlen($kodepuskesmas) == 4){
				$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
			}else {
				$this->db->where('code','P'.$kodepuskesmas);
			}
			$data['kodepuskesmas'] = $this->kegiatankelompok_model->get_data_puskesmas();
			$data['jeniskelompok'] = array('00' =>'Non-Prolanis' , '01' =>'Diabetes Melitus','02' =>'Hipertensi');
			$data['pesertadata']	  	= $this->parser->parse('eform/kegiatankelompok/peserta', $data, TRUE);
			$data['content'] 	= $this->parser->parse("eform/kegiatankelompok/edit",$data,true);
		}elseif($this->kegiatankelompok_model->update_entry($id_kegiatan)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."eform/kegiatankelompok/edit/".$id_kegiatan);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."eform/kegiatankelompok/edit/".$id_kegiatan);
		}

		$this->template->show($data,"home");
	}
	function detail($id_kegiatan=0){
		$this->authentication->verify('eform','edit');
		if($this->form_validation->run()== FALSE){
			$data 	= $this->kegiatankelompok_model->get_data_row($id_kegiatan);
			$data['title_group'] 	= "eform";
			$data['title_form']		= "Detail Pengadaan Barang";
			$data['action']			= "view";
			$data['kode']			= $id_kegiatan;
			$data['viewreadonly']	= "readonly=''";

			$kodepuskesmas = $this->session->userdata('puskesmas');
			if(substr($kodepuskesmas, -2)=="01"){
				$data['unlock'] = 1;
			}else{
				$data['unlock'] = 0;
			}
			$data['kodepuskesmas'] = $this->kegiatankelompok_model->get_data_puskesmas();
			$data['kodestatus'] = $this->kegiatankelompok_model->get_data_status();
			$data['kodestatus_inv'] = $this->kegiatankelompok_model->pilih_data_status('status_inventaris');
			$data['barang']	  	= $this->parser->parse('eform/kegiatankelompok/peserta', $data, TRUE);
			$data['content'] 	= $this->parser->parse("eform/kegiatankelompok/edit",$data,true);
			$this->template->show($data,"home");
		}
	}
	function dodel($kode=0){
		$this->authentication->verify('eform','del');

		if($this->kegiatankelompok_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."eform/kegiatankelompok");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."eform/kegiatankelompok");
		}
	}
	function updatestatus_peserta(){
		$this->authentication->verify('eform','edit');
		$this->kegiatankelompok_model->update_status();				
	}
	function dodelpermohonan($pengadaan=0,$kode=0,$kembarproc=0){
		if($this->kegiatankelompok_model->delete_entryitem($kode,$kembarproc)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
				$dataupdate['jumlah_unit']= $this->kegiatankelompok_model->sum_unit($kode)->num_rows();
				$dataupdate['nilai_pengadaan']= $this->kegiatankelompok_model->sum_jumlah_item( $kode,'harga');
				$key['id_pengadaan'] = $pengadaan;
        		$this->db->update("inv_pengadaan",$dataupdate,$key);
				$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
	}

	public function detailpeserta($id = 0)
	{
		$data	  	= array();
		$filter 	= array();
		$filterLike = array();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir' ) {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$activity = $this->kegiatankelompok_model->getItem('data_kegiatan_peserta', array('data_kegiatan_peserta.id_data_kegiatan'=>$id))->result();
		$no=1;
		foreach($activity as $act) {
			$juml =($act->register-1)+$act->jumlah;
			$data[] = array(
				'no'							=> $no++,
				'no_kartu'   					=> $act->no_kartu,
				'id_data_kegiatan'   			=> $act->id_data_kegiatan,
				'nama'							=> $act->nama,
				'tgl_lahir'						=> $act->tgl_lahir,
				'usia'							=> $act->usia,
				'jenis_kelamin'					=> $act->jenis_kelamin,
				'jenis_peserta'					=> $act->jenis_peserta,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$json = array(
			'TotalRows' => sizeof($data),
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	public function kodeInvetaris($id=0){
		$this->db->where('code',"P".$this->session->userdata('puskesmas'));
		$query = $this->db->get('cl_phc')->result();
		foreach ($query as $q) {
			$kode[] = array(
				'kodeinv' => $q->cd_kompemilikbarang.'.'.$q->cd_propinsi.'.'.$q->cd_kabkota.'.'.$q->cd_bidang.'.'.$q->cd_unitbidang.'.'.$q->cd_satuankerja, 
			);
			echo json_encode($kode);
		}
	}
	function form_tab_dpp($pageIndex,$id_kegiatan=0){
		$data = array();
		
		$data['kode']			= $id_kegiatan;
		switch ($pageIndex) {
			case 1:
				//$this->add_peserta($id_kegiatan);
				die($this->parser->parse("eform/kegiatankelompok/peserta_form",$data));
				break;
			case 2:
				// $this->add_peserta($id_kegiatan);
				die($this->parser->parse("eform/kegiatankelompok/peserta_form_grid",$data));
				break;
			default:
					// $this->add_peserta($id_kegiatan);
				die($this->parser->parse("eform/kegiatankelompok/peserta_form",$data));
				break;
		}

	}
	public function add_peserta($kode=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('id_mst_inv_peserta', 'Kode Barang', 'trim|required');
        $this->form_validation->set_rules('nama_peserta', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        //$this->form_validation->set_rules('keterangan_pengadaan', 'Keterangan', 'trim|required');
        $this->form_validation->set_rules('id_inventaris_peserta', 'Kode Inventaris', 'trim|required');

		if($this->form_validation->run()== FALSE){
			// $data['kodebarang']		= $this->kegiatankelompok_model->get_databarang();
			$data['notice']			= validation_errors();

			die($this->parser->parse('eform/kegiatankelompok/tab_peserta', $data));
		}else{
			$jumlah =$this->input->post('jumlah');
			$id_peserta = $this->input->post('id_mst_inv_peserta');
			$kode_proc = $this->kegiatankelompok_model->barang_kembar_proc_($this->input->post('id_inventaris_peserta'));
			$id_= $this->kegiatankelompok_model->kode_invetaris($this->input->post('id_inventaris_peserta'));
			$kodepuskesmas = 'P'.$this->session->userdata('puskesmas');
			$id_inventaris = $this->kegiatankelompok_model->kode_invetaris($this->input->post('id_inventaris_peserta'));
			$register = substr($id_inventaris, 24,28);
			for($i=1;$i<=$jumlah;$i++){
				$values = array(
					'id_inventaris_peserta'=>$this->kegiatankelompok_model->kode_invetaris($this->input->post('id_inventaris_peserta')),
					'register' 			  => substr($this->kegiatankelompok_model->kode_invetaris($this->input->post('id_inventaris_peserta')),-4),
					'id_mst_inv_peserta'=> $id_peserta,
					'nama_peserta' => $this->input->post('nama_peserta'),
					'harga' => $this->input->post('harga'),
					//'keterangan_pengadaan' => $this->input->post('keterangan_pengadaan'),
					'barang_kembar_proc' => $kode_proc,
					'id_pengadaan' => $kode,
					'code_cl_phc' => $kodepuskesmas,
				);
				$simpan=$this->db->insert('inv_inventaris_peserta', $values);
			}
			if($simpan==true){
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']= $this->kegiatankelompok_model->sum_unit($kode)->num_rows();
				$dataupdate['nilai_pengadaan']= $this->kegiatankelompok_model->sum_jumlah_item( $kode,'harga');
				$key['id_pengadaan'] = $kode;
        		$this->db->update("inv_pengadaan",$dataupdate,$key);
				die("OK|$id_|$kode_proc");
			}else{
				 die("Error|Proses data gagal");
			}
			
		}
	}
	public function edit_peserta($id_pengadaan=0,$kd_inventaris=0,$kode_proc=0)
	{
		$data['action']			= "edit";
		$data['kode']			= $kd_inventaris;
		$this->form_validation->set_rules('id_mst_inv_peserta', 'Kode Barang', 'trim|required');
        $this->form_validation->set_rules('nama_peserta', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
       // $this->form_validation->set_rules('keterangan_pengadaan', 'Keterangan', 'trim|required');
      	/*validasi kode barang*/
	    $kodebarang_ = substr($kd_inventaris, -14,-12);
	    if($kodebarang_=='01') {
	    	$this->form_validation->set_rules('luas', 'Luas', 'trim|required');
	    	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_satuan_peserta', 'Pilihan Satuan Barang', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_status_hak', 'Pilihan Status Hak', 'trim|required');
	    	$this->form_validation->set_rules('status_sertifikat_tanggal', 'Tanggal Status Sertifikat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_penggunaan', 'Pilihan Penggunaan', 'trim|required');
	    	$this->form_validation->set_rules('status_sertifikat_nomor', 'Nomor Sertifikat', 'trim|required');
	    }else if($kodebarang_=='02') {	
	    	$this->form_validation->set_rules('merek_type', 'Merek Tipe', 'trim|required');
	    	$this->form_validation->set_rules('identitas_peserta', 'Identitas Barang', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_bahan', 'Pilihan Bahan', 'trim|required');
	    	$this->form_validation->set_rules('ukuran_peserta', 'Ukuran Barang', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_satuan', 'Pilihan Satuan', 'trim|required');
	    	$this->form_validation->set_rules('tanggal_bpkb', 'Tanggal BPKB', 'trim|required');
	    	$this->form_validation->set_rules('nomor_bpkb', 'Nomor BPKB', 'trim|required');
	    	$this->form_validation->set_rules('no_polisi', 'No Polisi', 'trim|required');
	    	$this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|required');
	    }else if($kodebarang_=='03') {
	    	$this->form_validation->set_rules('luas_lantai', 'Luas Lantai', 'trim|required');
	    	$this->form_validation->set_rules('letak_lokasi_alamat', 'Letak Lokasi Alamat', 'trim|required');
	    	$this->form_validation->set_rules('pillihan_status_hak', 'Pillihan Status Hak', 'trim|required');
	    	$this->form_validation->set_rules('nomor_kode_tanah', 'Nomor Kode Tanah', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_kons_tingkat', 'Pilihan Kontruksi Tingkat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_kons_beton', 'Pilihan Konstruksi Beton', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_tanggal', 'Tanggal Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_nomor', 'Nomor Dokumen', 'trim|required');
	    }else if($kodebarang_=='04') {
	    	$this->form_validation->set_rules('konstruksi', 'Konstruksi', 'trim|required');
	    	$this->form_validation->set_rules('panjang', 'Panjang', 'trim|required');
	    	$this->form_validation->set_rules('lebar', 'Lebar', 'trim|required');
	    	$this->form_validation->set_rules('luas', 'Luas', 'trim|required');
	    	$this->form_validation->set_rules('letak_lokasi_alamat', 'Lokasi Alamat', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_tanggal', 'Tanggal Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_nomor', 'Nomor Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_status_tanah', 'Pilihan Status Tanah', 'trim|required');
	    	$this->form_validation->set_rules('nomor_kode_tanah', 'Nomor Kode Tanah', 'trim|required');
	    }else if($kodebarang_=='05') {
	    	$this->form_validation->set_rules('buku_judul_pencipta', 'Judul Buku Pencipta', 'trim|required');
	    	$this->form_validation->set_rules('buku_spesifikasi', 'Spesifikasi Buku', 'trim|required');
	    	$this->form_validation->set_rules('budaya_asal_daerah', 'Budaya Asal Daerah', 'trim|required');
	    	$this->form_validation->set_rules('budaya_pencipta', 'Pencipta Budaya', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_budaya_bahan', 'pilihan Budaya Bahan', 'trim|required');
	    	$this->form_validation->set_rules('flora_fauna_jenis', 'Jenis Flora Fauna', 'trim|required');
	    	$this->form_validation->set_rules('flora_fauna_ukuran', 'Ukuran Flora Fauna', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_satuan', 'Pilihan Satuan', 'trim|required');
	    	$this->form_validation->set_rules('tahun_cetak_beli', 'Tahun Cetak Beli', 'trim|required');
	    }else if($kodebarang_=='06') {
	    	$this->form_validation->set_rules('bangunan', 'Bangunan', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_konstruksi_bertingkat', 'Pilihan Konstruksi Bertingkat', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_konstruksi_beton', 'Pilihan Konstruksi Beton', 'trim|required');
	    	$this->form_validation->set_rules('luas', 'Luas', 'trim|required');
	    	$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_tanggal', 'Tanggal Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('dokumen_nomor', 'Nomor Dokumen', 'trim|required');
	    	$this->form_validation->set_rules('tanggal_mulai', 'Mulai Tanggal', 'trim|required');
	    	$this->form_validation->set_rules('pilihan_status_tanah', 'Pilihan Status Tanah', 'trim|required');
	    }
		/*end validasi kode barang*/
		if($this->form_validation->run()== FALSE){
			
			
			/*mengirim status pada masing2 form*/

			$kodebarang_ = substr($kd_inventaris, -14,-12);
	   		if($kodebarang_=='01') {
	   			$data = $this->kegiatankelompok_model->get_data_peserta_edit_table($kd_inventaris,'inv_inventaris_peserta_a'); 
	   			$data['pilihan_satuan_peserta_']			= $this->kegiatankelompok_model->get_data_pilihan('satuan');
	   			$data['pilihan_status_hak_']			= $this->kegiatankelompok_model->get_data_pilihan('status_hak');
	   			$data['pilihan_penggunaan_']			= $this->kegiatankelompok_model->get_data_pilihan('penggunaan');
	   		}else if($kodebarang_=='02') {
	   			$data = $this->kegiatankelompok_model->get_data_peserta_edit_table($kd_inventaris,'inv_inventaris_peserta_b'); 
	   			$data['pilihan_bahan_']				= $this->kegiatankelompok_model->get_data_pilihan('bahan');
	   			$data['pilihan_satuan_']				= $this->kegiatankelompok_model->get_data_pilihan('satuan');
	   		}else if($kodebarang_=='03') {
	   			$data = $this->kegiatankelompok_model->get_data_peserta_edit_table($kd_inventaris,'inv_inventaris_peserta_c'); 
	   			$data['pillihan_status_hak_']		= $this->kegiatankelompok_model->get_data_pilihan('status_hak');
	   			$data['pilihan_kons_tingkat_']		= $this->kegiatankelompok_model->get_data_pilihan('kons_tingkat');
	   			$data['pilihan_kons_beton_']			= $this->kegiatankelompok_model->get_data_pilihan('kons_beton');
	   		}else if($kodebarang_=='04') {
	   			$data = $this->kegiatankelompok_model->get_data_peserta_edit_table($kd_inventaris,'inv_inventaris_peserta_d'); 
	   			$data['pilihan_status_tanah_']		= $this->kegiatankelompok_model->get_data_pilihan('status_hak');
	   		}else if($kodebarang_=='05') {
	   			$data = $this->kegiatankelompok_model->get_data_peserta_edit_table($kd_inventaris,'inv_inventaris_peserta_e'); 
	   			$data['pilihan_budaya_bahan_']		= $this->kegiatankelompok_model->get_data_pilihan('bahan');
	   			$data['pilihan_satuan_']				= $this->kegiatankelompok_model->get_data_pilihan('satuan');
   			}else if($kodebarang_=='06') {
   				$data = $this->kegiatankelompok_model->get_data_peserta_edit_table($kd_inventaris,'inv_inventaris_peserta_f'); 
   				$data['pilihan_konstruksi_bertingkat_']= $this->kegiatankelompok_model->get_data_pilihan('kons_tingkat');
	   			$data['pilihan_konstruksi_beton_']	= $this->kegiatankelompok_model->get_data_pilihan('kons_beton');
	   			$data['pilihan_status_tanah_']		= $this->kegiatankelompok_model->get_data_pilihan('status_hak');
   			}
   			//$data = $this->kegiatankelompok_model->get_data_peserta_edit($id_peserta,$kd_proc,$kd_inventaris); 
   			$data['kodebarang']		= $this->kegiatankelompok_model->get_databarang();
   			$data['kodestatus_inv'] = $this->kegiatankelompok_model->pilih_data_status('status_inventaris');
			$data['action']			= "edit";
			$data['kode']			= $kd_inventaris;
			$data['id_pengadaan']	= $id_pengadaan;
			$data['kode_proc']		= $kode_proc;
			$data['disable']		= "disable";
			$data['notice']			= validation_errors();
   			/*end mengirim status pada masing2 form*/
			die($this->parser->parse('eform/kegiatankelompok/peserta_form_edit', $data));
		}else{
			$jumlah =$this->input->post('jumlah');
			$tanggalterima = explode("/",$this->input->post('tanggal_diterima'));
			$kodebarang_ = substr($kd_inventaris, -14,-12);
			$tanggal_diterima = $tanggalterima[2].'-'.$tanggalterima[1].'-'.$tanggalterima[0];
			$tanggal = $this->kegiatankelompok_model->tanggal($id_pengadaan);
			$data_update = array(
					'nama_peserta' 			=> $this->input->post('nama_peserta'),
					'harga' 				=> $this->input->post('harga'),
				//	'keterangan_pengadaan' 	=> $this->input->post('keterangan_pengadaan'),
					'pilihan_status_invetaris'  => $this->input->post('pilihan_status_invetaris'),
		            'tanggal_pembelian'     => $tanggal,
		            'tanggal_pengadaan'     => $tanggal,
		            'tanggal_diterima'      => $tanggal_diterima,
			);
			$key_update = array('barang_kembar_proc' => $kode_proc,
			 );
			$this->db->update('inv_inventaris_peserta',$data_update,$key_update);
			//$simpan = $this->dodelpermohonan($kd_inventaris,$id_peserta,$kd_proc);
			//for($i=1;$i<=$jumlah;$i++){
			//	$id = $this->kegiatankelompok_model->insert_data_from($id_peserta,$kode_proc,$tanggal_diterima,$id_pengadaan);
					/*simpan pada bedadatabase*/
			$id_inv = $this->db->query("SELECT id_inventaris_peserta,id_mst_inv_peserta FROM inv_inventaris_peserta WHERE  barang_kembar_proc=".'"'.$kode_proc.'"'."")->result();
        	foreach ($id_inv as $keyinv) {
        		$kodebarang_ = substr($keyinv->id_mst_inv_peserta,0,2);
		   		if($kodebarang_=='01') {	
		   			$this->db->where('id_inventaris_peserta',$keyinv->id_inventaris_peserta);
				 	$this->db->delete('inv_inventaris_peserta_a');
		   			/*$jumlah = $this->kegiatankelompok_model->jumlahtable('inv_inventaris_peserta_a',$keyinv->id_inventaris);
		   			if ($jumlah>0) {
		   				$tanggal = explode("/",$this->input->post('status_sertifikat_tanggal'));
		   				$status_sertifikat_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   				$values = array(
							'luas' 					=> $this->input->post('luas'),
							'alamat' 				=> $this->input->post('alamat'),
							'pilihan_satuan_peserta' => $this->input->post('pilihan_satuan_peserta'),
							'pilihan_status_hak' 	=> $this->input->post('pilihan_status_hak'),
							'status_sertifikat_tanggal' => $status_sertifikat_tanggal,
							'status_sertifikat_nomor'=> $this->input->post('status_sertifikat_nomor'),
							'pilihan_penggunaan' 	=> $this->input->post('pilihan_penggunaan'),
						);
						$key = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							);
						$simpan=$this->db->update('inv_inventaris_peserta_a', $values,$key);
		   			}else{*/
		   				$tanggal = explode("/",$this->input->post('status_sertifikat_tanggal'));
		   				$status_sertifikat_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
		   				$values = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							'id_mst_inv_peserta'		=> $keyinv->id_mst_inv_peserta,
							'luas' 					=> $this->input->post('luas'),
							'alamat' 				=> $this->input->post('alamat'),
							'pilihan_satuan_peserta' => $this->input->post('pilihan_satuan_peserta'),
							'pilihan_status_hak' 	=> $this->input->post('pilihan_status_hak'),
							'status_sertifikat_tanggal' => $status_sertifikat_tanggal,
							'status_sertifikat_nomor'=> $this->input->post('status_sertifikat_nomor'),
							'pilihan_penggunaan' 	=> $this->input->post('pilihan_penggunaan'),
						);
						$simpan=$this->db->insert('inv_inventaris_peserta_a', $values);
						
					//}
		   		}else if($kodebarang_=='02') {
		   			$this->db->where('id_inventaris_peserta',$keyinv->id_inventaris_peserta);
				 	$this->db->delete('inv_inventaris_peserta_b');
		   			/*$jumlah = $this->kegiatankelompok_model->jumlahtable('inv_inventaris_peserta_b',$keyinv->id_inventaris);
		   			if($jumlah>0){
		   				$tanggal = explode("/",$this->input->post('tanggal_bpkb'));
			   			$tanggal_bpkb = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$tanggal_ = explode("/",$this->input->post('tanggal_perolehan'));
			   			$tanggal_perolehan = $tanggal_[2].'-'.$tanggal_[1].'-'.$tanggal_[0];
			   			$values = array(
							'merek_type' 			=> $this->input->post('merek_type'),
							'identitas_peserta' 		=> $this->input->post('identitas_peserta'),
							'pilihan_bahan' 		=> $this->input->post('pilihan_bahan'),
							'ukuran_peserta' 		=> $this->input->post('ukuran_peserta'),
							'pilihan_satuan' 		=> $this->input->post('pilihan_satuan'),
							'tanggal_bpkb'			=> $tanggal_bpkb,
							'nomor_bpkb'		 	=> $this->input->post('nomor_bpkb'),
							'no_polisi'		 		=> $this->input->post('no_polisi'),
							'tanggal_perolehan'	 	=> $tanggal_perolehan,
						);
						$key = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
						 );	
						$simpan=$this->db->update('inv_inventaris_peserta_b', $values,$key);
		   			}else{*/
			   			$tanggal = explode("/",$this->input->post('tanggal_bpkb'));
			   			$tanggal_bpkb = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$tanggal_ = explode("/",$this->input->post('tanggal_perolehan'));
			   			$tanggal_perolehan = $tanggal_[2].'-'.$tanggal_[1].'-'.$tanggal_[0];
			   			$values = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							'id_mst_inv_peserta'		=> $keyinv->id_mst_inv_peserta,
							'merek_type' 			=> $this->input->post('merek_type'),
							'identitas_peserta' 		=> $this->input->post('identitas_peserta'),
							'pilihan_bahan' 		=> $this->input->post('pilihan_bahan'),
							'ukuran_peserta' 		=> $this->input->post('ukuran_peserta'),
							'pilihan_satuan' 		=> $this->input->post('pilihan_satuan'),
							'tanggal_bpkb'			=> $tanggal_bpkb,
							'nomor_bpkb'		 	=> $this->input->post('nomor_bpkb'),
							'no_polisi'		 		=> $this->input->post('no_polisi'),
							'tanggal_perolehan'	 	=> $tanggal_perolehan,
						);
						$simpan=$this->db->insert('inv_inventaris_peserta_b', $values);
					//}
		   		}else if($kodebarang_=='03') {
		   			$this->db->where('id_inventaris_peserta',$keyinv->id_inventaris_peserta);
				 	$this->db->delete('inv_inventaris_peserta_c');
		   			/*$jumlah = $this->kegiatankelompok_model->jumlahtable('inv_inventaris_peserta_c',$keyinv->id_inventaris);
		   			if ($jumlah>0) {
		   				$tanggal = explode("/",$this->input->post('dokumen_tanggal'));
			   			$dokumen_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'luas_lantai' 			=> $this->input->post('luas_lantai'),
							'letak_lokasi_alamat' 	=> $this->input->post('letak_lokasi_alamat'),
							'pillihan_status_hak' 	=> $this->input->post('pillihan_status_hak'),
							'nomor_kode_tanah' 		=> $this->input->post('nomor_kode_tanah'),
							'pilihan_kons_tingkat' 	=> $this->input->post('pilihan_kons_tingkat'),
							'pilihan_kons_beton'	=> $this->input->post('pilihan_kons_beton'),
							'dokumen_tanggal'		=> $dokumen_tanggal,
							'dokumen_nomor'		 	=> $this->input->post('dokumen_nomor'),
						);
						$key = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
						 );	
						$simpan=$this->db->update('inv_inventaris_peserta_c', $values,$key);
		   			}else {*/
			   			$tanggal = explode("/",$this->input->post('dokumen_tanggal'));
			   			$dokumen_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							'id_mst_inv_peserta'		=> $keyinv->id_mst_inv_peserta,
							'luas_lantai' 			=> $this->input->post('luas_lantai'),
							'letak_lokasi_alamat' 	=> $this->input->post('letak_lokasi_alamat'),
							'pillihan_status_hak' 	=> $this->input->post('pillihan_status_hak'),
							'nomor_kode_tanah' 		=> $this->input->post('nomor_kode_tanah'),
							'pilihan_kons_tingkat' 	=> $this->input->post('pilihan_kons_tingkat'),
							'pilihan_kons_beton'	=> $this->input->post('pilihan_kons_beton'),
							'dokumen_tanggal'		=> $dokumen_tanggal,
							'dokumen_nomor'		 	=> $this->input->post('dokumen_nomor'),
						);
						$simpan=$this->db->insert('inv_inventaris_peserta_c', $values);
					//}
		   		}else if($kodebarang_=='04') {
		   			$this->db->where('id_inventaris_peserta',$keyinv->id_inventaris_peserta);
				 	$this->db->delete('inv_inventaris_peserta_d');
		   			/*$jumlah = $this->kegiatankelompok_model->jumlahtable('inv_inventaris_peserta_d',$keyinv->id_inventaris);
		   			if($jumlah>0){
		   				$tanggal = explode("/",$this->input->post('dokumen_tanggal'));
			   			$dokumen_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'konstruksi' 			=> $this->input->post('konstruksi'),
							'panjang' 				=> $this->input->post('panjang'),
							'lebar' 				=> $this->input->post('lebar'),
							'luas' 					=> $this->input->post('luas'),
							'letak_lokasi_alamat' 	=> $this->input->post('letak_lokasi_alamat'),
							'dokumen_tanggal'		=> $dokumen_tanggal,
							'dokumen_nomor'			=> $this->input->post('dokumen_nomor'),
							'pilihan_status_tanah'	=> $this->input->post('pilihan_status_tanah'),
							'nomor_kode_tanah'		=> $this->input->post('nomor_kode_tanah'),
						);
		   				$key = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
						 );	
						$simpan=$this->db->update('inv_inventaris_peserta_d', $values,$key);
		   			}else{*/
			   			$tanggal = explode("/",$this->input->post('dokumen_tanggal'));
			   			$dokumen_tanggal = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							'id_mst_inv_peserta'		=> $keyinv->id_mst_inv_peserta,
							'konstruksi' 			=> $this->input->post('konstruksi'),
							'panjang' 				=> $this->input->post('panjang'),
							'lebar' 				=> $this->input->post('lebar'),
							'luas' 					=> $this->input->post('luas'),
							'letak_lokasi_alamat' 	=> $this->input->post('letak_lokasi_alamat'),
							'dokumen_tanggal'		=> $dokumen_tanggal,
							'dokumen_nomor'			=> $this->input->post('dokumen_nomor'),
							'pilihan_status_tanah'	=> $this->input->post('pilihan_status_tanah'),
							'nomor_kode_tanah'		=> $this->input->post('nomor_kode_tanah'),
						);
						$simpan=$this->db->insert('inv_inventaris_peserta_d', $values);
					//}
		   		}else if($kodebarang_=='05') {
		   			$this->db->where('id_inventaris_peserta',$keyinv->id_inventaris_peserta);
				 	$this->db->delete('inv_inventaris_peserta_e');
		   			/*$jumlah = $this->kegiatankelompok_model->jumlahtable('inv_inventaris_peserta_e',$keyinv->id_inventaris);
		   			if ($jumlah>0) {
		   				$tanggal = explode("/",$this->input->post('tahun_cetak_beli'));
			   			$tahun_cetak_beli = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'buku_judul_pencipta' 	=> $this->input->post('buku_judul_pencipta'),
							'buku_spesifikasi' 		=> $this->input->post('buku_spesifikasi'),
							'budaya_asal_daerah' 	=> $this->input->post('budaya_asal_daerah'),
							'budaya_pencipta' 		=> $this->input->post('budaya_pencipta'),
							'pilihan_budaya_bahan' 	=> $this->input->post('pilihan_budaya_bahan'),
							'flora_fauna_jenis'		=> $this->input->post('flora_fauna_jenis'),
							'flora_fauna_ukuran'	=> $this->input->post('flora_fauna_ukuran'),
							'pilihan_satuan'		=> $this->input->post('pilihan_satuan'),
							'tahun_cetak_beli'		=> $tahun_cetak_beli,
						);
		   				$key = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
						 );	
						$simpan=$this->db->update('inv_inventaris_peserta_e', $values,$key);
		   			}else{*/
			   			$tanggal = explode("/",$this->input->post('tahun_cetak_beli'));
			   			$tahun_cetak_beli = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							'id_mst_inv_peserta'		=> $keyinv->id_mst_inv_peserta,
							'buku_judul_pencipta' 	=> $this->input->post('buku_judul_pencipta'),
							'buku_spesifikasi' 		=> $this->input->post('buku_spesifikasi'),
							'budaya_asal_daerah' 	=> $this->input->post('budaya_asal_daerah'),
							'budaya_pencipta' 		=> $this->input->post('budaya_pencipta'),
							'pilihan_budaya_bahan' 	=> $this->input->post('pilihan_budaya_bahan'),
							'flora_fauna_jenis'		=> $this->input->post('flora_fauna_jenis'),
							'flora_fauna_ukuran'	=> $this->input->post('flora_fauna_ukuran'),
							'pilihan_satuan'		=> $this->input->post('pilihan_satuan'),
							'tahun_cetak_beli'		=> $tahun_cetak_beli,
						);
						$simpan=$this->db->insert('inv_inventaris_peserta_e', $values);
					//}
				}else if($kodebarang_=='06') {
					$this->db->where('id_inventaris_peserta',$keyinv->id_inventaris_peserta);
				 	$this->db->delete('inv_inventaris_peserta_f');
					/*$jumlah = $this->kegiatankelompok_model->jumlahtable('inv_inventaris_peserta_f',$keyinv->id_inventaris);
					if($jumlah>0){
						$tanggal = explode("/",$this->input->post('tanggal_mulai'));
			   			$tanggal_mulai = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'bangunan' 				=> $this->input->post('bangunan'),
							'pilihan_konstruksi_bertingkat' => $this->input->post('pilihan_konstruksi_bertingkat'),
							'pilihan_konstruksi_beton' 	=> $this->input->post('pilihan_konstruksi_beton'),
							'luas' 					=> $this->input->post('luas'),
							'lokasi' 				=> $this->input->post('lokasi'),
							'dokumen_tanggal'		=> $this->input->post('dokumen_tanggal'),
							'dokumen_nomor'			=> $this->input->post('dokumen_nomor'),
							'tanggal_mulai'			=> $tanggal_mulai,
							'pilihan_status_tanah'	=> $this->input->post('pilihan_status_tanah'),
						);
						$key = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
						 );	
						$simpan=$this->db->update('inv_inventaris_peserta_f', $values,$key);
					}else{*/
						$tanggal = explode("/",$this->input->post('tanggal_mulai'));
			   			$tanggal_mulai = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
			   			$values = array(
							'id_inventaris_peserta' 	=> $keyinv->id_inventaris_peserta,
							'id_mst_inv_peserta'		=> $keyinv->id_mst_inv_peserta,
							'bangunan' 				=> $this->input->post('bangunan'),
							'pilihan_konstruksi_bertingkat' => $this->input->post('pilihan_konstruksi_bertingkat'),
							'pilihan_konstruksi_beton' 	=> $this->input->post('pilihan_konstruksi_beton'),
							'luas' 					=> $this->input->post('luas'),
							'lokasi' 				=> $this->input->post('lokasi'),
							'dokumen_tanggal'		=> $this->input->post('dokumen_tanggal'),
							'dokumen_nomor'			=> $this->input->post('dokumen_nomor'),
							'tanggal_mulai'			=> $tanggal_mulai,
							'pilihan_status_tanah'	=> $this->input->post('pilihan_status_tanah'),
						);
						$simpan=$this->db->insert('inv_inventaris_peserta_f', $values);
					//}
				}
				/*end simpan pada bedadatabase form*/
			}
			
			if($simpan==true){
				$dataupdate__['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate__['nilai_pengadaan']= $this->kegiatankelompok_model->sum_jumlah_item( $id_pengadaan,'harga');
				$dataupdate__['jumlah_unit']= $this->kegiatankelompok_model->sum_unit($id_pengadaan)->num_rows();
				$key__['id_pengadaan'] = $id_pengadaan;
        		$this->db->update("inv_pengadaan",$dataupdate__,$key__);
				die("OK|");
			}else{
				 die("Error|Proses data gagal");
			}
		}
		
	}
	function dodel_peserta($kode=0,$id_peserta="",$table=0){
		$this->authentication->verify('eform','del');

		if($this->kegiatankelompok_model->delete_entryitem_table($kode,$id_peserta,$table)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	
	public function get_autonama() {
        $kode = $this->input->post('code_mst_inv_peserta',TRUE); //variabel kunci yang di bawa dari input text id kode
        $query = $this->mkota->get_allkota(); //query model
 
        $kota       =  array();
        foreach ($query as $d) {
            $kota[]     = array(
                'label' => $d->nama_kota, //variabel array yg dibawa ke label ketikan kunci
                'nama' => $d->nama_kota , //variabel yg dibawa ke id nama
                'ibukota' => $d->ibu_kota, //variabel yang dibawa ke id ibukota
                'keterangan' => $d->keterangan //variabel yang dibawa ke id keterangan
            );
        }
        echo json_encode($kota);      //data array yang telah kota deklarasikan dibawa menggunakan json
    }

}
