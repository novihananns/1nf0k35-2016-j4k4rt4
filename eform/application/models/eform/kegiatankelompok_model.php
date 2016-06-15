<?php
class Kegiatankelompok_model extends CI_Model {

    var $tabel    = 'data_kegiatan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');

    }
    function get_data_puskesmas()
    {   
        $this->db->order_by('value','asc');
        $query = $this->db->get('cl_phc'); 
        return $query->result();    
    }
    
    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->select("$this->tabel.*,IF(kode_kelompok = '00','Non-Prolanis',IF(kode_kelompok = '01','Diabetes Melitus',IF(kode_kelompok = '02','Hipertensi','-'))) as namakelompok,mas_club.alamat",false);
        $this->db->order_by('tgl','desc');
        $this->db->join('mas_club','mas_club.clubId=data_kegiatan.kode_club','left');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }
    public function getItem($table,$data){
        $this->db->select("data_keluarga_anggota.*,kelamin.value as jenis_kelamin,data_kegiatan.*,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia");
        $this->db->join('data_keluarga_anggota', "data_keluarga_anggota.bpjs=data_kegiatan_peserta.no_kartu",'left');
        $this->db->join('data_kegiatan', "data_kegiatan.id_data_kegiatan=data_kegiatan_peserta.id_data_kegiatan",'left');
        $this->db->join('mst_keluarga_pilihan kelamin', "kelamin.id_pilihan=data_keluarga_anggota.id_pilihan_kelamin and tipe='jk'",'left');
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
    
	function get_data_barang_edit($id_barang,$kd_proc,$kd_inventaris){
		$data = array();
		
		/*$this->db->select("inv_inventaris_barang.id_inventaris_barang,inv_inventaris_barang.id_mst_inv_barang,inv_inventaris_barang.nama_barang,inv_inventaris_barang.harga,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang) AS jumlah,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang)*inv_inventaris_barang.harga AS totalharga,
                        inv_inventaris_barang.keterangan_pengadaan,inv_inventaris_barang.tanggal_diterima,
                        inv_inventaris_barang.waktu_dibuat,inv_inventaris_barang.terakhir_diubah,inv_inventaris_barang.pilihan_status_invetaris");
		$this->db->where("id_inventaris_barang",$kd_inventaris);
		$this->db->where("id_mst_inv_barang",$id_barang);
        $this->db->where("barang_kembar_proc",$kd_proc);*/
        $sql="SELECT inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah FROM inv_inventaris_barang WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )";
		$query = $this->db->query($sql, array($kd_inventaris));
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
    function get_data_barang_edit_table($kd_inventaris,$pilih_table){
        $data = array();
        if($pilih_table=='inv_inventaris_barang_a'){

            $sql= "SELECT inv_inventaris_barang_a.*, inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah 
FROM inv_inventaris_barang 
LEFT JOIN inv_inventaris_barang_a ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_a.id_inventaris_barang 
AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_a.id_mst_inv_barang)
WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )
";           $query= $this->db->query($sql, array($kd_inventaris));

        }else if($pilih_table=='inv_inventaris_barang_b'){

            $sql= "SELECT inv_inventaris_barang_b.*, inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah 
FROM inv_inventaris_barang 
LEFT JOIN inv_inventaris_barang_b ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_b.id_inventaris_barang 
AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_b.id_mst_inv_barang)
WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )
";           $query= $this->db->query($sql, array($kd_inventaris));

        }else if($pilih_table=='inv_inventaris_barang_c'){

            $sql= "SELECT inv_inventaris_barang_c.*, inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah 
FROM inv_inventaris_barang 
LEFT JOIN inv_inventaris_barang_c ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_c.id_inventaris_barang 
AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_c.id_mst_inv_barang)
WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )
";           $query= $this->db->query($sql, array($kd_inventaris));            

        }else if($pilih_table=='inv_inventaris_barang_d'){

            $sql= "SELECT inv_inventaris_barang_d.*, inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah 
FROM inv_inventaris_barang 
LEFT JOIN inv_inventaris_barang_d ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_d.id_inventaris_barang 
AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_d.id_mst_inv_barang)
WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )
";           $query= $this->db->query($sql, array($kd_inventaris));

        }else if($pilih_table=='inv_inventaris_barang_e'){

            $sql= "SELECT inv_inventaris_barang_e.*, inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah 
FROM inv_inventaris_barang 
LEFT JOIN inv_inventaris_barang_e ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_e.id_inventaris_barang 
AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_e.id_mst_inv_barang)
WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )
";           $query= $this->db->query($sql, array($kd_inventaris));

        }else if($pilih_table=='inv_inventaris_barang_f'){   

            $sql= "SELECT inv_inventaris_barang_f.*, inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah 
FROM inv_inventaris_barang 
LEFT JOIN inv_inventaris_barang_f ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_f.id_inventaris_barang 
AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_f.id_mst_inv_barang)
WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )
";           $query= $this->db->query($sql, array($kd_inventaris));

        }
        
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, $data);
    }

    function get_permohonan_id($puskesmas="")
    {
    	$this->db->select('MAX(id_inv_permohonan_barang)+1 as id');
    	$this->db->where('code_cl_phc',$puskesmas);
    	$permohonan = $this->db->get('inv_permohonan_barang')->row();
    	if (empty($permohonan->id)) {
    		return 1;
    	}else {
    		return $permohonan->id;
    	}
	}
	function get_inventarisbarang_id($id,$barang,$table)
    {
    	$query  = $this->db->query("SELECT MAX(id_inventaris_barang) as id from $table WHERE id_pengadaan=$id AND id_mst_inv_barang=$barang");
        $result = $query->result();
    	if(empty($result)){
    		return 1;
    	}else {
    		foreach ($query->result() as $jum ) {
    			return $jum->id+1;
    		}
    	}

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
   
    function kode_pengadaan($kode){
        $inv=explode(".", $kode);
        $kode_pengadaan = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
        $urut = $this->nourut($kode_pengadaan);
        return  $kode_pengadaan.$urut;
    }
    function nourut($kode_pengadaan){
        $jmldata = strlen($kode_pengadaan);
        $q = $this->db->query("select MAX(RIGHT(id_pengadaan,6)) as kd_max from inv_pengadaan where (LEFT(id_pengadaan,$jmldata))=$kode_pengadaan");
        $nourut="";
        if($q->num_rows()>0)
        {
            foreach($q->result() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $nourut = sprintf("%06s", $tmp);
            }
        }
        else
        {
            $nourut = "000001";
        }
        return $nourut;
    }
    function tanggal($pengadaan){
        $query = $this->db->query("select tgl_pengadaan from inv_pengadaan where id_pengadaan = $pengadaan")->result();
        foreach ($query as $key) {
            return $key->tgl_pengadaan;
        }
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
        $pus=substr($datapus, 1,12);
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
        $data['biaya']                        = $this->input->post('biaya');
        $data['keterangan']                   = $this->input->post('keterangan');
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
        $data['tgl']                        = $tgldata;
        $data['kode_kelompok']              = $this->input->post('kode_kelompok');
        $data['status_penyuluhan']          = $this->input->post('edukasi');
        $data['status_senam']               = $this->input->post('senam');
        $data['kode_club']                  = $this->input->post('jenis_kelompok');
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
    function tampil_id($status){
    	$this->db->select('code');
    	$this->db->where('value',$status);
		$this->db->where('tipe','status_pengadaan');
		$query=$this->db->get('mst_inv_pilihan');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $id = $k->code;
            }
        }
        else
        {
            $id = 1;
        }
        	return  $id;
    }

    function tampilstatus_id($status,$tipe){
        $this->db->select('code');
        $this->db->where('value',$status);
        $this->db->where('tipe',$tipe);
        $query=$this->db->get('mst_inv_pilihan');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $id = $k->code;
            }
        }
        else
        {
            $id = 1;
        }
            return  $id;
    }

    function getPilihan($tipe,$code){
        $this->db->select('value');
        $this->db->where('code',$code);
        $this->db->where('tipe',$tipe);
        $query=$this->db->get('mst_inv_pilihan')->row();
        if(!empty($query)){
            return  $query->value;
        }else{
            return $tipe;
        }
    }

    function update_status()
    {	
        $pilihan_inv  = $this->input->post('pilihan_inv');
    	$kode_proc= $this->input->post('kode_proc');
        $id_pengadaan= $this->input->post('id_pengadaan');

        $id = $this->db->query("SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc =$kode_proc and id_pengadaan=$id_pengadaan")->result(); 
        foreach ($id as $key) {
            $data['pilihan_status_invetaris']   = $this->tampilstatus_id($pilihan_inv,'status_inventaris');
            $this->db->update('inv_inventaris_barang', $data,array('barang_kembar_proc'=> $this->input->post('kode_proc')));
        }
            

    }
    function sum_jumlah_item($kode,$tipe){
    	$this->db->select_sum($tipe);
    	$this->db->where('id_pengadaan',$kode);
		$query=$this->db->get('inv_inventaris_barang');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $jumlah = $k->harga;
            }
        }
        else
        {
            $jumlah = 0;
        }
        return  $jumlah;
    }
    function barang_kembar_proc($kode){
        $q = $this->db->query("SELECT  MAX(RIGHT(barang_kembar_proc,4)) as kd_max FROM inv_inventaris_barang WHERE id_mst_inv_barang=$kode ORDER BY barang_kembar_proc DESC");
        $kd = "";
        if($q->num_rows()>0)
        {
           foreach($q->result() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }
        else
        {
            $kd = "0001";
        }
        return $kode.$kd;
    }
    function sum_unit($kode)
    {
        $this->db->select("*");
        $this->db->where('id_pengadaan',$kode);  
        return $query = $this->db->get("inv_inventaris_barang"); 
    }
	function delete_entry($kode)
	{
		$this->db->where('id_pengadaan',$kode);

		return $this->db->delete($this->tabel);
	}
    function jumlahtable($table,$id_inventaris_barang){

        $this->db->where('id_inventaris_barang',$id_inventaris_barang);
        $q = $this->db->get($table);
        $kd = 0;
        if($q->num_rows()>0)
        {
           $kd = $q->num_rows();
        }
        else
        {
            $kd = 0;
        }
        return $kd;
    }
	function delete_entryitem($kode,$kd_proc)
	{   $id = $this->db->query("SELECT * FROM inv_inventaris_barang WHERE id_inventaris_barang =$kode")->result(); 
        foreach ($id as $key) {
              $this->db->where('barang_kembar_proc',$kd_proc);
              $this->db->delete('inv_inventaris_barang');
                $kodebarang_ = substr($kode, -14,-12);
                if($kodebarang_=='01') {
                    $this->db->where('id_inventaris_barang',$key->id_inventaris_barang);
                    $this->db->delete('inv_inventaris_barang_a');
                }else if($kodebarang_=='02') {  
                    $this->db->where('id_inventaris_barang',$key->id_inventaris_barang);
                    $this->db->delete('inv_inventaris_barang_b');
                }else if($kodebarang_=='03') {  
                    $this->db->where('id_inventaris_barang',$key->id_inventaris_barang);
                    $this->db->delete('inv_inventaris_barang_c');
                }else if($kodebarang_=='04') {                 
                    $this->db->where('id_inventaris_barang',$key->id_inventaris_barang);
                    $this->db->delete('inv_inventaris_barang_d');
                }else if($kodebarang_=='05') {  
                    $this->db->where('id_inventaris_barang',$key->id_inventaris_barang);
                    $this->db->delete('inv_inventaris_barang_e');
                }else if($kodebarang_=='06') {  
                    $this->db->where('id_inventaris_barang',$key->id_inventaris_barang);
                    $this->db->delete('inv_inventaris_barang_f');
                }
        }
        
	}
    function delete_entryitem_table($kode,$id_barang,$table)
    {    
        $this->db->where('id_pengadaan',$kode);
        $this->db->where('id_mst_inv_barang',$id_barang);
        return $this->db->delete($table);
    }
	function get_databarang($start=0,$limit=999999)
    {
		$this->db->order_by('uraian','asc');
        $query = $this->db->get('mst_inv_barang',$limit,$start);
        return $query->result();
    }
}