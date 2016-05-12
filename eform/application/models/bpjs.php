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


	    /*$cnf = $this->get_data_bpjs();
	    $this->server 	= $cnf['server'];
	    $this->username 	= $cnf['username'];
	    $this->password 	= $cnf['password'];
	    $this->consid 	= $cnf['consid'];
	    $this->secretkey = $cnf['secretkey'];
	    $this->xtime = time();
	    $this->maxxtimeget 	= 15;
	    $this->maxxtimepost 	= 120;
	    $this->xauth = base64_encode($this->username.':'.$this->password.':095');
	    $this->data = $this->consid."&".time();
	    $this->signature = hash_hmac('sha256', $this->data, $this->secretkey, true);
	    $this->xsign = base64_encode($this->signature);
	    */
		////////  TES BPJS /////////////////////
		$this->server = "http://dvlp.bpjs-kesehatan.go.id:9080/pcare-rest-dev/v1/";
		$this->username = "pkmbangko";
		$this->password = "05050101";
		$this->consid 	= "23921";
		$this->secretKey = "0pMBE6D40F";
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
        	$data['server'] ='';
        	$data['username'] ='';
        	$data['password'] ='';
        	$data['consid'] ='';
        	$data['secretkey'] ='';
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
	    //die(print_r($response));
		// if($response["metaData"]["code"]=="201"){

		// }elseif($response["metaData"]["code"]=="304"){

		// }else{

		// }

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

	function deleteApi($url=""){
	   try
        {
          $response = \Httpful\Request::delete($this->server.$url)
          ->xConsId($this->consid)
          ->xTimestamp($this->xtime)
          ->xSignature($this->xsign)
          ->xAuthorization("Basic ".$this->xauth)
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
	function inserbpjs($kode){
       $tampildata = $this->getApi('peserta/'.$kode);
       if (($tampildata['metaData']['message']=='error')&&($tampildata['metaData']['code']=='777')) {
           return  'bpjserror';
       }else{
	        if (array_key_exists("kdProvider",$tampildata['response']['kdProviderPst'])){
	            $kodeprov = $tampildata['response']['kdProviderPst']['kdProvider'];
	        }else{
	            $kodeprov = '0';
	        }
            $data_kunjungan = array(
              "kdProviderPeserta" => $kodeprov,
              "tglDaftar" 	=> date('d-m-Y'),
              "noKartu" 	=> $tampildata['response']['noKartu'],
              "kdPoli" 		=> "020",
              "keluhan" 	=> null,
              "kunjSakit" 	=> false,
              "sistole" 	=> 0,
              "diastole" 	=> 0,
              "beratBadan" 	=> 0,
              "tinggiBadan" => 0,
              "respRate" 	=> 0,
              "heartRate" 	=> 0,
              "rujukBalik" 	=> 0,
              "rawatInap" 	=> false
            ); 
            $datavisit = $this->postApi('pendaftaran', $data_kunjungan);
            if (($datavisit['metaData']['message']=='CREATED') && ($datavisit['metaData']['code']=='201')){
            	return $datasmpn = $this->simpandatabpjs($datavisit['response']['message'],$kode);
	        }
	        elseif(($datavisit['metaData']['message']=='NOT_MODIFIED') && ($datavisit['metaData']['code']=='304')){
	            return 'dataada';
	        }
	        elseif(($datavisit['metaData']['message']=='PRECONDITION_FAILED') && ($datavisit['metaData']['code']=='412')){
	            return 'datatidakada';
	        }else{
	            return 'bpjserror';
	        }
        }
    }
   
    function simpandatabpjs($nourut=0,$kartu=0){
        $tampildata = $this->getApi('peserta/'.$kartu);
        if (($tampildata['metaData']['message']=='error') && ($tampildata['metaData']['code']=='777')) {
           return  'bpjserror';
        }else{
           if (isset($tampildata['response']['kdProviderPst']['kdProvider']) && $tampildata['response']['kdProviderPst']['kdProvider']!=""){
                $kodeprov = $tampildata['response']['kdProviderPst']['kdProvider'];
            }else{
                $kodeprov = '0';
            }
            $data = array(
	            'kd_provider_peserta'  =>  $kodeprov,
	            'no_kartu'  	=> $kartu,
	            'tgl_daftar'  	=> date("d-m-Y"),
	            'no_urut'  		=> $nourut
            );
            $this->db->insert('data_keluarga_anggota_bpjs',$data);
            return 'datatersimpan';
        }
    }
    function keluargaanggotabpjs($kode=0){
        $this->db->where('no_kartu',$kode);
        $query = $this->db->get('data_keluarga_anggota_bpjs');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }else{
            $data['kd_provider_peserta'] = '';
            $data['no_kartu'] 	= '';
            $data['tgl_daftar'] = '';
            $data['no_urut'] 	= '';
        }
        $query->free_result();
        return $data;
    }
    function deletebpjs($kode){
    	$tampildata = $this->keluargaanggotabpjs($kode);
    	$datavisit 	= $this->deleteApi("/pendaftaran/peserta/".$tampildata['no_kartu']."/tglDaftar/".$tampildata['tgl_daftar']."/noUrut/".$tampildata['no_urut']);
    	die();
        if (($datavisit['metaData']['message']=='OK')&&($datavisit['metaData']['code']=='200')) {
            return 'datatersimpan';
        }else{
            return 'bpjserror';
        }
    }

}
?>