<?php
class Laporanpendata_model extends CI_Model {

    var $tabel    = 'data_keluarga';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
        require_once(APPPATH.'third_party/httpful.phar');
    }
    
// ,
//                             (
//                                 SELECT COUNT(id_data_keluarga) FROM data_keluarga_anggota 
//                                 WHERE id_data_keluarga IN(
//                                     SELECT id_data_keluarga 
//                                     FROM data_keluarga a 
//                                     WHERE a.nama_koordinator = data_keluarga.nama_koordinator 
//                                     AND a.nama_pendata = data_keluarga.nama_pendata
//                                     )
//                             ) AS totalanggotakeluarga
    function get_data($start=0,$limit=999999,$options=array()){
        $this->db->select("data_keluarga.*,COUNT(id_data_keluarga) AS totalkk",false);
        $kec = substr($this->session->userdata('puskesmas'), 0,7);
        $this->db->like('id_kecamatan',$kec);
		$this->db->group_by('nama_koordinator,nama_pendata');
		$query =$this->db->get('data_keluarga',$limit,$start);
        
        return $query->result();
    }
    function get_data_export_detail($anggota = 0){
        $this->db->where('id_data_keluarga',$anggota);
        $this->db->select("$this->tabel.*,cl_village.value,
            (SELECT COUNT(no_anggota) l FROM data_keluarga_anggota WHERE id_pilihan_kelamin='5' AND id_data_keluarga=data_keluarga.id_data_keluarga) AS laki,
            (SELECT COUNT(no_anggota) p FROM data_keluarga_anggota WHERE id_pilihan_kelamin='6' AND id_data_keluarga=data_keluarga.id_data_keluarga) AS pr,
            (SELECT COUNT(no_anggota) jml FROM data_keluarga_anggota WHERE id_data_keluarga=data_keluarga.id_data_keluarga) AS jmljiwa,
            ");
        $this->db->join('cl_village', "data_keluarga.id_desa = cl_village.code",'inner');
        $query =$this->db->get($this->tabel);
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }
    public function get_data_export($start=0,$limit=999999,$options=array())
    {
        $this->db->select("$this->tabel.*,cl_village.value,
            (SELECT COUNT(no_anggota) l FROM data_keluarga_anggota WHERE id_pilihan_kelamin='5' AND id_data_keluarga=data_keluarga.id_data_keluarga) AS laki,
            (SELECT COUNT(no_anggota) p FROM data_keluarga_anggota WHERE id_pilihan_kelamin='6' AND id_data_keluarga=data_keluarga.id_data_keluarga) AS pr,
            (SELECT COUNT(no_anggota) jml FROM data_keluarga_anggota WHERE id_data_keluarga=data_keluarga.id_data_keluarga) AS jmljiwa,
            ");
        $this->db->join('cl_village', "data_keluarga.id_desa = cl_village.code",'inner');

        $kec = substr($this->session->userdata('puskesmas'), 0,7);
        $this->db->like('id_data_keluarga',$kec);
        $this->db->order_by('data_keluarga.tanggal_pengisian','asc');
        $query =$this->db->get($this->tabel,$limit,$start);
        
        return $query->result();
    }
    
    function get_data_all($keluarga="-"){
        if($_POST) {
            $ord = $this->input->post('sortdatafield');
            if(!empty($ord)) {
                $this->db->order_by($ord, $this->input->post('sortorder'));
            }
        }

        $data = array();
        
        $this->db->where("data_keluarga.id_data_keluarga IN ('".$keluarga."')");
        $this->db->select("data_keluarga.namakepalakeluarga,data_keluarga.id_data_keluarga as id,data_keluarga_anggota.*,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia,data_keluarga.jml_anaklaki,data_keluarga.jml_anakperempuan, data_keluarga.pus_ikutkb,data_keluarga.pus_tidakikutkb ,data_keluarga.nourutkel");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota.id_data_keluarga","right");
        $this->db->order_by('data_keluarga.nourutkel');
        $query =$this->db->get('data_keluarga_anggota');
        $data = $query->result_array(); 

        return $data;
    }

    
    function get_data_anggotaKeluarga($start=0,$limit=999999,$options=array()){
        $this->db->select("data_keluarga_anggota.*, hubungan.value as hubungan,jeniskelamin.value as jeniskelamin,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia,agama.value as agama,pendidikan.value as pendidikan,pekerjaan.value as pekerjaan,kawin.value as kawin,jkn.value as jkn");
        $this->db->join("mst_keluarga_pilihan hubungan","data_keluarga_anggota.id_pilihan_hubungan = hubungan.id_pilihan and hubungan.tipe='hubungan'",'left');
        $this->db->join("mst_keluarga_pilihan jeniskelamin","data_keluarga_anggota.id_pilihan_kelamin = jeniskelamin.id_pilihan and jeniskelamin.tipe ='jk'",'left');
        $this->db->join("mst_keluarga_pilihan agama","data_keluarga_anggota.id_pilihan_agama = agama.id_pilihan and agama.tipe ='agama'",'left');
        $this->db->join("mst_keluarga_pilihan pendidikan","data_keluarga_anggota.id_pilihan_pendidikan = pendidikan.id_pilihan and pendidikan.tipe= 'pendidikan'",'left');
        $this->db->join("mst_keluarga_pilihan pekerjaan","data_keluarga_anggota.id_pilihan_pekerjaan = pekerjaan.id_pilihan and pekerjaan.tipe = 'pekerjaan'" ,'left');
        $this->db->join("mst_keluarga_pilihan kawin","data_keluarga_anggota.id_pilihan_kawin = kawin.id_pilihan and kawin.tipe='kawin'",'left');
        $this->db->join("mst_keluarga_pilihan jkn","data_keluarga_anggota.id_pilihan_jkn = jkn.id_pilihan and jkn.tipe='jkn'",'left');
        $this->db->order_by('data_keluarga_anggota.no_anggota','asc');
        $query =$this->db->get("data_keluarga_anggota",$limit,$start);
        
        return $query->result();
    }
    function get_datawhere ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        return $this->db->get($table)->result();
    }
    function get_datawhereasli ($code,$condition,$table){
        $this->db->select("*");
        $this->db->where($condition,$code);
        return $this->db->get($table)->result();
    }
}