<?php
class Clubprolanis_model extends CI_Model {

    var $tabel    = 'mas_club';
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
		$query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

    function insertClub($data = array()){
        $this->db->where('clubId',$data['clubId']);
        $query = $this->db->get($this->tabel);
        if ($query->num_rows() > 0){
            $this->db->where('clubId',$data['clubId']);
            $this->db->update($this->tabel, $data);
        }else{
            $this->db->insert($this->tabel, $data);
        }
    }

    function get_puskesmas(){
        $this->db->where('username <> ""');
        $this->db->select("cl_phc.*,cl_phc_bpjs.username");
        $this->db->join("cl_phc_bpjs","cl_phc_bpjs.code=cl_phc.code");
        $query= $this->db->get("cl_phc");
        if($query->num_rows() > 0){
            return $query->result(); 
         }else{
            return 0;
         }
    }
}