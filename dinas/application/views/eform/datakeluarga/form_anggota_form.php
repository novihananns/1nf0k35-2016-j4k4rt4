<script>
  	$(function () { 
      <?php if ($action=="detail") {?>
        $("input[name^=keluarga6]").prop("disabled", true);     
        $("input[name^=kesehatan]").prop("disabled", true); 
        $("select").prop("disabled", true); 
        $("#keluarga6_tgl_lahir").prop("disabled", true); 
  <?php    } ?>
      <?php
      if(isset($data_profile_anggota) and $data_profile_anggota!="salah"){
        foreach($data_profile_anggota as $row){ ?>
          var kode = "<?php echo $row->kode;?>";
          var value= "<?php echo $row->value; ?>";
          if(kode.slice(-5)=="radio"){
            if(value=="0"){
              document.getElementById("<?php echo $row->kode.'_ya';?>").checked = true; 
            }else if(value=="1"){
              document.getElementById("<?php echo $row->kode.'_tidak';?>").checked = true;
            }
          }else if(kode.slice(-5)=="radi3"){
            if(value=="0"){
              document.getElementById("<?php echo $row->kode.'_a';?>").checked = true; 
            }else if(value=="1"){
              document.getElementById("<?php echo $row->kode.'_b';?>").checked = true;
            }else{
              document.getElementById("<?php echo $row->kode.'_c';?>").checked = true;
            }
          }else if(kode.slice(-5)=="radi4"){
            if(value=="0"){
              document.getElementById("<?php echo $row->kode.'_a';?>").checked = true; 
            }else if(value=="1"){
              document.getElementById("<?php echo $row->kode.'_b';?>").checked = true;
            }else if(value=="2"){
              document.getElementById("<?php echo $row->kode.'_c';?>").checked = true;
            }else{
              document.getElementById("<?php echo $row->kode.'_d';?>").checked = true;
            }
          }else if(kode.slice(-5)=="radi5"){
            if(value=="0"){
              document.getElementById("<?php echo $row->kode.'_a';?>").checked = true; 
            }else if(value=="1"){
              document.getElementById("<?php echo $row->kode.'_b';?>").checked = true;
            }else if(value=="2"){
              document.getElementById("<?php echo $row->kode.'_c';?>").checked = true;
            }else if(value=="3"){
              document.getElementById("<?php echo $row->kode.'_d';?>").checked = true;
            }else{
              document.getElementById("<?php echo $row->kode.'_e';?>").checked = true;
            }
          }else if(kode.slice(-5)=="cebox"){
            document.getElementById("<?php echo $row->kode;?>").checked = true;
          }else{
            document.getElementById("<?php echo $row->kode;?>").value = "<?php echo $row->value; ?>";
          }
      <?php
        }
      }
      ?>
      $("input[name^=keluarga6]").change(function(){
        //alert($(this).attr('name')+' ' +$(this).val());
        var noanggota = "<?php echo $noanggota;?>";
        var id_data_keluarga = "<?php echo $id_data_keluarga; ?>";
        $.post("<?php echo base_url()?>eform/data_kepala_keluarga/update_kepala",{kode:$(this).attr('name'),id_data_keluarga:id_data_keluarga,value:$(this).val(),noanggota:noanggota},function(data,status){;
            });
      });

      $("select").change(function(){
        //alert($(this).attr('name')+' ' +$(this).val());
        var noanggota = "<?php echo $noanggota;?>";
        var id_data_keluarga = "<?php echo $id_data_keluarga; ?>";
        $.post("<?php echo base_url()?>eform/data_kepala_keluarga/update_kepala",{kode:$(this).attr('name'),id_data_keluarga:id_data_keluarga,value:$(this).val(),noanggota:noanggota},function(data,status){;
            });
      });
      $("input[name^=kesehatan]").change(function(){
     // alert($(this).attr('name')+' ' +$(this).val());
      var noanggota = "<?php echo $noanggota;?>";
      var id_data_keluarga = "<?php echo $id_data_keluarga; ?>";
        $.post("<?php echo base_url()?>eform/data_kepala_keluarga/addanggotaprofile",{kode:$(this).attr('name'),id_data_keluarga:id_data_keluarga,value:$(this).val(),noanggota:noanggota},function(data,status){;
            });
      })


      $('#btn-up').click(function(){
        $.get('<?php echo base_url()?>eform/data_kepala_keluarga/anggota/{id_data_keluarga}', function (data) {
            $('#content2').html(data);
        });
      });

      $('#btn-up2').click(function(){
          window.scrollTo(0, 600);
          $('#content2' ).scrollTop(0);
      });
      <?php
        if ($action=="detail") {?>
          var disabled = "disabled:true";
      <?php }else{ ?>
          var disabled = "";
      <?php }?>
      $("#keluarga6_tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px',disabled});
      $("#keluarga6_tgl_lahir").on('change', function (event) { 
        var noanggota = "<?php echo $noanggota;?>";
        var id_data_keluarga = "<?php echo $id_data_keluarga; ?>";
          $.post("<?php echo base_url()?>eform/data_kepala_keluarga/update_kepala",{kode:$(this).attr('name'),id_data_keluarga:id_data_keluarga,value:$(this).val(),noanggota:noanggota},function(data,status){;
          });
      }); 

  
	});
</script>

<form action="<?php echo base_url()?>eform/data_kepala_keluarga/{action}/{id_data_keluarga}" method="post">
<div class="row" style="margin: 0" id="tops">
  <div class="col-md-12">
    <div class="box-footer">
      <div class="col-md-6">
        <h4><i class="icon fa fa-group" ></i> Ubah Data Anggota Keluarga</h4>
      </div>
      <div class="col-md-6" style="text-align: right">
        <button type="button" id="btn-up" class="btn btn-success"><i class='fa  fa-arrow-circle-o-up'></i> &nbsp;Kembali</button>
     </div>
    </div>

    <input type="hidden" name="keluarga6_id_data_keluarga" value="{id_data_keluarga}">
    <input type="hidden" name="keluarga6_noanggota" value="{noanggota}">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
          <label>Data Anggota Keluarga</label>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">NIK</div>
            <div class="col-md-8">
              <input type="text" name="keluarga6_nik" id="keluarga6_nik" placeholder="Nomor Induk Keluarga" value="<?php 
                if(set_value('keluarga6_nik')=="" && isset($nik)){
                  echo $nik;
                }else{
                  echo  set_value('keluarga6_nik');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama</div>
            <div class="col-md-8">
              <input type="text" name="keluarga6_nama" id="keluarga6_nama" placeholder="Nama" value="<?php 
                if(set_value('keluarga6_nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('keluarga6_nama');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tempat Lahir</div>
            <div class="col-md-8">
              <input type="text" name="keluarga6_tmpt_lahir" id="keluarga6_tmpt_lahir" placeholder="Tempat Lahir" value="<?php 
                if(set_value('keluarga6_tmpt_lahir')=="" && isset($tmpt_lahir)){
                  echo $tmpt_lahir;
                }else{
                  echo  set_value('keluarga6_tmpt_lahir');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tanggal Lahir</div>
            <div class="col-md-8">
              <div id='keluarga6_tgl_lahir' name="keluarga6_tgl_lahir" value="<?php
                if(set_value('keluarga6_tgl_lahir')=="" && isset($tgl_lahir)){
                  $tgl_lahir = strtotime($tgl_lahir);
                }else{
                  $tgl_lahir = strtotime(set_value('keluarga6_tgl_lahir'));
                }
                if($tgl_lahir=="") $tgl_lahir = time();
                echo date("Y-m-d",$tgl_lahir);
              ?>" >
              </div>
            </div>
          </div>
          
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Hubungan Dengan KK</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_hubungan" id="keluarga6_id_pilihan_hubungan" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_hubungan')=="" && isset($id_pilihan_hubungan)){
                  $pilihan_hubungan = $id_pilihan_hubungan;
                }else{
                  $pilihan_hubungan = set_value('keluarga6_id_pilihan_hubungan');
                }

                foreach($data_pilihan_hubungan as $row_hub){
                $select = $row_hub->id_pilihan == $pilihan_hubungan ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_hub->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_hub->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jenis Kelamin</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_kelamin" id="keluarga6_id_pilihan_kelamin" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_kelamin')=="" && isset($id_pilihan_kelamin)){
                  $pilihan_kelamin = $id_pilihan_kelamin;
                }else{
                  $pilihan_kelamin = set_value('keluarga6_id_pilihan_kelamin');
                }

                foreach($data_pilihan_kelamin as $row_kelamin){
                $select = $row_kelamin->id_pilihan == $pilihan_kelamin ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_kelamin->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_kelamin->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Agama</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_agama" id="keluarga6_id_pilihan_agama" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_agama')=="" && isset($id_pilihan_agama)){
                  $pilihan_agama = $id_pilihan_agama;
                }else{
                  $pilihan_agama = set_value('keluarga6_id_pilihan_agama');
                }

                foreach($data_pilihan_agama as $row_agama){
                $select = $row_agama->id_pilihan == $pilihan_agama ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_agama->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_agama->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Pendidikan</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_pendidikan" id="keluarga6_id_pilihan_pendidikan" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_pendidikan')=="" && isset($id_pilihan_pendidikan)){
                  $pilihan_pendidikan = $id_pilihan_pendidikan;
                }else{
                  $pilihan_pendidikan = set_value('keluarga6_id_pilihan_pendidikan');
                }

                foreach($data_pilihan_pendidikan as $row_pendidikan){
                $select = $row_pendidikan->id_pilihan == $pilihan_pendidikan ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_pendidikan->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_pendidikan->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Pekerjaan</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_pekerjaan" id="keluarga6_id_pilihan_pekerjaan" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_pekerjaan')=="" && isset($id_pilihan_pekerjaan)){
                  $pilihan_pekerjaan = $id_pilihan_pekerjaan;
                }else{
                  $pilihan_pekerjaan = set_value('keluarga6_id_pilihan_pekerjaan');
                }

                foreach($data_pilihan_pekerjaan as $row_pekerjaan){
                $select = $row_pekerjaan->id_pilihan == $pilihan_pekerjaan ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_pekerjaan->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_pekerjaan->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Status Kawin</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_kawin" id="keluarga6_id_pilihan_kawin" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_kawin')=="" && isset($id_pilihan_kawin)){
                  $pilihan_kawin = $id_pilihan_kawin;
                }else{
                  $pilihan_kawin = set_value('keluarga6_id_pilihan_kawin');
                }

                foreach($data_pilihan_kawin as $row_kawin){
                $select = $row_kawin->id_pilihan == $pilihan_kawin ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_kawin->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_kawin->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">JKN</div>
            <div class="col-md-8">
              <select  name="keluarga6_id_pilihan_jkn" id="keluarga6_id_pilihan_jkn" class="form-control">
                <?php
                if(set_value('keluarga6_id_pilihan_jkn')=="" && isset($id_pilihan_jkn)){
                  $pilihan_jkn = $id_pilihan_jkn;
                }else{
                  $pilihan_jkn = set_value('keluarga6_id_pilihan_jkn');
                }

                foreach($data_pilihan_jkn as $row_jkn){
                $select = $row_jkn->id_pilihan == $pilihan_jkn ? 'selected' : '' ;
                ?>
                    <option value="<?php echo $row_jkn->id_pilihan; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_jkn->value)); ?></option>
                <?php
                }    
                ?>
            </select>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor BPJS</div>
            <div class="col-md-8">
              <input type="text" name="keluarga6_bpjs "id="keluarga6_bpjs" placeholder="Nomor BPJS" value="<?php 
                if(set_value('bpjs')=="" && isset($bpjs)){
                  echo $bpjs;
                }else{
                  echo  set_value('bpjs');
                }
                ?>" class="form-control">
            </div>
            <div class="col-md-8">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Suku</div>
            <div class="col-md-8">
              <input type="text" name="keluarga6_suku" id="keluarga6_suku" placeholder="Suku" value="<?php 
                if(set_value('suku')=="" && isset($suku)){
                  echo $suku;
                }else{
                  echo  set_value('suku');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor HP</div>
            <div class="col-md-8">
              <input type="text" name="keluarga6_no_hp" id="keluarga6_no_hp" placeholder="Nomor Rumah" value="<?php 
                if(set_value('no_hp')=="" && isset($no_hp)){
                  echo $no_hp;
                }else{
                  echo  set_value('no_hp');
                }
                ?>" class="form-control">
            </div>
          </div>

          </div>
        </div><!-- /.form-box -->
      </div><!-- /.form-box -->

      <div class="col-md-6">
        <div class="box box-warning">
          <div class="box-body">
          <label>Profile Umum Anggota Keluarga</label>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">1. Punya Akte Lahir ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_1_radio" id="kesehatan_0_g_1_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_1_radio" id="kesehatan_0_g_1_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">2. WNA ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_2_radio" id="kesehatan_0_g_2_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_2_radio" id="kesehatan_0_g_2_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">3. Putus Sekolah ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_3_radio" id="kesehatan_0_g_3_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_3_radio" id="kesehatan_0_g_3_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">4. Ikut PAUD/ Sejenisnya ? </div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_4_radio" id="kesehatan_0_g_4_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_4_radio" id="kesehatan_0_g_4_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-7" style="padding: 5px">5. Ikut Kelompok Belajar ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_5_radio" id="kesehatan_0_g_5_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_5_radio" id="kesehatan_0_g_5_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px 5px 5px 24px">Jika Ya, pilih jenis paket A, B, C baru KF</div>
            <div class="col-md-2 col-xs-3">
              <input type="radio" name="kesehatan_0_g_5_radi4" id="kesehatan_0_g_5_radi4_a" value="0"> A
            </div>
            <div class="col-md-3 col-xs-3">
              <input type="radio" name="kesehatan_0_g_5_radi4" id="kesehatan_0_g_5_radi4_b" value="1"> B
            </div>
            <div class="col-md-2 col-xs-3">
              <input type="radio" name="kesehatan_0_g_5_radi4" id="kesehatan_0_g_5_radi4_c" value="2"> C
            </div>
            <div class="col-md-3 col-xs-3">
              <input type="radio" name="kesehatan_0_g_5_radi4" id="kesehatan_0_g_5_radi4_d" value="3"> KF
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">6. Punya Tabungan ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_6_radio" id="kesehatan_0_g_6_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_6_radio" id="kesehatan_0_g_6_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-7" style="padding: 5px">7. Ikut Koperasi ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_7_radio" id="kesehatan_0_g_7_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_7_radio" id="kesehatan_0_g_7_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px 5px 5px 24px">Jika Ya, tuliskan jenis</div>
            <div class="col-md-5">
              <input type="text" name="kesehatan_0_g_7_koperasi" id="kesehatan_0_g_7_koperasi" placeholder="Ikut Koperasi ?" value="<?php 
                if(set_value('kesehatan_0_g_7_koperasi')=="" && isset($kesehatan_0_g_7_koperasi)){
                  echo $kesehatan_0_g_7_koperasi;
                }else{
                  echo  set_value('kesehatan_0_g_7_koperasi');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">8. Usia Subur ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_8_radio" id="kesehatan_0_g_8_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_8_radio" id="kesehatan_0_g_8_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">9. Hamil ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_9_radio" id="kesehatan_0_g_9_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_9_radio" id="kesehatan_0_g_9_radio_tidak" value="1"> Tidak
            </div>
          </div>
           
          <div class="row" style="margin: 5px;">
            <div class="col-md-7" style="padding: 5px">10. Disabilitas ?</div>
            <div class="col-md-2 col-xs-6">
              <input type="radio" name="kesehatan_0_g_10_radio" id="kesehatan_0_g_10_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_0_g_10_radio" id="kesehatan_0_g_10_radio_tidak" value="1"> Tidak
            </div>
          </div>
           
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px 5px 5px 24px">Jika Ya, tuliskan jenis</div>
            <div class="col-md-5">
              <input type="text" name="kesehatan_0_g_10_jenisrumah" id="kesehatan_0_g_10_jenisrumah" placeholder="Disabilitas ?" value="<?php 
                if(set_value('norumah')=="" && isset($norumah)){
                  echo $norumah;
                }else{
                  echo  set_value('norumah');
                }
                ?>" class="form-control">
            </div>
          </div>

          </div>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->
  </div>
</div>

<div class="row" style="margin: 0">
  <div class="col-md-12">

    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
          <label>Profile Kesehatan Anggota Keluarga (1)</label>
          <br><br>
          <label>Pemeliharaan Kebersihan Diri<br>Perilaku Higienis</label>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Selalu mencuci tangan pakai sabun?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Sebelum menyiapkan Makanan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_1_a_cebox" id="kesehatan_1_g_1_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Setiap kali tangan kotor (pegang uang,
binatang, berkebun)?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_1_b_cebox" id="kesehatan_1_g_1_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Setelah buang air besar?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_1_c_cebox" id="kesehatan_1_g_1_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Setelah mencebok bayi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_1_d_cebox" id="kesehatan_1_g_1_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Setelah menggunakan pestisida/insektisida?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_1_e_cebox" id="kesehatan_1_g_1_e_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">f. Sebelum menyusui bayi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_1_f_cebox" id="kesehatan_1_g_1_f_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">2. Lokasi biasa buang air besar?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Jamban?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_1_g_2_radi5" id="kesehatan_1_g_2_radi5_a" value="0">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Kolam/ Sawah/ Selokan?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_1_g_2_radi5" id="kesehatan_1_g_2_radi5_b" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Sungai/ Danau/ Laut?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_1_g_2_radi5" id="kesehatan_1_g_2_radi5_c" value="2">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Lubang tanah?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_1_g_2_radi5" id="kesehatan_1_g_2_radi5_d" value="3">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Pantai/ Tanah Lapangan/ Kebun/
Halaman?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_1_g_2_radi5" id="kesehatan_1_g_2_radi5_e" value="4">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">3. Sikat gigi setiap hari? (Ya atau Tidak)?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_3_f_cebox" id="kesehatan_1_g_3_f_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">4. Kapan menyikat gigi?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Saat mandi pagi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_4_a_cebox" id="kesehatan_1_g_4_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Saat mandi sore?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_4_b_cebox" id="kesehatan_1_g_4_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Sesudah makan pagi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_4_c_cebox" id="kesehatan_1_g_4_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Sesudah bangun pagi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_4_d_cebox" id="kesehatan_1_g_4_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Sebelum tidur malam?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_4_e_cebox" id="kesehatan_1_g_4_e_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">f. Sesudah makan siang?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_1_g_4_f_cebox" id="kesehatan_1_g_4_f_cebox" value="1">
            </div>
          </div>

          <br>
          <label>Kondisi dan Riwayat Kesehatan Diri<br>Penggunaan Tembakau</label>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Merokok selama 1 bulan terakhir?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8 col-xs-11" style="padding: 5px 5px 5px 24px">a. Ya, setiap hari?</div>
            <div class="col-md-4 col-xs-1">
              <input type="radio" name="kesehatan_1_g_1_radi5" id="kesehatan_1_g_1_radi5_a" value="0">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8 col-xs-11" style="padding: 5px 5px 5px 24px">b. Ya, kadang-kadang?</div>
            <div class="col-md-4 col-xs-1">
              <input type="radio" name="kesehatan_1_g_1_radi5" id="kesehatan_1_g_1_radi5_b" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8 col-xs-11" style="padding: 5px 5px 5px 24px">c. Tidak, tapi dulu merokok setiap hari?</div>
            <div class="col-md-4 col-xs-1">
              <input type="radio" name="kesehatan_1_g_1_radi5" id="kesehatan_1_g_1_radi5_c" value="2">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8 col-xs-11" style="padding: 5px 5px 5px 24px">d. Tidak, tapi dulu kadang-kadang?</div>
            <div class="col-md-4 col-xs-1">
              <input type="radio" name="kesehatan_1_g_1_radi5" id="kesehatan_1_g_1_radi5_d" value="3">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8 col-xs-11" style="padding: 5px 5px 5px 24px">e. Tidak pernah sama sekali?</div>
            <div class="col-md-4 col-xs-1">
              <input type="radio" name="kesehatan_1_g_1_radi5" id="kesehatan_1_g_1_radi5_e" value="4">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8" style="padding: 5px 5px 5px 24px">Umur berapa . . .</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_1_g_1_text" id="kesehatan_1_g_1_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8" style="padding: 5px">2. Mulai merokok setiap hari?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_1_g_2_text" id="kesehatan_1_g_2_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8" style="padding: 5px">3. Pertama kali merokok ?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_1_g_3_text" id="kesehatan_1_g_3_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">4. Untuk jawaban "Ya" di pertanyaan no. 1,</div>
            <div class="col-md-8" style="padding: 5px 5px 5px 22px">Jumlah batang rokok dikonsumsi per hari ?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Batang" class="form-control" name="kesehatan_1_g_4_text" id="kesehatan_1_g_4_text">
            </div>
          </div>


          </div>
        </div><!-- /.form-box -->
      </div><!-- /.form-box -->

      <div class="col-md-6">
        <div class="box box-warning">
          <div class="box-body">
          <label>Profile Kesehatan Anggota Keluarga (2)</label>
          <br>
          <br>
          <label>Kondisi & Riwayat Kesehatan Diri<br>Tuberkulosis Paru (TB Paru)</label>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa dengan atau tanpa foto dada (rontgen) oleh tenaga kesehatan (dokter/ perawat/ bidan)?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Ya, dalam ≤ 1 bulan terakhir?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_radi4" id="kesehatan_2_g_1_radi4_a" value="0">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Ya, > 1 bulan - 12 bulan?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_radi4" id="kesehatan_2_g_1_radi4_b" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Tidak?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_radi4" id="kesehatan_2_g_1_radi4_c" value="2">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Tidak tahu?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_radi4" id="kesehatan_2_g_1_radi4_d" value="3">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">2. Mengalami gejala penyakit demam, batuk, kesulitan
bernafas dengan atau tanpa nyeri dada?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Ya, dalam ≤ 1 bulan terakhir?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_2_radi4" id="kesehatan_2_g_2_radi4_a" value="0">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Ya, > 1 bulan - 12 bulan?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_2_radi4" id="kesehatan_2_g_2_radi4_b" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Tidak?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_2_radi4" id="kesehatan_2_g_2_radi4_c" value="2">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Tidak tahu?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_2_radi4" id="kesehatan_2_g_2_radi4_d" value="3">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Jika ya, kesulitan yang dialami?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Napas cepat?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_3_a_cebox" id="kesehatan_2_g_3_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Napas cuping hidung?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_3_b_cebox" id="kesehatan_2_g_3_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Tarikan dinding dada bawah ke dalam?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_3_c_cebox" id="kesehatan_2_g_3_c_cebox" value="1">
            </div>
          </div>
          <br>
          <label>Penyakit Ginjal, untuk berumur ≥ 15 tahun</label>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa menderita penyakit gagal ginjal kronis (min.
sakit selama 3 bulan berturut-turut) oleh dokter?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio"  name="kesehatan_2_g_1_radio" id="kesehatan_2_g_1_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio"  name="kesehatan_2_g_1_radio" id="kesehatan_2_g_1_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">2. Pernah didiagnosa mengalami penyakit batu ginjal oleh dokter?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_2_g_2_radio" id="kesehatan_2_g_2_radio_ya" value="1">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_2_g_2_radio" id="kesehatan_2_g_2_radio_tidak" value="1">
            </div>
          </div>

          <br>
          <label>Tuberkulosis Paru (TB Paru)</label>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Akhir-akhir ini batuk?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Ya, < 2 minggu terakhir?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_tb_radi3" id="kesehatan_2_g_1_tb_radi3_a" value="0">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Ya, ≥ 2 minggu?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_tb_radi3" id="kesehatan_2_g_1_tb_radi3_b" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Tidak?</div>
            <div class="col-xs-1">
              <input type="radio" name="kesehatan_2_g_1_tb_radi3" id="kesehatan_2_g_1_tb_radi3_c" value="2">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">2. Jika iya, batuk disertai gejala?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Dahak?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_a_cebox" id="kesehatan_2_g_2_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Darah/ Dahak campur darah?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_b_cebox" id="kesehatan_2_g_2_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Demam?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_c_cebox" id="kesehatan_2_g_2_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Nyeri Dada?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_d_cebox" id="kesehatan_2_g_2_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Sesak Nafas?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_e_cebox" id="kesehatan_2_g_2_e_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">f. Berkeringat malam hari tanpa kegiatan
fisik?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_f_cebox" id="kesehatan_2_g_2_f_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">g. Nafsu Makan menurun?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_g_cebox" id="kesehatan_2_g_2_g_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">h. Berat badan menurun/ sulit bertambah?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_2_h_cebox" id="kesehatan_2_g_2_h_cebox" value="1">
            </div>
          </div>


          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Perlu didiagnosa TB Paru oleh tenaga kesehatan?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Ya, dalam ≤ 1 tahun terakhir?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_3_a_tb_cebox" id="kesehatan_2_g_3_a_tb_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Ya, > 1 tahun?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_3_b_tb_cebox" id="kesehatan_2_g_3_b_tb_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Tidak?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_3_c_tb_cebox" id="kesehatan_2_g_3_c_tb_cebox" value="1">
            </div>
          </div>


          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">4. Pemeriksaan yang digunakan untuk
menegakkan diagnosa TB?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Pemeriksaan dahak?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_4_a_cebox" id="kesehatan_2_g_4_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Pemeriksaan foto dada (Rontgen)?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_2_g_4_b_cebox" id="kesehatan_2_g_4_b_cebox" value="1">
            </div>
          </div>

          </div>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->
  </div>
</div>

<div class="row" style="margin: 0">
  <div class="col-md-12">

    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
          <label>Profile Kesehatan Anggota Keluarga (3)</label>
          <br><br>
          <label>Kondisi & Riwayat Kesehatan Diri<br>Kanker</label>
          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa menderita penyakit kanker oleh dokter ?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_1_kk_radio" id="kesehatan_3_g_1_kk_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio"  name="kesehatan_3_g_1_kk_radio" id="kesehatan_3_g_1_kk_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8" style="padding: 5px">2. Didiagnosa kanker pertama kali pada tahun?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_3_g_2_kk_text" id="kesehatan_3_g_2_kk_text" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Jenis kanker?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Kanker leher rahim (cervix uteri) ?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_a_cebox" id="kesehatan_3_g_3_kk_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Kanker Payudara?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_b_cebox" id="kesehatan_3_g_3_kk_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Kanker prostat?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_c_cebox" id="kesehatan_3_g_3_kk_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Kanker kolorektal/ usus besar?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_d_cebox" id="kesehatan_3_g_3_kk_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Kanker paru dan bronkus?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_e_cebox" id="kesehatan_3_g_3_kk_e_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">f. Kanker nasofaring?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_f_cebox" id="kesehatan_3_g_3_kk_f_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">g. Kanker getah bening?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_kk_g_cebox" id="kesehatan_3_g_3_kk_g_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-xs-6" style="padding: 5px 5px 5px 24px">h. Lainnya. Jenis :</div>
            <div class="col-xs-6">
              <input type="text" name="kesehatan_3_g_3_kk_h_text" id="kesehatan_3_g_3_kk_h_text" placeholder="Jenis Kanker" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">4. Sudah pernah test IVA (Inspeksi Visual dengan Asam Asetat)?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_4_radio" id="kesehatan_3_g_4_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_4_radio" id="kesehatan_3_g_4_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">5. Pengobatan kanker yang telah dijalani?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Pembedahan/ operasi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_5_kk_a_cebox" id="kesehatan_3_g_5_kk_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Radiasi/ penyinaran?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_5_kk_b_cebox" id="kesehatan_3_g_5_kk_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Kemoterapi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_5_kk_c_cebox" id="kesehatan_3_g_5_kk_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-xs-6" style="padding: 5px 5px 5px 24px">d. Lainnya. Jenis :</div>
            <div class="col-xs-6">
              <input type="text" name="kesehatan_3_g_d_text" id="kesehatan_3_g_d_text" placeholder="Jenis Pengobatan" class="form-control">
            </div>
          </div>


          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">6. Sudah pernah test pap smear?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_6_radio" id="kesehatan_3_g_6_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_6_radio" id="kesehatan_3_g_6_radio_tidak" value="1">
            </div>
          </div>
          <br>
          <label>Asma/ Mengi/ Bengek dan Penyakit Paru Obstruktif Kronik (PPOK)</label>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah mengalami gejala sesak napas?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_1_radio" id="kesehatan_3_g_1_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_1_radio" id="kesehatan_3_g_1_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">2. Gejala sesak napas terjadi pada kondisi?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Terpapar udara dingin?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_a_cebox" id="kesehatan_3_g_2_sn_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Debu?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_b_cebox" id="kesehatan_3_g_2_sn_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Asap rokok?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_c_cebox" id="kesehatan_3_g_2_sn_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Stress?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_d_cebox" id="kesehatan_3_g_2_sn_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Flu atau infeksi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_e_cebox" id="kesehatan_3_g_2_sn_e_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">f. Kelelahan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_f_cebox" id="kesehatan_3_g_2_sn_f_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">g. Alergi obat?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_g_cebox" id="kesehatan_3_g_2_sn_g_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">h. Alergi makanan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_2_sn_h_cebox" id="kesehatan_3_g_2_sn_h_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Gejala sesak napas disertai kondisi?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Mengi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_mg_a_cebox" id="kesehatan_3_g_3_mg_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Sesak napas berkurang atau menghilang dengan pengobatan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_mg_b_cebox" id="kesehatan_3_g_3_mg_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Sesak napas berkurang atau menghilang tanpa pengobatan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_mg_c_cebox" id="kesehatan_3_g_3_mg_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Sesak napas lebih berat dirasakan pada malam hari atau menjelang pagi?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_3_g_3_mg_d_cebox" id="kesehatan_3_g_3_mg_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-8" style="padding: 5px">4. Umur berapa mulai merasakan keluhan
sesak pertama kali</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_3_g_4_mg_d_text" id="kesehatan_3_g_4_mg_d_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-12" style="padding: 5px">5. Sesak napas pernah kambuh dalam 12 bulan terakhir?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_5_radio" id="kesehatan_3_g_5_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_3_g_5_radio" id="kesehatan_3_g_5_radio_tidak" value="1">
            </div>
          </div>

          </div>
        </div><!-- /.form-box -->
      </div><!-- /.form-box -->

      <div class="col-md-6">
        <div class="box box-warning">
          <div class="box-body">
          <label>Profile Kesehatan Anggota Keluarga (4)</label>
          <br><br>
          <label>Kondisi & Riwayat Kesehatan Diri<br>Kencing Manis (Diabetes Melitus), untuk berumur ≥ 15 tahun</label>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa menderita kencing manis oleh dokter?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_1_radio" id="kesehatan_4_g_1_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_1_radio" id="kesehatan_4_g_1_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">2. Hal-hal untuk mengendalikan penyakit?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Diet ?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_2_p_a_cebox" id="kesehatan_4_g_2_p_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Olah raga?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_2_p_b_cebox" id="kesehatan_4_g_2_p_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Minum obat anti diabetik?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_2_p_c_cebox" id="kesehatan_4_g_2_p_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Injeksi insulin?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_2_p_d_cebox" id="kesehatan_4_g_2_p_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Gejala dialami dalam 1 bulan terakhir?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Sering lapar ?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_p_a_cebox" id="kesehatan_4_g_3_p_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Sering haus?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_p_b_cebox" id="kesehatan_4_g_3_p_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Sering buang air kecil & jumlah banyak?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_p_c_cebox" id="kesehatan_4_g_3_p_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Berat badan turun?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_p_d_cebox" id="kesehatan_4_g_3_p_d_cebox" value="1">
            </div>
          </div>
          <br><br>
          <label>Hipertensi/ Tekanan Darah Tinggi, untuk berumur ≥ 15 tahun</label>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa menderita hipertensi/ penyakit tekanan
darah tinggi oleh tenaga kesehatan (dokter/ perawat/ bidan)?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio"  name="kesehatan_4_g_1_hp_radio" id="kesehatan_4_g_1_hp_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio"  name="kesehatan_4_g_1_hp_radio" id="kesehatan_4_g_1_hp_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-8" style="padding: 5px">2. Tahun berapa didiagnosa pertama kali?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_4_g_2_hp_text" id="kesehatan_4_g_2_hp_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Sedang minum obat medis untuk tekanan darah tinggi?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_3_hp_radio" id="kesehatan_4_g_3_hp_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_3_hp_radio" id="kesehatan_4_g_3_hp_radio_tidak" value="1">
            </div>
          </div>

          <br><br>
          <label>Penyakit Jantung Koroner, untuk berumur ≥ 15 tahun</label>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa menderita penyakit jantung koroner (Angina Pektoris dan/atau Infark Miokard) oleh tenaga kesehatan (dokter/ perawat/ bidan)?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_1_jk_radio" id="kesehatan_4_g_1_jk_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_1_jk_radio" id="kesehatan_4_g_1_jk_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-8" style="padding: 5px">2. Tahun berapa didiagnosa pertama kali?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control" name="kesehatan_4_g_2_jk_text" id="kesehatan_4_g_2_jk_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Gejala/ riwayat yang pernah dialami?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Nyeri di dalam dada/ rasa tertekan berat/ tidak nyaman di dada ?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_jk_a_cebox" id="kesehatan_4_g_3_jk_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Nyeri/ tidak nyaman di dada bagian tengah/ dada kiri depan/ menjalar ke lengan kiri?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_jk_b_cebox" id="kesehatan_4_g_3_jk_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Nyeri/ tidak nyaman di dada dirasakan waktu  endaki/ naik tangga/ berjalan tergesa-gesa?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_jk_c_cebox" id="kesehatan_4_g_3_jk_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Nyeri/ tidak nyaman di dada hilang ketika menghentikan aktivitas/ istirahat?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_jk_d_cebox" id="kesehatan_4_g_3_jk_d_cebox" value="1">
            </div>
          </div>

          <br><br>
          <label>Stroke, untuk berumur ≥ 15 tahun</label>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">1. Pernah didiagnosa menderita penyakit stroke oleh tenaga kesehatan (dokter/ perawat/ bidan)?</div>
          </div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_1_sk_radio" id="kesehatan_4_g_1_sk_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_4_g_1_sk_radio" id="kesehatan_4_g_1_sk_radio_tidak" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-8" style="padding: 5px">2. Tahun berapa didiagnosa pertama kali?</div>
            <div class="col-md-4">
              <input type="number" placeholder="Tahun" class="form-control"  name="kesehatan_4_g_2_sk_text" id="kesehatan_4_g_2_sk_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. Pernah alami keluhan secara mendadak?</div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">a. Kelumpuhan pada satu sisi tubuh ?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_sk_a_cebox" id="kesehatan_4_g_3_sk_a_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">b. Kesemutan atau baal satu sisi tubuh?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_sk_b_cebox" id="kesehatan_4_g_3_sk_b_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">c. Mulut jadi mencong tanpa kelumpuhan otot mata?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_sk_c_cebox" id="kesehatan_4_g_3_sk_c_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">d. Bicara pelo?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_sk_d_cebox" id="kesehatan_4_g_3_sk_d_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px 5px 5px 24px">e. Sulit bicara/ komunikasi dan/atau tidak mengerti pembicaraan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_4_g_3_sk_e_cebox" id="kesehatan_4_g_3_sk_e_cebox" value="1">
            </div>
          </div>

          </div>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->
  </div>
</div>

<div class="row" style="margin: 0">
  <div class="col-md-12">

    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
          <label>Profile Kesehatan Anggota Keluarga (5)</label>
          <br><br>
          <label>Kesehatan Jiwa Untuk berumur ≥ 15 tahun</label>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">1. Sering menderita sakit kepala?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_1_kk_cebox" id="kesehatan_5_g_1_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">2. Tidak nafsu makan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_2_kk_cebox" id="kesehatan_5_g_2_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">3. Sulit tidur?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_3_kk_cebox" id="kesehatan_5_g_3_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">4. Mudah takut?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_4_kk_cebox" id="kesehatan_5_g_4_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">5. Merasa tegang, cemas atau kuatir?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_5_kk_cebox" id="kesehatan_5_g_5_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">6. Tangan gemetar?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_6_kk_cebox" id="kesehatan_5_g_6_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">7. Percernaan terganggu/ buruk?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_7_kk_cebox" id="kesehatan_5_g_7_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">8. Sulit berpikir jernih?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_8_kk_cebox" id="kesehatan_5_g_8_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">9. Merasa tidak bahagia?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_9_kk_cebox" id="kesehatan_5_g_9_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">10. Menangis lebih sering?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_10_kk_cebox" id="kesehatan_5_g_10_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">11. Merasa sulit menikmati kegiatan seharihari?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_11_kk_cebox" id="kesehatan_5_g_11_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">12. Sulit mengambil keputusan?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_12_kk_cebox" id="kesehatan_5_g_12_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">13. Pekerjaan sehari-hari terganggu?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_13_kk_cebox" id="kesehatan_5_g_13_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">14. Tidak mampu melakukan hal-hal yang bermanfaat dalam hidup?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_14_kk_cebox" id="kesehatan_5_g_14_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">15. Kehilangan minat dalam berbagai hal?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_15_kk_cebox" id="kesehatan_5_g_15_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">16. Sering menderita sakit kepala?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_16_kk_cebox" id="kesehatan_5_g_16_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">17. Merasa tidak berharga?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_17_kk_cebox" id="kesehatan_5_g_17_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">18. Mempunya pikiran untuk mengakhiri hidup?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_18_kk_cebox" id="kesehatan_5_g_18_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">19. Merasa lelah sepanjang waktu?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_19_kk_cebox" id="kesehatan_5_g_19_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">20. Mengalami rasa tidak enak di perut?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_20_kk_cebox" id="kesehatan_5_g_20_kk_cebox" value="1">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-11" style="padding: 5px">21. Sering menderita sakit kepala?</div>
            <div class="col-xs-1">
              <input type="checkbox" name="kesehatan_5_g_21_kk_cebox" id="kesehatan_5_g_21_kk_cebox" value="1">
            </div>
          </div>

          <div class="col-md-12" style="padding: 5px">21. Untuk semua keluhan 1 s/d 20, pernah melakukan pengobatan ke fasilitas kesehatan/ tenaga kesehatan?</div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_5_g_21_radio" id="kesehatan_5_g_21_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_5_g_21_radio" id="kesehatan_5_g_21_radio_tidak" value="1"> 
            </div>
          </div>

          <div class="col-md-12" style="padding: 5px">22. Jika pernah melakukan pengobatan ke fasilitas kesehatan/ tenaga kesehatan, apakah dalam 2 minggu terakhir?</div>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">a. Ya?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_5_g_22_radio" id="kesehatan_5_g_22_radio_ya" value="0">
            </div>
            <div class="col-xs-3" style="padding: 5px 5px 5px 24px">b. Tidak?</div>
            <div class="col-xs-3">
              <input type="radio" name="kesehatan_5_g_22_radio" id="kesehatan_5_g_22_radio_tidak" value="1">
            </div>
          </div>

          </div>
        </div><!-- /.form-box -->
      </div><!-- /.form-box -->

      <div class="col-md-6">
        <div class="box box-warning">
          <div class="box-body">
          <label>Profile Kesehatan Anggota Keluarga (6)</label>
          <div class="row" style="margin: 5px;border-bottom:1px solid #EEEEEE;">
            <div class="col-md-7" style="padding: 5px">1. Status Imunisasi ?</div>
            <div class="col-md-2">
              <input type="radio" name="kesehatan_6_g_1_radio" id="kesehatan_6_g_1_radio_ya" value="0"> Ya
            </div>
            <div class="col-md-3">
              <input type="radio" name="kesehatan_6_g_1_radio" id="kesehatan_6_g_1_radio_tidak" value="1"> Tidak
            </div>
          </div>

          <div class="row" style="margin: 5px;">
            <div class="col-md-12" style="padding: 5px">2. Aktivitas?</div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">Olahraga</div>
            <div class="col-md-6">
              <input type="text" placeholder="Olahraga" class="form-control" name="kesehatan_6_g_2_ol_text" id="kesehatan_6_g_2_ol_text">
            </div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">Tidur</div>
            <div class="col-md-6">
              <input type="text" placeholder="Tidur" class="form-control" name="kesehatan_6_g_2_td_text" id="kesehatan_6_g_2_td_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">3. TTV (Tanda-Tanda Vital)?</div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">TD : Tekanan Darah</div>
            <div class="col-md-6">
              <input type="text" placeholder="Tekanan Darah" class="form-control" name="kesehatan_6_g_3_td_text" id="kesehatan_6_g_3_td_text">
            </div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">N : Nadi</div>
            <div class="col-md-6">
              <input type="text" placeholder="Nadi" class="form-control" name="kesehatan_6_g_3_tn_text" id="kesehatan_6_g_3_tn_text">
            </div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">P: Pernapasan</div>
            <div class="col-md-6">
              <input type="text" placeholder="Pernapasan" class="form-control" name="kesehatan_6_g_3_p_text" id="kesehatan_6_g_3_p_text">
            </div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">S: Suhu</div>
            <div class="col-md-6">
              <input type="number" placeholder="Suhu" class="form-control" name="kesehatan_6_g_3_s_text" id="kesehatan_6_g_3_s_text">
            </div>
          </div>


          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">4. Antropometri?</div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">TB: Tinggi Badan</div>
            <div class="col-md-6">
              <input type="number" placeholder="Tinggi Badan" class="form-control" name="kesehatan_6_g_4_at_text" id="kesehatan_6_g_4_at_text">
            </div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">BB: Berat Badan</div>
            <div class="col-md-6">
              <input type="number" placeholder="Berat Badan" class="form-control" name="kesehatan_6_g_4_bb_text" id="kesehatan_6_g_4_bb_text">
            </div>
            <div class="col-md-6" style="padding: 5px 5px 5px 24px">Status Gizi</div>
            <div class="col-md-6">
              <input type="text" placeholder="Status Gizi" class="form-control" name="kesehatan_6_g_4_sg_text" id="kesehatan_6_g_4_sg_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-6" style="padding: 5px">5. Conjunctiva ?</div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_6_g_5_radio" id="kesehatan_6_g_5_radio_ya" value="0"> Pucat
            </div>
            <div class="col-md-3 col-xs-6">
              <input type="radio" name="kesehatan_6_g_5_radio" id="kesehatan_6_g_5_radio_tidak" value="1"> Normal
            </div>
          </div>


          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">6. Riwayat Kesehatan?</div>
            <div class="col-md-12">
              <input type="text" placeholder="Riwayat Kesehatan" class="form-control" name="kesehatan_6_g_6_text" id="kesehatan_6_g_6_text">
            </div>
          </div>

          <div class="row" style="margin: 5px;border-top:1px solid #EEEEEE;">
            <div class="col-md-12" style="padding: 5px">7. Analisa Masalah Kesehatan?</div>
            <div class="col-md-12">
              <input type="text" placeholder="Analisa Masalah Kesehatan" class="form-control" name="kesehatan_6_g_7_text" id="kesehatan_6_g_7_text">
            </div>
          </div>

          </div>

      </div><!-- /.form-box -->
    <div class="box-footer" style="text-align: right">
        <button type="button" id="btn-up2" class="btn btn-warning"><i class='fa  fa-arrow-circle-up'></i> &nbsp;Back To Top</button>
    </div>
    </div><!-- /.register-box -->
  </div>
</div></form>        
