<?php
class Admin_config_model extends CI_Model {

    var $tabel    = 'app_config';

    function __construct() {
        parent::__construct();
    }
    
	function checkBPJS($code=""){
		$this->load->database("epuskesmas_live_jaktim_".$code, FALSE, TRUE);
		
		$row = array();
		$data = $this->db->get('bpjs_setting')->result_array();
		foreach ($data as $dt) {
			$row[$dt['name']] = $dt['value'];
		}

		$data = array(
			'code' => $code,
			'server' 	=> $row['bpjs_server'],
			'username' 	=> $row['bpjs_username'],
			'password' 	=> $row['bpjs_password'],
			'consid' 	=> $row['bpjs_consid'],
			'secretkey' => $row['bpjs_secret']
			);

		$this->load->database("default", FALSE, TRUE);
		$this->db->delete('cl_phc_bpjs', array('code' => $code));
		$this->db->insert('cl_phc_bpjs', $data);

		return $data;
    }
    function get_detail($code='')
    {
    	$data = array();
    	$this->db->where('code',"$code");
    	$query = $this->db->get('cl_phc_bpjs');
    	if ($query->num_rows() > 0){
			$data = $query->row_array();
		}else{
			$data['server'] ='';
			$data['username'] ='';
			$data['password'] ='';
			$data['consid'] ='';
			$data['secretkey'] ='';
		}

		$query->free_result();    
		return $data;
    }
    function get_nama($code=0,$select='')
    {
    	$data = array();
    	$this->db->select("$select");
    	$this->db->where('code',"$code");
    	$query = $this->db->get('cl_phc');
    	if ($query->num_rows() > 0){
    		foreach ($query->result() as $key) {
    			return $key->$select;
    		}
		}else{
			return $select;
		}
    }

    function get_data($start=0,$limit=999999,$options=array()){
        $this->db->select("cl_phc.*,cl_phc_bpjs.server,cl_phc_bpjs.username,cl_phc_bpjs.password,cl_phc_bpjs.consid,cl_phc_bpjs.secretkey");
        $this->db->join("cl_phc_bpjs","cl_phc_bpjs.code=cl_phc.code","left");

		$query =$this->db->get('cl_phc',$limit,$start);
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

	
}