<?php
class Kecamatan_model extends CI_Model {

    var $tabel    = 'cl_kec';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    function get_nama($kolom_a,$tabel,$kolom_b,$kond){
       $this->db->where($kolom_b,$kond);
        $this->db->select($kolom_a);
        $query = $this->db->get($tabel)->result();
        foreach ($query as $key) {
            return $key->$kolom_a;
        }
    }
    function get_data($start=0,$limit=999999,$options=array()){
        $query = $this->db->query('select * from cl_kec order by code');
		$query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

       public function get_data_export($start=0,$limit=999999,$options=array())
    {
            $query = $this->db->query('select * from cl_kec order by code');
        $query =$this->db->get($this->tabel,$limit,$start);
     return $query->result();
    }

    
    function get_data_kecamatan($start=0,$limit=999999,$options=array()){
        $query = $this->db->query('select * from cl_kec order by code');
        $query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();

    }
     function update_kecamatan($code){
        $data=array(
            'nama'            => $this->input->post('nama')
     
        );
        if($this->db->update('cl_kec',$data,array('code' => $code))){
            return true;
        }else{
            return mysql_error();
        }
    }

 function get_datawhere ($code,$condition,$table){
        $this->db->select("*");       
        $query= $this->db->get($table);
        if($query->num_rows() > 0){
            return $query->result(); 
         }else{
            return 0;
         }
    }

   function delete_entry($kode){
    
        $this->db->where(array('code' => $kode));
        return $this->db->delete($this->tabel);
    }

    function get_kode_phc($code=""){
        $data = array();
        $options = array('cl_kec.code'=>$code);
        $this->db->select("cl_kec.*");
        // $this->db->where('username',$username);
        // $this->db->where('code',$code);
        $query = $this->db->get_where($this->tabel,$options,1);
        if($query->num_rows() > 0){
            $data=$query->row_array();
        }

        $query->result();
        return $data;
    }
    
}