<?php
class Pembangunan_keluarga_model extends CI_Model {

    var $tabel    = 'data_keluarga';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->order_by('tanggal_pengisian','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
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
    public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('code'=>$data));
    }
    function insertdatatable_pembangunan(){
        $id_data_keluarga = $this->input->post('id_data_keluarga');
        $kode = $this->input->post('kode');
        $value = $this->input->post('value');
        $this->db->select('*');
        $this->db->from('data_keluarga_pembangunan');
        $this->db->where('id', 'III');
        $this->db->where('id_data_keluarga', $id_data_keluarga);
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        if(substr($kode, -5) == "cebo4"){
            if($query->num_rows() == 1){
                $this->db->where('id','III');
                $this->db->where('id_data_keluarga',$id_data_keluarga);
                $this->db->where('kode',$kode);
                $querydelete = $this->db->delete('data_keluarga_pembangunan');
                if ($querydelete) {
                    return 'ok';
                }
             }else{
                $data=array(
                            'id' => 'III',
                            'id_data_keluarga'=> $id_data_keluarga,
                            'kode'=>$kode,
                            'value'=>$value,
                            );
                $queryinsert = $this->db->insert('data_keluarga_pembangunan',$data);
                if ($queryinsert) {
                    return 'ok';
                }
            }
        }else{
            if($query->num_rows() == 1){
                $values = array(
                    'value'          => $value,
                );
                $queryupdate = $this->db->update('data_keluarga_pembangunan', $values, array('id' => 'III','id_data_keluarga'=>$id_data_keluarga,'kode'=>$kode));
                if ($queryupdate) {
                    return 'ok';
                }

             }else{
                $data=array(
                            'id' => 'III',
                            'id_data_keluarga'=> $id_data_keluarga,
                            'kode'=>$kode,
                            'value'=>$value,
                            );
                $queryins = $this->db->insert('data_keluarga_pembangunan',$data);
                if ($queryins) {
                    return 'ok';
                }
             }
        }
    }
   
    function get_data_pembangunan($id){
        $this->db->select('*');
        $this->db->from('data_keluarga_pembangunan');
        $this->db->where('id', 'III');
        $this->db->where('id_data_keluarga', $id);
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            return $query->result(); 
         }else{
            return 'salah';
         }
    }

    function delete_entry($kode)
    {
        $this->db->where('id_data_keluarga',$kode);

        return $this->db->delete($this->tabel);
    }
}