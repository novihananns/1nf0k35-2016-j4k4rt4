<?php

class Logactivity extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('logactivity_model');

	}
	
	function index($page=1)
	{
		$this->authentication->verify('eform','show');

		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Log Activity";
		$data['datalogfilter'] = array('filterlogin' => 'Log Login','filterlogout' => 'Log Logout','filterlogdelete' => 'Log Delete');
		if ($this->session->userdata('filter_datafilterlog') == 'filterlogdelete') {
			$this->db->like(array('activity' => "delete"));
		}else if ($this->session->userdata('filter_datafilterlog') == 'filterlogin') {
			$this->db->like(array('activity' => "login"));
		}else if ($this->session->userdata('filter_datafilterlog') == 'filterlogout') {
			$this->db->like(array('activity' => "logout"));
		}else{
			$this->db->where(array('activity' => "datakosong"));
		}
		$data['query'] = $this->logactivity_model->get_data(); 

		$data['content'] = $this->parser->parse("admin/logactivity/show",$data,true);

		$this->template->show($data,"home");
	}
	function get_filterlogactivity(){
		if ($this->input->post('filterlog')!="null") {
			if($this->input->is_ajax_request()) {
				$filterlog = $this->input->post('filterlog');
				$this->session->set_userdata('filter_datafilterlog',$this->input->post('filterlog'));
			}
			// show_404();
		}
	}

}
