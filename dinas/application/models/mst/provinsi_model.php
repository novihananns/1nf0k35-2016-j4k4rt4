<?php
class Provinsi_model extends CI_Model {

    var $tabel    = 'cl_province';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    function get_nama($kolom_sl,$tabel,$kolom_wh,$kond){
       $this->db->where($kolom_wh,$kond);
        $this->db->select($kolom_sl);
        $query = $this->db->get($tabel)->result();
        foreach ($query as $key) {
            return $key->$kolom_sl;
        }
    }
    function get_data($start=0,$limit=999999,$options=array()){
        $query = $this->db->query('select * from cl_province order by code');
        $query = $this->db->get($this->tabel,$limit,$start);
        
     return $query->result();

    }
    

    function update_provinsi($code){
        $data=array(
           
            'value'        => $this->input->post('value'),
        );
        if($this->db->update('cl_province',$data,array('code' => $code))){
            return true;
        }else{
            return mysql_error();
        }
    }

    function get_kode_province($code=""){
        $data = array();
        $options = array('cl_province.code'=>$code);
        $this->db->select("cl_province.*");
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
    
     function get_datawhere ($code,$condition,$table){
        $this->db->select("*");
     
        $query= $this->db->get($table);
        if($query->num_rows() > 0){
            return $query->result(); 
         }else{
            return 0;
         }
    }
}