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
    
    function get_data_detail($nama_koordinator,$nama_pendata,$start=0,$limit=999999,$options=array()){
        $this->db->select("$this->tabel.*,cl_village.value");
        $this->db->join('cl_village', "data_keluarga.id_desa = cl_village.code",'inner');
        if ($nama_koordinator == 'null') {
            $this->db->where('nama_koordinator is null');
        }else if ($nama_koordinator == 'kosong') {
            $this->db->where("nama_koordinator =''");
        }else{
            $this->db->where('nama_koordinator',str_replace("%20", ' ', $nama_koordinator));
        }
        if ($nama_pendata == 'null') {
            $this->db->where('nama_pendata is null');
        }else if ($nama_pendata == 'kosong') {
            $this->db->where("nama_pendata =''");
        }else{
            $this->db->where('nama_pendata',str_replace("%20", " ", $nama_pendata));
        }
        $kec = substr($this->session->userdata('puskesmas'), 0,7);
        $this->db->like('id_data_keluarga',$kec);
        $this->db->order_by('data_keluarga.tanggal_pengisian','asc');
        $query =$this->db->get('data_keluarga',$limit,$start);
        
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