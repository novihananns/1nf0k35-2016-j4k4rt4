<?php
class Puskesmas_model extends CI_Model {

    var $tabel    = 'cl_phc';
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
        $query = $this->db->query('select * from cl_phc order by code');
		$query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

    function get_data_export($start=0,$limit=999999,$options=array()){
        $query = $this->db->query('select * from cl_phc order by code');
        $query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }
    
    function get_data_puskesmas($start=0,$limit=999999,$options=array()){
        $query = $this->db->query('select * from cl_phc order by code');
        $query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();

    }

    function get_kode_phc($code=""){
        $data = array();
        $options = array('cl_phc.code'=>$code);
        $this->db->select("cl_phc.*");
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
    function update_puskess($code){
        $data=array(
            'value'            => $this->input->post('value'),
            'alamat'        => $this->input->post('alamat'),
            'tlp'                => $this->input->post('tlp')
        );
        if($this->db->update('cl_phc',$data,array('code' => $code))){
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


    
}