<?php
class Desa_model extends CI_Model {

    var $tabel    = 'cl_village';
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
        $query = $this->db->query('select * from cl_village order by code');
        $query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();
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

     function update_desa($code){
        $data=array(
           
            'value'        => $this->input->post('value'),
        );
        if($this->db->update('cl_village',$data,array('code' => $code))){
            return true;
        }else{
            return mysql_error();
        }
    }

    function get_kode_village($code=""){
        $data = array();
        $options = array('cl_village.code'=>$code);
        $this->db->select("cl_village.*");
        // $this->db->where('username',$username);
        // $this->db->where('code',$code);
        $query = $this->db->get_where($this->tabel,$options,1);
        if($query->num_rows() > 0){
            $data=$query->row_array();
        }

        $query->result();
        return $data;
    }


    function delete_entry($kode){
        $this->db->where(array('code' => $kode));
        return $this->db->delete($this->tabel);
    }

    function get_desa()
    {
        $sql = "SELECT MID(cl_village.code,0,2) FROM cl_village";
        return $this->db->query($sql)->result();
    }
}