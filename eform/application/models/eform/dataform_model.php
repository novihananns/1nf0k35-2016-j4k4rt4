<?php
class Dataform_model extends CI_Model {

    var $tabel    = 'data_keluarga';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    
    function insertdataform_profile(){
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
            $dataupdate = array('value' => $value );
            $this->db->where('id','D');
            $this->db->where('id_data_keluarga',$id_data_keluarga);
            $this->db->where('kode',$kode);
            $queryupdate = $this->db->update('data_keluarga_profile',$dataupdate);
            if ($queryupdate) {
                return "ok";
            }
         }else{
            $data=array(
                        'id' => 'D',
                        'id_data_keluarga'=> $id_data_keluarga,
                        'kode'=>$kode,
                        'value'=>$value,
                        );
            $insert = $this->db->insert('data_keluarga_profile',$data);
            if ($insert == true) {
                return "ok";
            }
         }
    }
    function get_data_formprofile($id){
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
    
}