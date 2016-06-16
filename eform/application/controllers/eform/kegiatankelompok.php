<?php
class Kegiatankelompok extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('bpjs');
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
	function bpjs_search($by = 'nik',$no){
      	$data = $this->bpjs->bpjs_search($by,$no);

      	echo json_encode($data);
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
	public function getdatakelompokedit()
	{
		if($this->input->is_ajax_request()) {
			$datakelom = $this->input->post('datakelom');
			$kode_club = $this->input->post('kode_club');

			$kode 	= $this->kegiatankelompok_model->getSelectedData('mas_club',array('kdProgram'=>$datakelom))->result();
			
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
			$data['jeniskelompok'] = $this->kegiatankelompok_model->get_jenis();
		
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
			$data['jeniskelompok'] = $this->kegiatankelompok_model->get_jenis();
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
	
	function dodelpermohonan($id_data_kegiatan=0,$no_kartu=0){

		if($this->kegiatankelompok_model->delete_entryitem($id_data_kegiatan,$no_kartu)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
	public function json_pesertabpjs(){
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
		$this->db->where("CHAR_LENGTH(data_keluarga_anggota.bpjs)",'13');
		$this->db->where("data_keluarga_anggota.bpjs !=",'');
		$this->db->where("data_keluarga_anggota.bpjs !=",'-');
		$rows_all = $this->kegiatankelompok_model->get_data_anggotaKeluarga();

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
		
		$this->db->where("CHAR_LENGTH(data_keluarga_anggota.bpjs)",'13');
		$this->db->where("data_keluarga_anggota.bpjs !=",'');
		$this->db->where("data_keluarga_anggota.bpjs !=",'-');
		$rows = $this->kegiatankelompok_model->get_data_anggotaKeluarga($this->input->post('recordstartindex'),$this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_data_keluarga'		=> $act->id_data_keluarga,
				'no_anggota'			=> $act->no_anggota,
				'nama'					=> $act->nama,
				'nik'					=> $act->nik,
				'tmpt_lahir'			=> $act->tmpt_lahir,
				'id_pilihan_kelamin'	=> $act->id_pilihan_kelamin,
				'tgl_lahir'				=> $act->tgl_lahir,
				'tgl_lahirdata'			=> $act->tgl_lahir,
				'jeniskelamin'			=> $act->jeniskelamin,
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
	public function add_pesertabpjs($iddata,$data_peserta)
	{	
		$data['action']			= "add";
		
		if($this->kegiatankelompok_model->add_pesertabpjs($iddata,$data_peserta)==true){			
			die("OK|");
		}else{
			die("Error|Proses data gagal");
		}
	}
	public function detailpeserta($id = 0)
	{
		$data	  	= array();
		$filter 	= array();
		$filterLike = array();
		$no=1;
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir' ) {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where('data_kegiatan_peserta.tgl_lahir',$value);
				}else if($field == 'jenis_kelamin' ) {
					$this->db->where('kelamin.value',$value);
				}else if($field == 'usia' ) {
					$this->db->where('(year(curdate())-year(data_kegiatan_peserta.tgl_lahir))',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$activity = $this->kegiatankelompok_model->getItem('data_kegiatan_peserta', array('data_kegiatan_peserta.id_data_kegiatan'=>$id))->result();
		$no=$this->input->post('recordstartindex')+1;
		foreach($activity as $act) {
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
	
	function form_tab_dpp($pageIndex,$id_kegiatan=0){
		$data = array();
		
		$data['kode']			= $id_kegiatan;
		switch ($pageIndex) {
			case 1:
				$this->add_peserta($id_kegiatan);
				// die($this->parser->parse("eform/kegiatankelompok/peserta_form",$data));
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
	public function tab($index=0,$kode=0)
	{	
		$data['kode']			= $kode;
		die($this->parser->parse('eform/kegiatankelompok/tab_peserta', $data));
	}
	public function add_peserta($kode=0)
	{	

		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('nik', 'NIK', 'trim');
        $this->form_validation->set_rules('bpjs', 'No BPJS', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('id_pilihan_kelamin', 'Jenis Kelamin', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['data_pilihan_kelamin'] = $this->kegiatankelompok_model->get_pilihan("jk");
			$data['alert_form']		= '';
			$data['action']			= "add";
			$data['kode']			= $kode;
			$data['notice']			= validation_errors();

			die($this->parser->parse('eform/kegiatankelompok/peserta_form', $data));
		}else{
			$this->db->where('id_data_kegiatan',$kode);
			$this->db->where('no_kartu',$this->input->post('bpjs'));
			$qwery=$this->db->get('data_kegiatan_peserta');
			if ($qwery->num_rows() > 0) {
				die("Error|Data Telah Tersimpan");
			}else{
				$jenispeserta = 'apa';//$this->input->post('jenis_peserta')
				$tgl = explode('-', $this->input->post('tgl_lahir'));
				$tgl_lahir = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];
				$kel = $this->input->post('id_pilihan_kelamin');
				if ($kel=='6') {
					$kelamin='P';# code...
				}else{
					$kelamin='L';
				}
				$values = array(
					'id_data_kegiatan'		=>$kode,
					'no_kartu' 			  	=> $this->input->post('bpjs'),
					'nama'					=> $this->input->post('nama'),
					'sex' 					=> $this->input->post('id_pilihan_kelamin'),
					'jenis_peserta'		 	=> $jenispeserta,
					'tgl_lahir' 			=> $tgl_lahir,
				);
				$simpan=$this->db->insert('data_kegiatan_peserta', $values);
				if($simpan==true){
					die("OK|Data Tersimpan");
				}else{
					 die("Error|Proses data gagal");
				}
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

}

