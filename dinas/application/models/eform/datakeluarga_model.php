<?php
class Datakeluarga_model extends CI_Model {

    var $tabel    = 'data_keluarga';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    function get_nama($kolom_sl,$tabel,$kolom_wh,$kond){
       $this->db->where($kolom_wh,$kond);
        $this->db->select($kolom_sl);
        $query = $this->db->get("$tabel");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                return $key->$kolom_sl;
            }
        }else{
            return "Seluruh Kecamatan";
        }
        
    }
    function get_data($start=0,$limit=999999,$options=array()){
        $this->db->select("$this->tabel.*,cl_village.value");
		$this->db->join('cl_village', "data_keluarga.id_desa = cl_village.code",'inner');

        //$kec = substr($this->session->userdata('puskesmas'), 0,7);
        //this->db->like('id_kecamatan',$kec);
		$this->db->order_by('data_keluarga.tanggal_pengisian','asc');
		$query =$this->db->get($this->tabel,$limit,$start);
        
        return $query->result();
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
    function get_data_row($id){
        $data = array();
        $options = array('id_data_keluarga' => $id);
        $query = $this->db->get_where($this->tabel,$options);
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
    function get_data_row_anggota($idkeluarga,$noanggota){
        $data = array();
        $options = array('id_data_keluarga' => $idkeluarga);
        $options = array('no_anggota' => $noanggota);
        $query = $this->db->get_where("data_keluarga_anggota",$options);
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
    
    public function getSelectedData($table,$data){
        return $this->db->get_where($table, array('code'=>$data));
    }
    
    function insertDataTable(){
        $id_data_keluarga = $this->input->post('id_data_keluarga');
        $kode = $this->input->post('kode');
        $value = $this->input->post('value');
        $this->db->select('*');
        $this->db->from('data_keluarga_profile');
        $this->db->where('id', 'D');
        $this->db->where('id_data_keluarga', $id_data_keluarga);
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        if($query->num_rows() == 1){
            $this->db->query("update data_keluarga_profile set value='$value' where id='D' and id_data_keluarga='$id_data_keluarga' and kode='$kode'")->result();
        }else{
            $data=array(
                        'id' => 'D',
                        'id_data_keluarga'=> $id_data_keluarga,
                        'kode'=>$kode,
                        'value'=>$value,
                        );
            $this->db->insert('data_keluarga_profile',$data);
        }
    }

    function getNourutkel($kelurahan){
        $this->db->where('id_desa', $kelurahan);
        $this->db->order_by('id_data_keluarga', 'DESC');
        $id = $this->db->get('data_keluarga')->row();

        if(empty($id->id_data_keluarga)){
            $data = array(
                'id_data_keluarga'  => $kelurahan."001",
                'nourutkel'         => "001"
            );            
        }else{
            $last_id = substr($id->id_data_keluarga, -3) + 1;
            $last_id = str_repeat("0",3-strlen($last_id)).$last_id;

            $data = array(
                'id_data_keluarga'  => $kelurahan.$last_id,
                'nourutkel'         => $last_id
            );            
        }

        return $data;
    }
    
    function insert_entry(){
        $id = $this->getNourutkel($this->input->post('kelurahan'));

        $data=array(
            'id_data_keluarga'  => $id['id_data_keluarga'],
            'nourutkel'         => $id['nourutkel'],
            'tanggal_pengisian' => date("Y-m-d", strtotime($this->input->post('tgl_pengisian'))),
            'jam_data'          => $this->input->post('jam_data'),
            'alamat'            => $this->input->post('alamat'),
            'id_propinsi'       => $this->input->post('provinsi'),
            'id_kota'           => $this->input->post('kota'),
            'id_kecamatan'      => $this->input->post('id_kecamatan'),
            'id_desa'           => $this->input->post('kelurahan'),
            'id_kodepos'        => $this->input->post('kodepos'),
            'rw'                => $this->input->post('dusun'),
            'rt'                => $this->input->post('rt'),
            'norumah'           => $this->input->post('norumah'),
            'nama_komunitas'    => $this->input->post('namakomunitas'),
            'namakepalakeluarga'=> $this->input->post('namakepalakeluarga'),
            'notlp'             => $this->input->post('notlp'),
            'namadesawisma'     => $this->input->post('namadesawisma'),
            'id_pkk'            => $this->input->post('jabatanstuktural'),
        );
        if($this->db->insert('data_keluarga',$data)){
            return $id['id_data_keluarga'];
        }else{
            return mysql_error();
        }

    }
    function get_datawhereasli ($code,$condition,$table){
        $this->db->select("*");
        $this->db->where($condition,$code);
        return $this->db->get($table)->result();
    }
    function noanggota($id_data_keluarga){
        $q = $this->db->query("select MAX(no_anggota) as kd_max from data_keluarga_anggota");
        $kd = "";
        if($q->num_rows()>0)
        {
            foreach($q->result() as $k)
            {
                $kd = ((int)$k->kd_max)+1;
            }
        }
        else
        {
            $kd = "1";
        }
        return $kd;
    }
    function insert_dataAnggotaKeluarga(){

        $data=array(
            'id_data_keluarga'  => $this->input->post('id_data_keluarga'),
            'no_anggota'        => $this->noanggota($this->input->post('id_data_keluarga')),
            'nama'              => $this->input->post('nama'),
            'nik'               => $this->input->post('nik'),
            'tmpt_lahir'        => $this->input->post('tmpt_lahir'),
            'tgl_lahir'             => date("Y-m-d", strtotime($this->input->post('tgl_lahir'))),
            'id_pilihan_hubungan'   => $this->input->post('id_pilihan_hubungan'),
            'id_pilihan_kelamin'    => $this->input->post('id_pilihan_kelamin'),
            'id_pilihan_agama'      => $this->input->post('id_pilihan_agama'),
            'id_pilihan_pendidikan' => $this->input->post('id_pilihan_pendidikan'),
            'id_pilihan_pekerjaan'  => $this->input->post('id_pilihan_pekerjaan'),
            'id_pilihan_kawin'      => $this->input->post('id_pilihan_kawin'),
            'id_pilihan_jkn'        => $this->input->post('id_pilihan_jkn'),
            'bpjs'                  => $this->input->post('bpjs'),
            'suku'                  => $this->input->post('suku'),
            'no_hp'                 => $this->input->post('no_hp')
        );
        if($this->db->insert('data_keluarga_anggota',$data)){
            return $data['no_anggota'];
        }else{
            return mysql_error();
        }
    }
    function update_entry($id_data_keluarga){
        $data=array(
            'alamat'            => $this->input->post('alamat'),
            'id_kodepos'        => $this->input->post('kodepos'),
            'rw'                => $this->input->post('dusun'),
            'rt'                => $this->input->post('rt'),
            'norumah'           => $this->input->post('norumah'),
            'nama_komunitas'    => $this->input->post('namakomunitas'),
            'namakepalakeluarga'=> $this->input->post('namakepalakeluarga'),
            'notlp'             => $this->input->post('notlp'),
            'namadesawisma'     => $this->input->post('namadesawisma'),
            'id_pkk'            => $this->input->post('jabatanstuktural'),
            'nama_koordinator'  => $this->input->post('nama_koordinator'),
            'nama_pendata'      => $this->input->post('nama_pendata'),
            'jam_selesai'       => $this->input->post('jam_selesai')
        );
        if($this->db->update('data_keluarga',$data,array('id_data_keluarga' => $id_data_keluarga))){
            return true;
        }else{
            return mysql_error();
        }
    }
    
    function get_data_profile($id){
        $this->db->select('*');
        $this->db->from('data_keluarga_profile');
        $this->db->where('id', 'D');
        $this->db->where('id_data_keluarga', $id);
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            return $query->result(); 
         }else{
            return 'salah';
         }
    }

    function delete_entry($kode){
        $this->db->where('id_data_keluarga',$kode);
        $this->db->delete('data_keluarga_anggota');

        $this->db->where('id_data_keluarga',$kode);
        $this->db->delete('data_keluarga_anggota_profile');

        $this->db->where('id_data_keluarga',$kode);
        $this->db->delete('data_keluarga_kb');

        $this->db->where('id_data_keluarga',$kode);
        $this->db->delete('data_keluarga_pembangunan');

        $this->db->where('id_data_keluarga',$kode);
        $this->db->delete('data_keluarga_profile');

        $this->db->where('id_data_keluarga',$kode);
        return $this->db->delete($this->tabel);
    }

    function delete_Anggotakeluarga($kode,$noanggota){
        $this->db->where('id_data_keluarga',$kode);
        $this->db->where('no_anggota',$noanggota);

        $this->db->delete("data_keluarga_anggota_profile");

        $this->db->where('id_data_keluarga',$kode);
        $this->db->where('no_anggota',$noanggota);

        return $this->db->delete("data_keluarga_anggota");
    }
    
    function get_provinsi($provinsi=""){
        if($provinsi==""){
            $provinsi = substr($this->session->userdata('puskesmas'),0,2);
        }

        $this->db->where('code',$provinsi);
        $query = $this->db->get("cl_province");
        
        return $query->result();
    }

    function get_kotakab($kotakab=""){
        if($kotakab==""){
            $kotakab = substr($this->session->userdata('puskesmas'),0,4);
        }

        $this->db->where('code',$kotakab);
        $query = $this->db->get("cl_district");
        
        return $query->result();
    }

    function get_kecamatan($kecamatan=""){
        if($kecamatan==""){
            $kecamatan = substr($this->session->userdata('puskesmas'),0,7);
        }

        $this->db->like('code',$kecamatan);
        $query = $this->db->get("cl_kec");
        
        return $query->result();
    }
    
    function get_desa($kecamatan=""){
        if($kecamatan==""){
            $kecamatan = substr($this->session->userdata('puskesmas'),0,7);
        }

        $this->db->like('code',$kecamatan);
        $query = $this->db->get("cl_village");
        
        return $query->result();
    }
    
    function get_pos($kecamatan=""){
        if($kecamatan==""){
            $kecamatan = substr($this->session->userdata('puskesmas'),0,7);
        }

        $this->db->select('distinct pos',false);
        $this->db->order_by('pos','ASC');

        $this->db->like('code',$kecamatan);
        $query = $this->db->get("cl_village");
        
        return $query->result();
    }
    
    function get_pkk(){
        $this->db->order_by('id_pkk','asc');
        $query = $this->db->get('mas_pkk');
        
        return $query->result();
    }
    function datajml(){
        $data = array();
        $this->db->select("SUM(jml_anaklaki) as jml_laki,SUM(jml_anakperempuan) as jml_perempuan, SUM((jml_anaklaki+jml_anakperempuan)) as jml_jiwa,COUNT(*) AS jml_kk",false);
        $query = $this->db->get('data_keluarga');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }else{
            $data['jml_jiwa'] ='0';
            $data['jml_kk'] ='0';
            $data['jml_laki'] ='0';
            $data['jml_perempuan'] ='0';
        }
        $query->free_result();
        return $data;
    }
    function get_pkk_value($id){
        $query = $this->db->get_where('mas_pkk',array('id_pkk'=>$id));
        
        return $query->row_array();
    }
    function get_pilihan($pilihan){
        $query = $this->db->get_where('mst_keluarga_pilihan',array('tipe'=>$pilihan));
        
        return $query->result();
    }
    function update_kepala(){
        $id_data_keluarga = $this->input->post('id_data_keluarga');
        $kode = str_replace('keluarga6_','', $this->input->post('kode'));
        $noanggota = $this->input->post('noanggota');
        $value = $this->input->post('value');
        if($kode == "tgl_lahir"){
            $value = date('Y-m-d',strtotime($value));
        }
        $dataubah = array($kode => $value);
        $keyubah = array(
                         'id_data_keluarga' => $id_data_keluarga,
                         'no_anggota' => $noanggota,
                         );
        $this->db->update("data_keluarga_anggota",$dataubah,$keyubah);

    }
     function addanggotaprofile(){
        $id_data_keluarga = $this->input->post('id_data_keluarga');
        $kode = $this->input->post('kode');
        $value = $this->input->post('value');
        $noanggota = $this->input->post('noanggota');
        $this->db->select('*');
        $this->db->from('data_keluarga_anggota_profile');
        $this->db->where('id', 'G');
        $this->db->where('no_anggota', $noanggota);
        $this->db->where('id_data_keluarga', $id_data_keluarga);
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        if(substr($kode, -5) == "cebox"){
            if($query->num_rows() > 0){
                $this->db->where('id','G');
                $this->db->where('no_anggota',$noanggota);
                $this->db->where('id_data_keluarga',$id_data_keluarga);
                $this->db->where('kode',$kode);
                $this->db->delete('data_keluarga_anggota_profile');
             }else{
                $data=array(
                            'id' => 'G',
                            'id_data_keluarga'=> $id_data_keluarga,
                            'kode'=>$kode,
                            'no_anggota'=>$noanggota,
                            'value'=>$value,
                            );
                $this->db->insert('data_keluarga_anggota_profile',$data);
            }
        }else{
            if($query->num_rows() > 0){
                $values = array(
                    'value'          => $value,
                );
                $this->db->update('data_keluarga_anggota_profile', $values, array('id' => 'G','id_data_keluarga'=>$id_data_keluarga,'no_anggota'=>$noanggota,'kode'=>$kode));
             }else{
                $data=array(
                            'id' => 'G',
                            'id_data_keluarga'=> $id_data_keluarga,
                            'kode'=>$kode,
                            'no_anggota'=>$noanggota,
                            'value'=>$value,
                            );
                $this->db->insert('data_keluarga_anggota_profile',$data);
             }
        }
    }
    function get_data_anggotaprofile($idkeluarga,$noanggota){
        $this->db->select('*');
        $this->db->from('data_keluarga_anggota_profile');
        $this->db->where('id', 'G');
        $this->db->where('id_data_keluarga', $idkeluarga);
        $this->db->where('no_anggota', $noanggota);
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result(); 
         }else{
            return 'salah';
         }
    }
     function get_datawhere ($code,$condition,$table){
        $this->db->select("*");
        $this->db->like($condition,$code);
        $query= $this->db->get($table);
        if($query->num_rows() > 0){
            return $query->result(); 
         }else{
            return 0;
         }
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
        $this->db->select("data_keluarga.namakepalakeluarga,data_keluarga.id_data_keluarga as id,data_keluarga_anggota.*,(year(curdate())-year(data_keluarga_anggota.tgl_lahir)) as usia,data_keluarga.jml_anaklaki,data_keluarga.jml_anakperempuan,data_keluarga.pus_ikutkb,data_keluarga.pus_tidakikutkb,data_keluarga.nourutkel");
        $this->db->join("data_keluarga","data_keluarga.id_data_keluarga=data_keluarga_anggota.id_data_keluarga","right");
        $this->db->order_by('data_keluarga.nourutkel');
        $query =$this->db->get('data_keluarga_anggota');
        $data = $query->result_array(); 

        return $data;
    }

    function get_data_all_anggota($keluarga="-"){
        $data = array();

        $this->db->where("id_data_keluarga IN ('".$keluarga."')");
        $anggota = $this->db->get('data_keluarga_anggota')->result_array(); 
        foreach ($anggota as $pr) {
            $data[$pr['id_data_keluarga']][$pr['no_anggota']] = $pr;
        }

        return $data;
    }

    function get_data_all_anggota_profile($keluarga="-"){
        $data = array();

        $this->db->where("id_data_keluarga IN ('".$keluarga."')");
        $prof = $this->db->get('data_keluarga_anggota_profile')->result_array(); 
        foreach ($prof as $pr) {
            $data[$pr['id_data_keluarga']][$pr['no_anggota']][$pr['kode']] = $pr['value'];
        }

        return $data;
    }

    function get_data_all_profile($keluarga="-"){
        $data = array();

        $this->db->where("id_data_keluarga IN ('".$keluarga."')");
        $prof = $this->db->get('data_keluarga_profile')->result_array(); 
        foreach ($prof as $pr) {
            $data[$pr['id_data_keluarga']][$pr['kode']] = $pr['value'];
        }

        return $data;
    }

    function get_data_all_kb($keluarga="-"){
        $data = array();

        $this->db->where("id_data_keluarga IN ('".$keluarga."')");
        $prof = $this->db->get('data_keluarga_kb')->result_array(); 
        foreach ($prof as $pr) {
            $data[$pr['id_data_keluarga']][$pr['kode']] = $pr['value'];
        }

        return $data;
    }

    function get_data_all_pembangunan($keluarga="-"){
        $data = array();

        $this->db->where("id_data_keluarga IN ('".$keluarga."')");
        $prof = $this->db->get('data_keluarga_pembangunan')->result_array(); 
        foreach ($prof as $pr) {
            $data[$pr['id_data_keluarga']][$pr['kode']] = $pr['value'];
        }

        return $data;
    }
}