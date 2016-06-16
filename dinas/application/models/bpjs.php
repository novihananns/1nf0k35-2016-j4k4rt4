<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		 CodeIgniter
 * @subpackage 	 Model
 * @category	 PCare API
 *
 */
 
class Bpjs extends CI_Model {

	var $server;
	var $consid;
	var $secretkey;
	var $username; 
	var $password;
	var $xtime;
	var $xauth;
	var $data;
	var $signature;
	var $xsign;

	function __construct() {
		parent::__construct();
	   	
	   	require_once(APPPATH.'third_party/httpful.phar');
	}

	function bpjs_loginswitch($puskesmas = ""){
    	$data = array();
    	$this->db->where('code',$puskesmas);
    	$data = $this->db->get('cl_phc_bpjs')->row_array();
		if(!isset($data['code']) || !isset($data['server']) || !isset($data['username']) || !isset($data['password']) || !isset($data['consid']) || !isset($data['secretkey'])) {
    		return false;
        }else{
		    $this->server 		= $data['server'];
		    $this->username 	= $data['username'];
		    $this->password 	= $data['password'];
		    $this->consid 		= $data['consid'];
		    $this->secretkey 	= $data['secretkey'];
		    $this->xtime 		= time();
		    $this->maxxtimeget 	= 15;
		    $this->maxxtimepost	= 120;
		    $this->xauth 		= base64_encode($this->username.':'.$this->password.':095');
		    $this->data 		= $this->consid."&".time();
		    $this->signature 	= hash_hmac('sha256', $this->data, $this->secretkey, true);
		    $this->xsign 		= base64_encode($this->signature);

		    return true;
	    }
	}

	function getApi($url=""){
	   try
	    {
	      $response = \Httpful\Request::get($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
	      ->timeout($this->maxxtimeget)
	      ->send();
	       $data = json_decode($response,true);
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	      //die(json_encode(array("res"=>"error","msg"=>$data)));

	    }
	    
	    if($data["metaData"]["code"]=="500"){
	      die(json_encode(array("res"=>"500","msg"=>$data["metaData"]["message"])));
	    } 
	    /*update message ketika nomor kartu tidak ditemukan--> kasus no kartu tidak sama dengan 13*/
	    if($data["metaData"]["code"]=="412"){
	      die(json_encode(array("res"=>"412","msg"=>$data["response"][0]["message"])));
	    } 

	    return $data;
	}

	function bpjs_club($puskesmas = ""){
	    $bpjs = $this->bpjs_loginswitch($puskesmas);
	    if($bpjs){
		    $data = array();

			$dt_01 = $this->getApi('kelompok/club/01');
	      	$data['01'] = $dt_01['response']['list'];

			$dt_02 = $this->getApi('kelompok/club/02');
	      	$data['02'] = $dt_02['response']['list'];

	      	return $data;
	    }
	}
}
?>