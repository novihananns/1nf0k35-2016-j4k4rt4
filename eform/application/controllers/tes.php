<?php
class Tes extends CI_Controller {

    public function __construct(){
		parent::__construct();
	}
	function index(){
		$query = $this->db->query("SELECT id_data_keluarga,id_pilihan_kelamin,COUNT(id_pilihan_kelamin) as kelamin FROM data_keluarga_anggota  GROUP BY id_data_keluarga,id_pilihan_kelamin  order by id_data_keluarga asc limit 0,1000")->result_array();
		foreach ($query as $key) {
			
			// if ($key['id_pilihan_kelamin']=='5') {
			// 	$values = array('jml_anaklaki'=> $key['kelamin']);
			// 	$kode = array('id_data_keluarga' => $key['id_data_keluarga']);
			// 	$this->db->update('data_keluarga',$values,$kode);
			// }else{
			// 	$values = array('jml_anakperempuan'=> $key['kelamin']);
			// 	$kode = array('id_data_keluarga' => $key['id_data_keluarga']);
			// 	$this->db->update('data_keluarga',$values,$kode);
			// }
			// if ($key['id_data_keluarga']=='3172010001053') {
			// 	print_r($key);
			// }
		}
	}
}
	
