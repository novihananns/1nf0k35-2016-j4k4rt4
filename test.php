<?php
   include('httpful.phar');

   //$server = "http://api.bpjs-kesehatan.go.id/pcare-rest/v1/";
   $server = "http://dvlp.bpjs-kesehatan.go.id:9080/pcare-rest-dev/v1/";
   $xtime = time();


   //eclinic
   $consid = "15451";
   $secretKey = "2aX99C1E11";
   $username = "10231701";
   $password = "serasi";
   //pulogadung
   $consid = "28381";
   $secretKey = "0kT81E2A7F";
   $username = "09030200";
   $password = "123456";
   //tes
   $consid = "23921";
   $secretKey = "0pMBE6D40F";
   $username = "pkmbangko";
   $password = "05050101";

   $xauth = base64_encode($username.':'.$password.':095');
   $data = $consid."&".time();
   $signature = hash_hmac('sha256', $data, $secretKey, true);
   $xsign = base64_encode($signature);
 
    $data_kunjungan = array(
      "kdProviderPeserta" => "09010101",
      "tglDaftar" => "09-05-2016",
      "noKartu" => "0001058669785",
      "kdPoli" => "020",
      "keluhan" => null,
      "kunjSakit" => false,
      "sistole" => 0,
      "diastole" => 0,
      "beratBadan" => 0,
      "tinggiBadan" => 0,
      "respRate" => 0,
      "heartRate" => 0,
      "rujukBalik" => 0,
      "rawatInap" => false
    ); 

    /*
////GET PESERTA
   try
    {
      $response = \Httpful\Request::get($server.'/peserta/0001058669785')
      ->xConsId($consid)
      ->xTimestamp($xtime)
      ->xSignature($xsign)
      ->xAuthorization("Basic ".$xauth)
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
////POST PENDAFTARAN
   try
    {
      $response = \Httpful\Request::post($server.'pendaftaran')
      ->xConsId($consid)
      ->xTimestamp($xtime)
      ->xSignature($xsign)
      ->xAuthorization("Basic ".$xauth)
        ->body($data_kunjungan)
        ->sendsJson()
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
    */
////GET DAFTAR
   try
    {
      $response = \Httpful\Request::get($server.'/pendaftaran/tglDaftar/09-05-2016/0/999')
      ->xConsId($consid)
      ->xTimestamp($xtime)
      ->xSignature($xsign)
      ->xAuthorization("Basic ".$xauth)
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
      die(json_encode(array("res"=>"error","msg"=>$data["metaData"]["message"])));
    } 
    /*update message ketika nomor kartu tidak ditemukan--> kasus no kartu tidak sama dengan 13*/
    if($data["metaData"]["code"]=="412"){
      die(json_encode(array("res"=>"error","msg"=>$data["response"][0]["message"])));
    } 

      echo $response;
    
?>