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
	var $secretKey;
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


	   $cnf = $this->get_data_bpjs();
	   $this->server 	= $cnf['server'];
	   $this->username 	= $cnf['username'];
	   $this->password 	= $cnf['password'];
	   $this->consid 	= $cnf['consid'];
	   $this->secretKey = $cnf['secretKey'];
	   $this->xtime = time();
	   $this->maxxtimeget 	= 15;
	   $this->maxxtimepost 	= 120;
	   
	   $this->xauth = base64_encode($this->username.':'.$this->password.':095');
	   $this->data = $this->consid."&".time();
	   $this->signature = hash_hmac('sha256', $this->data, $this->secretKey, true);
	   $this->xsign = base64_encode($this->signature);
	}
	function get_data_bpjs()
    {
    	$data = array();
    	$id='P'.$this->session->userdata('puskesmas');
    	$this->db->where('code',$id);
    	$this->db->select("*");
    	$data = $this->db->get('cl_phc_bpjs')->row_array();
        if (!empty($data['server'])){
            return $data;
        }else{
        	return $data;
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
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      die(json_encode(array("res"=>"error","msg"=>$data)));
	    }
	    $data = json_decode($response,true);
	    
	    if($data["metaData"]["code"]=="500"){
	      die(json_encode(array("res"=>"500","msg"=>$data["metaData"]["message"])));
	    } 
	    /*update message ketika nomor kartu tidak ditemukan--> kasus no kartu tidak sama dengan 13*/
	    if($data["metaData"]["code"]=="412"){
	      die(json_encode(array("res"=>"412","msg"=>$data["response"][0]["message"])));
	    } 

	    return $data;
	}

	function postApi($url="", $data=array()){
	   try
	    {
	      $response = \Httpful\Request::post($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
		  ->body($data)
		  ->sendsJson()
		  ->timeout($this->maxxtimepost)
	      ->send();
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      die(json_encode(array("res"=>"error","msg"=>$data)));
	    }
	    $data = json_decode($response,true);
	    
		if($response["metaData"]["code"]=="201"){

		}elseif($response["metaData"]["code"]=="304"){

		}else{

		}

	    return $data;
	}

	function putApi($url="", $data=array()){
	   try
	    {
	      $response = \Httpful\Request::post($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
		  ->body($data)
		  ->sendsJson()
		  ->timeout($this->maxxtimepost)
	      ->send();
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      die(json_encode(array("res"=>"error","msg"=>$data)));
	    }
	    $data = json_decode($response,true);
	    
		if($response["metaData"]["code"]=="200"){

		}else{

		}

	    return $data;
	}

	function bpjs_option($type="poli"){

		$data = $this->getApi('poli/fktp/0/99');

      	return $data['response']['list'];
	}

	function bpjs_search($by="nik",$no){

		if($by == "nik"){
			$data = $this->getApi('peserta/nik/'.$no);
		}else{
			$data = $this->getApi('peserta/'.$no);
		}

      	return $data;
	}

}
?>