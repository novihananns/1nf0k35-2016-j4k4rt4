<?php
class Admin_config_model extends CI_Model {

    var $tabel    = 'app_config';

    function __construct() {
        parent::__construct();
        $this->load->dbutil();
    }
    function get_data_bpjs()
    {
    	$data = array();
    	$id='P'.$this->session->userdata('puskesmas');
    	$this->db->where('code',$id);
    	$query = $this->db->get('cl_phc_bpjs');
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }
        $query->free_result();    
        return $data;
    }
    function checkBPJS($code=""){
        //$kode = lcfirst($code);
        if ($this->dbutil->database_exists("epuskesmas_live_jaktim_$code"))
        {
            $this->load->database("epuskesmas_live_jaktim_".$code, FALSE, TRUE);

            $row = array();
            $data = $this->db->get('bpjs_setting')->result_array();
            foreach ($data as $dt) {
                $row[$dt['name']] = $dt['value'];
            }

            $data = array(
                'code' => $code,
                'server'    => $row['bpjs_server'],
                'username'  => $row['bpjs_username'],
                'password'  => $row['bpjs_password'],
                'consid'    => $row['bpjs_consid'],
                'secretkey' => $row['bpjs_secret']
            );

            $this->load->database("default", FALSE, TRUE);
            $this->db->delete('cl_phc_bpjs', array('code' => $code));
            $this->db->insert('cl_phc_bpjs', $data);

            return $data;
        }else{
            $data = array(
                'code' => "kosong",
            );
            return $data;
        }
    }
    function insert_databpjs($value=0)
    {
    	$id='P'.$this->session->userdata('puskesmas');
    	$this->db->where('code',$id);
    	$query = $this->db->get('cl_phc_bpjs');
        if ($query->num_rows() > 0){
            $dataup = array(
            	'server' => $this->input->post('serverbpjs'),
            	'username' => $this->input->post('usernamebpjs'),
            	'password' => $this->input->post('passwordbpjs'),
            	'consid' => $this->input->post('considbpjs'),
            	'secretkey' => $this->input->post('keybpjs'),
            );
            $this->db->where('code',$id);
            $this->db->update("cl_phc_bpjs",$dataup);
        }else{
        	$data = array(
            	'server' => $this->input->post('serverbpjs'),
            	'username' => $this->input->post('usernamebpjs'),
            	'password' => $this->input->post('passwordbpjs'),
            	'consid' => $this->input->post('considbpjs'),
            	'secretkey' => $this->input->post('keybpjs'),
            	'code' => $id,
            );
            $this->db->insert('cl_phc_bpjs',$data);
        }
    }
    function get_data_puskes()
    {
    	$id='P'.$this->session->userdata('puskesmas');
    	$this->db->where('code',$id);
    	return $this->db->get('cl_phc')->result();
    }

    function get_data()
    {
        $query = $this->db->get($this->tabel);
		foreach($query->result_array() as $key=>$value){
			if($value['key']!='district') $data[$value['key']]=$value['value'];
		}
        return $data;
    }

    function get_theme()
    {
        $query = $this->db->get('app_theme');
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['id_theme']]=$dt['name']." :: ".$dt['folder'];
		}
		$query->free_result();    
		return $data;
    }
	
    function update_entry()
    {
		$theme_default['value']=$this->input->post('theme_default');
		$this->db->update($this->tabel, $theme_default, array('key' => 'theme_default'));

		$theme_offline['value']=$this->input->post('theme_offline');
		$this->db->update($this->tabel, $theme_offline, array('key' => 'theme_offline'));

		$title['value']=$this->input->post('title');
		$this->db->update($this->tabel, $title, array('key' => 'title'));

		if($this->input->post('online')){
			$online['value']=1;
		}else{
			$online['value']=0;
		}
		$this->db->update($this->tabel, $online, array('key' => 'online'));

		$description['value']=$this->input->post('description');
		$this->db->update($this->tabel, $description, array('key' => 'description'));

		$keywords['value']=$this->input->post('keywords');
		$this->db->update($this->tabel, $keywords, array('key' => 'keywords'));

		$epuskesmas_server['value']=$this->input->post('epuskesmas_server');
		$this->db->update($this->tabel, $epuskesmas_server, array('key' => 'epuskesmas_server'));

		$epuskesmas_user['value']=$this->input->post('epuskesmas_user');
		$this->db->update($this->tabel, $epuskesmas_user, array('key' => 'epuskesmas_user'));

		$epuskesmas_password['value']=$this->input->post('epuskesmas_password');
		$this->db->update($this->tabel, $epuskesmas_password, array('key' => 'epuskesmas_password'));

		return true;
    }
	
}