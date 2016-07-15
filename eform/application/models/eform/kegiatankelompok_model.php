<?php
class Kegiatankelompok_model extends CI_Model {

    var $tabel    = 'data_kegiatan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
        $this->load->model('bpjs');
    }
    function get_data_puskesmas()
    {   
        $this->db->order_by('value','asc');
        $query = $this->db->get('cl_phc'); 
        return $query->result();    
    }
    function lengkapdata($bpjs){
        $this->db->where('bpjs',$bpjs);
        $query = $this->db->get('data_keluarga_anggota');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }
    function add_pesetabpjsterdaftar($id_data_kegiatan,$no_kartu){
        $this->db->where('id_data_kegiatan',$id_data_kegiatan);
        $this->db->where('no_kartu',$no_kartu);
        $query = $this->db->get('data_kegiatan_peserta');
        if ($query->num_rows() > 0) {
            $this->db->where('id_data_kegiatan',$id_data_kegiatan);
            $this->db->where('no_kartu',$no_kartu);
            $this->db->delete('data_kegiatan_peserta');
            return true;
        }else{
            $datalengkap = $this->lengkapdata($no_kartu);
            $databpjs = $this->bpjs->bpjs_search('bpjs',$no_kartu);
            $tglla = explode("-", $databpjs['response']['tglLahir']);
            $tgllahir = $tglla[2].'-'.$tglla[1].'-'.$tglla[0];
            $values = array(
                'id_data_kegiatan'      => $id_data_kegiatan,
                'no_kartu'              => $databpjs['response']['noKartu'],
                'nama'                  => $databpjs['response']['nama'],
                'sex'                   => $databpjs['response']['sex'],//==5 ? "L":"P",
                'jenis_peserta'         => $databpjs['response']['jnsPeserta']['nama'],
                'tgl_lahir'             => $tgllahir
            );
            $this->db->insert('data_kegiatan_peserta', $values);
            return true;
        }
    }
    function add_pesertabpjs($id_kegiatan,$id_data){

        $db = explode('_tr_',$id_data);
        for($i=0; $i<(count($db))-1; $i++){
            $peserta = explode('_td_', $db[$i]);
            $datalengkap = $this->lengkapdata($peserta[0]);
            $databpjs = $this->bpjs->bpjs_search('bpjs',$peserta[0]);
            $values = array(
                'id_data_kegiatan'      => $id_kegiatan,
                'no_kartu'              => $datalengkap['bpjs'],
                'nama'                  => $datalengkap['nama'],
                'sex'                   => $datalengkap['id_pilihan_kelamin']==5 ? "L":"P",
                'jenis_peserta'         => $databpjs['response']['jnsPeserta']['nama'],
                'tgl_lahir'             => $datalengkap['tgl_lahir']
            );
            $this->db->insert('data_kegiatan_peserta', $values);
            
            
        }
        return true;
        
        
    }
    function get_nama($kolom_sl,$tabel,$kolom_wh,$kond){
       $this->db->where($kolom_wh,$kond);
        $this->db->select($kolom_sl);
        $query = $this->db->get($tabel)->result();
        foreach ($query as $key) {
            return $key->$kolom_sl;
        }
    }
    function get_data_anggotaKeluarga($id=0,$start=0,$limit=999999,$options=array()){
        
        $this->db->select("data_kegiatan_peserta.no_kartu,data_keluarga_anggota.*,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia");
        $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
        $this->db->order_by('data_keluarga_anggota.no_anggota','asc');
        $this->db->join('data_kegiatan_peserta',"data_kegiatan_peserta.no_kartu = data_keluarga_anggota.bpjs and data_kegiatan_peserta.id_data_kegiatan=".'"'.$id.'"'."",'left');
        // $this->db->where('`data_keluarga_anggota`.`bpjs` NOT IN (select `no_kartu` from `data_kegiatan_peserta`)', NULL, FALSE);
        $query =$this->db->group_by("bpjs");
        $query =$this->db->get("data_keluarga_anggota",$limit,$start);
        
        return $query->result();
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->select("$this->tabel.*,mas_club.nama as club,mas_club.alamat,mas_club_kelompok.value as kelompok,(select count(*) from data_kegiatan_peserta where id_data_kegiatan = data_kegiatan.id_data_kegiatan) as jmlpeserta",false);
        $this->db->order_by('tgl','desc');
        $this->db->join('mas_club','mas_club.clubId=data_kegiatan.kode_club','left');
        $this->db->join('mas_club_kelompok','mas_club_kelompok.id_mas_club_kelompok=data_kegiatan.kode_kelompok','left');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }
    public function getItem($table,$data){
        $this->db->select("data_kegiatan_peserta.*,kelamin.value as jenis_kelamin,data_kegiatan.*,(year(curdate())-year(data_kegiatan_peserta.tgl_lahir)) as usia");
        // $this->db->join('data_keluarga_anggota', "data_keluarga_anggota.bpjs=data_kegiatan_peserta.no_kartu",'left');
        $this->db->join('data_kegiatan', "data_kegiatan.id_data_kegiatan=data_kegiatan_peserta.id_data_kegiatan",'left');
        $this->db->join('mst_keluarga_pilihan kelamin', "kelamin.id_pilihan=data_kegiatan_peserta.sex and tipe='jk'",'left');
        return $this->db->get_where($table, $data);
    }

 	function get_data_row($kode){
		$data = array();
		$this->db->where("id_data_kegiatan",$kode);
		$this->db->select("$this->tabel.*,IF(kode_kelompok = '00','Non-Prolanis',IF(kode_kelompok = '01','Diabetes Melitus',IF(kode_kelompok = '02','Hipertensi','-'))) as namakelompok,mas_club.alamat",false);
        $this->db->order_by('tgl','desc');
        $this->db->join('mas_club','mas_club.clubId=data_kegiatan.kode_club','left');
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
    function get_pilihan($pilihan){
        $query = $this->db->get_where('mst_keluarga_pilihan',array('tipe'=>$pilihan));
        
        return $query->result();
    }
	
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, $data);
    }

    
    function getNourutkel($pukes){
        $this->db->like('id_data_kegiatan', $pukes);
        $this->db->order_by('id_data_kegiatan', 'DESC');
        $id = $this->db->get('data_kegiatan')->row();

        if(empty($id->id_data_kegiatan)){
            $data = array(
                'id_data_kegiatan'  => $pukes."001"
            );            
        }else{
            $last_id = substr($id->id_data_kegiatan, -3) + 1;
            $last_id = str_repeat("0",3-strlen($last_id)).$last_id;

            $data = array(
                'id_data_kegiatan'  => $pukes.$last_id,
            );            
        }

        return $data;
    }
   
   
    function insert_data_from($id_barang,$kode_proc,$tanggal_diterima,$kode)
    {   $tanggal = $this->tanggal($kode);
        $values = array(
            'id_mst_inv_barang'     => $id_barang,
            'nama_barang'           => $this->input->post('nama_barang'),
            'harga'                 => $this->input->post('harga'),
            'keterangan_pengadaan'  => $this->input->post('keterangan_pengadaan'),
            'pilihan_status_invetaris'  => $this->input->post('pilihan_status_invetaris'),
            'tanggal_pembelian'     => $tanggal,
            'tanggal_pengadaan'     => $tanggal,
            'id_pengadaan'          => $kode,
            'tanggal_diterima'      => $tanggal_diterima,
            'barang_kembar_proc'    => $kode_proc,
            'code_cl_phc'           => 'P'.$this->session->userdata('puskesmas'),
        );
        if($this->db->insert('inv_inventaris_barang', $values)){
            return $this->db->insert_id();
        }else{
            return mysql_error();
        }
    }
    function insert_entry()
    {   
        $datapus =$this->session->userdata('puskesmas');
        $pus= $datapus;
        $id = $this->getNourutkel($pus);
        $tg = explode("-", $this->input->post('tgl'));
        $tgldata = $tg[2].'-'.$tg[1].'-'.$tg[0];
        $data['id_data_kegiatan']           = $id['id_data_kegiatan'];
        $data['tgl']                        = $tgldata;
        $data['kode_kelompok']              = $this->input->post('kode_kelompok');
        $data['status_penyuluhan']          = $this->input->post('edukasi');
        $data['status_senam']               = $this->input->post('senam');
        $data['kode_club']                  = $this->input->post('jenis_kelompok');
        $data['materi']                     = $this->input->post('materi');
        $data['pembicara']                  = $this->input->post('pembicara');
        $data['lokasi']                     = $this->input->post('lokasi');
        $data['biaya']                      = $this->input->post('biaya');
        $data['keterangan']                 = $this->input->post('keterangan');
        $data['code_cl_phc']                = 'P'.$this->session->userdata('puskesmas');
        if($this->db->insert($this->tabel, $data)){
            return $data['id_data_kegiatan'];
        }else{
            return mysql_error();
        }
    }
    function update_entry($kode)
    {
    	$datapus =$this->session->userdata('puskesmas');
        $pus=substr($datapus, 1,12);
        $id = $this->getNourutkel($pus);
        $tg = explode("-", $this->input->post('tgl'));
        $tgldata = $tg[2].'-'.$tg[1].'-'.$tg[0];
        $data['status_penyuluhan']          = $this->input->post('edukasi');
        $data['status_senam']               = $this->input->post('senam');
        $data['materi']                     = $this->input->post('materi');
        $data['pembicara']                  = $this->input->post('pembicara');
        $data['lokasi']                     = $this->input->post('lokasi');
        $data['biaya']                        = $this->input->post('biaya');
        $data['keterangan']                   = $this->input->post('keterangan');
		$this->db->where('id_data_kegiatan',$kode);

		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }
    function get_data_anggotaKeluarga_count($id){
        
        $this->db->select("count(*) as totaldata");
        $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
        $this->db->order_by('data_keluarga_anggota.no_anggota','asc');
        $this->db->join('data_kegiatan_peserta',"data_kegiatan_peserta.no_kartu = data_keluarga_anggota.bpjs and data_kegiatan_peserta.id_data_kegiatan=".'"'.$id.'"'."",'left');
        // $query =$this->db->group_by("bpjs");
        $query =$this->db->get("data_keluarga_anggota");
        if ($query->num_rows > 0) {
            foreach ($query->result() as $key) {
                $data = $key->totaldata;
            }
        }else{
            $data = 0;
        }
        return $data;
    }
    function namaanggotakegiatan($kode){
        $this->db->where('id_data_kegiatan',$kode);
        $query = $this->db->get('data_kegiatan_peserta');
        $temp ='';
        foreach ($query->result() as $key) {
            $data = $temp.$key->no_kartu.'-'.$key->nama;
            $temp = $data.', ';
        }
        return $temp;
    }
    function getnamapuskesmas(){
        $code = 'P'.$this->session->userdata('puskesmas');
        $this->db->where('code',$code);
        $query = $this->db->get('cl_phc');
        foreach ($query->result() as $key) {
            $nama = $key->value;
        }
        return $nama;
    }
    function namakegiatan($kode){
        $this->db->where('id_data_kegiatan',$kode);
        $query = $this->db->get('data_kegiatan');
        $temp ='';
        foreach ($query->result() as $key) {
            $data = $key->materi.', ';
            $temp = $data;
        }
        return $temp;
    }

	function deletealldata($kode)
	{
        $namaanggotakegiatan = $this->namaanggotakegiatan($kode);
        $namapuskes     = $this->getnamapuskesmas();
        $namakegiatan = $this->namakegiatan($kode);
        $ipuser = $this->input->ip_address();
        $recorddelete = "$namapuskes delete: data_kegiatan $kode - $namakegiatan ($namaanggotakegiatan) : ".$ipuser;
        $this->user->recorddeletedata($recorddelete);

		$this->db->where('id_data_kegiatan',$kode);
		$this->db->delete('data_kegiatan_peserta');

        $this->db->where('id_data_kegiatan',$kode);
        return $this->db->delete('data_kegiatan');
	}
    function get_jenis(){
        return $this->db->get('mas_club_kelompok')->result();
    }
    function kegitandetail($id_data_kegiatan,$no_kartu){
        $this->db->where('id_data_kegiatan',$id_data_kegiatan);
        $this->db->where('no_kartu',$no_kartu);
        $query = $this->db->get('data_kegiatan_peserta');
        $temp ='';
        foreach ($query->result() as $key) {
            $data = $temp.$key->no_kartu.'-'.$key->nama;
            $temp = $data.', ';
        }
        return $temp;
    }
	function delete_entryitem($id_data_kegiatan,$no_kartu)
	{   
        $kegitandetail = $this->kegitandetail($id_data_kegiatan,$no_kartu);
        $namapuskes     = $this->getnamapuskesmas();
        $ipuser = $this->input->ip_address();
        $recorddelete = "$namapuskes delete data_kegiatan_peserta ( $kegitandetail ) : ".$ipuser;
        $this->user->recorddeletedata($recorddelete);


        $this->db->where('no_kartu',$no_kartu);
        $this->db->where('id_data_kegiatan',$id_data_kegiatan);
        $this->db->delete('data_kegiatan_peserta');
        
	}
    function delete_entryitem_table($kode,$id_barang,$table)
    {    
        $ipuser = $this->input->ip_address();
        $recorddelete = "delete data_kegiatan : ".$ipuser;
        $this->user->recorddeletedata($recorddelete);

        
        $this->db->where('id_pengadaan',$kode);
        $this->db->where('id_mst_inv_barang',$id_barang);
        return $this->db->delete($table);
    }

    function bpjs_send_kegiatan(){
        $kode = $this->input->post('kode');

        return $this->bpjs->bpjs_send_kegiatan($kode);
    }

    function bpjs_resend_kegiatan(){
        $kode = $this->input->post('kode');

        return $this->bpjs->bpjs_resend_kegiatan($kode);
    }
}