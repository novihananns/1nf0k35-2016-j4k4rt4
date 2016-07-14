<?php
class Logactivity_model extends CI_Model {

    var $tabel    = 'app_users_activity';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    

    function get_data($start=0,$limit=999999,$options=array())
    {
		
		$this->db->order_by('dtime','desc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('id' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['module']=$dt['module'];
				$data['id_theme']=$dt['id_theme'];
				$data['filename_'.$dt['lang']]=$dt['filename'];
			}
		}

		$query->free_result();    
		return $data;
	}

}