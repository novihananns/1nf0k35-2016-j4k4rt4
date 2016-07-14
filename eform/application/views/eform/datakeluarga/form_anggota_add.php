
<script>
  	$(function () { 
     	$('#btn-up,#btn-up2').click(function(){
        $.get('<?php echo base_url()?>eform/data_kepala_keluarga/anggota/{id_data_keluarga}', function (data) {
            $('#content2').html(data);
        });
	    });
      function simpandata() {
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();
        data.append('id_data_keluarga', $("[name='id_data_keluarga']").val());
        data.append('nik', $("[name='nik']").val());
        data.append('bpjs', $("[name='bpjs']").val());
        data.append('nama', $("[name='nama']").val());
        data.append('tmpt_lahir', $("[name='tmpt_lahir']").val());
        data.append('tgl_lahir', $("[name='tgl_lahir']").val());
        data.append('id_pilihan_hubungan', $("[name='id_pilihan_hubungan']").val());
        data.append('id_pilihan_kelamin', $("[name='id_pilihan_kelamin']").val());
        data.append('id_pilihan_agama', $("[name='id_pilihan_agama']").val());
        data.append('id_pilihan_pendidikan', $("[name='id_pilihan_pendidikan']").val());
        data.append('id_pilihan_pekerjaan', $("[name='id_pilihan_pekerjaan']").val());
        data.append('id_pilihan_kawin', $("[name='id_pilihan_kawin']").val());
        data.append('id_pilihan_jkn', $("[name='id_pilihan_jkn']").val());
        data.append('suku', $("[name='suku']").val());
        data.append('no_hp', $("[name='no_hp']").val());
        data.append('providerPeserta', $("[name='providerPeserta']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."eform/data_kepala_keluarga/anggota_{action}/{id_data_keluarga}"?>',
            data : data,
            success : function(response){
                $('#content2').html(response);
            }
        });

        return false;
      }
      $('#btn-save-add').click(function(){
        $.get("<?php echo base_url()?>eform/data_kepala_keluarga/cekkonek/",function(response){
          if(response=='ready'){
              if (($("#bpjs").val() != '') && $("#bpjs").val().length==13) {
                if (confirm("Daftarkan Home Visit di "+$("#providerPeserta").val()+"? ")) {
                    $.get("<?php echo base_url()?>eform/data_kepala_keluarga/simpanbpjs/"+$("#bpjs").val(),function(res){
                      if (res=='bpjserror') {
                        if (confirm("Tidak bisa terhubung ke server BPJS! \nApakah tetap ingin disimpan ?")) {
                          simpandata();
                        }
                      }else if (res=='dataada') {
                        if (confirm("Peserta sudah terdaftar di BPJS hari ini. \nApakah data ingin tetap disimpan ?")) {
                          simpandata();
                        }
                      }else if (res=='datatidakada') {
                        if (confirm("Peserta Tidak Terdaftar dibpjs. \nApakah data ingin tetap disimpan ?")) {
                          simpandata();
                        }
                      }else{
                        simpandata();
                      }
                    });
                  }else{
                      simpandata();
                  }
              }else{
                simpandata();
              }
          }else{
            simpandata();
          }
        });
        
      });

      $("input[name='nik']").keyup(function(){
        var nik = $("input[name='nik']").val();
        if(nik.length==16){
          $.get("<?php echo base_url()?>eform/data_kepala_keluarga/bpjs_search/nik/"+nik,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Anggota keluarga terdaftar sebagai peserta BPJS "+res.response.kdProviderPst.nmProvider+". \nStatus "+res.response.ketAktif+", Tunggakan sebesar Rp. "+res.response.tunggakan+"\nGunakan data?")){
                  $("#bpjs").val(res.response.noKartu);
                  $("#nama").val(res.response.nama);
                  $("#providerPeserta").val(res.response.kdProviderPst.nmProvider);
                  var tgl = res.response.tglLahir.split("-");
                  var date = new Date(tgl[2], (tgl[1]-1), tgl[0]);
                  $("#tgl_lahir").jqxDateTimeInput('setDate', date);

                  if(res.response.noHP!=" " && res.response.noHP!="") $("#no_hp").val(res.response.noHP);
                  if(res.response.sex=="P"){
                    $("#id_pilihan_kelamin").val(6);
                  }else{
                    $("#id_pilihan_kelamin").val(5);
                  }
                  $("#tmpt_lahir").focus();
                }
              }
          },"json");
        }
      });

      $("#bpjs").keyup(function(){
        var bpjs = $("#bpjs").val();
        if(bpjs.length==13){
          $.get("<?php echo base_url()?>eform/data_kepala_keluarga/bpjs_search/bpjs/"+bpjs,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Nomor BPJS terdaftar di "+res.response.kdProviderPst.nmProvider+", gunakan data?")){
                  if(res.response.noKTP!=null) $("input[name='nik']").val(res.response.noKTP);
                  $("#nama").val(res.response.nama);
                  $("#providerPeserta").val(res.response.kdProviderPst.nmProvider);

                  var tgl = res.response.tglLahir.split("-");
                  var date = new Date(tgl[2], (tgl[1]-1), tgl[0]);
                  $("#tgl_lahir").jqxDateTimeInput('setDate', date);

                  if(res.response.noHP!=" " && res.response.noHP!="") $("#no_hp").val(res.response.noHP);
                  if(res.response.sex=="P"){
                    $("#id_pilihan_kelamin").val(6);
                  }else{
                    $("#id_pilihan_kelamin").val(5);
                  }
                  $("#tmpt_lahir").focus();
                }
              }
          },"json");
        }
      });

      $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});
	});
</script>

<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($alert_form!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $alert_form; ?>
</div>
<?php } ?>
<form action="<?php echo base_url()?>eform/data_kepala_keluarga/{action}/{id_data_keluarga}" method="post">
<div class="row" style="margin: 0">
  <div class="col-md-12">
    <div class="box-footer">
      <div class="col-md-6">
        <h4><i class="icon fa fa-group" ></i> Tambah Anggota Keluarga</h4>
      </div>
      <div class="col-md-6" style="text-align: right">
        <button type="button" id="btn-save-add" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-up" class="btn btn-success"><i class='fa  fa-arrow-circle-o-up'></i> &nbsp;Kembali</button>
     </div>
    </div>
    <input type="hidden" name="id_data_keluarga" value="{id_data_keluarga}">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
          <label>Data Anggota Keluarga</label>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">NIK</div>
            <div class="col-md-8">
              <input style="background:#dde4ff" type="text" name="nik" id="nik" placeholder="Nomor Induk Keluarga" value="<?php 
                if(set_value('nik')=="" && isset($nik)){
                  echo $nik;
                }else{
                  echo  set_value('nik');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor BPJS</div>
            <div class="col-md-8">
              <input type="hidden" name="providerPeserta" id="providerPeserta" value="<?php 
                if(set_value('providerPeserta')=="" && isset($providerPeserta)){
                  echo $providerPeserta;
                }else{
                  echo  set_value('providerPeserta');
                }
                ?>">
              <input style="background:#dde4ff" type="text" name="bpjs" id="bpjs" placeholder="Nomor BPJS" value="<?php 
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
            <div class="col-md-4" style="padding: 5px">Nama</div>
            <div class="col-md-8">
              <input type="text" name="nama" id="nama" placeholder="Nama" value="<?php 
                if(set_value('nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('nama');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tempat Lahir</div>
            <div class="col-md-8">
              <input type="text" name="tmpt_lahir" id="tmpt_lahir" placeholder="Tempat Lahir" value="<?php 
                if(set_value('tmpt_lahir')=="" && isset($tmpt_lahir)){
                  echo $tmpt_lahir;
                }else{
                  echo  set_value('tmpt_lahir');
                }
                ?>" class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tanggal Lahir</div>
            <div class="col-md-8">
              <div id='tgl_lahir' name="tgl_lahir" value="<?php
                if(set_value('tgl_lahir')=="" && isset($tgl_lahir)){
                  $tgl_lahir = strtotime($tgl_lahir);
                }else{
                  $tgl_lahir = strtotime(set_value('tgl_lahir'));
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
              <select  name="id_pilihan_hubungan" id="id_pilihan_hubungan" class="form-control">
                <?php
                if(set_value('id_pilihan_hubungan')=="" && isset($id_pilihan_hubungan)){
                  $pilihan_hubungan = $id_pilihan_hubungan;
                }else{
                  $pilihan_hubungan = set_value('id_pilihan_hubungan');
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
              <select  name="id_pilihan_kelamin" id="id_pilihan_kelamin" class="form-control">
                <?php
                if(set_value('id_pilihan_kelamin')=="" && isset($id_pilihan_kelamin)){
                  $pilihan_kelamin = $id_pilihan_kelamin;
                }else{
                  $pilihan_kelamin = set_value('id_pilihan_kelamin');
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
              <select  name="id_pilihan_agama" id="id_pilihan_agama" class="form-control">
                <?php
                if(set_value('id_pilihan_agama')=="" && isset($id_pilihan_agama)){
                  $pilihan_agama = $id_pilihan_agama;
                }else{
                  $pilihan_agama = set_value('id_pilihan_agama');
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
              <select  name="id_pilihan_pendidikan" id="id_pilihan_pendidikan" class="form-control">
                <?php
                if(set_value('id_pilihan_pendidikan')=="" && isset($id_pilihan_pendidikan)){
                  $pilihan_pendidikan = $id_pilihan_pendidikan;
                }else{
                  $pilihan_pendidikan = set_value('id_pilihan_pendidikan');
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
              <select  name="id_pilihan_pekerjaan" id="id_pilihan_pekerjaan" class="form-control">
                <?php
                if(set_value('id_pilihan_pekerjaan')=="" && isset($id_pilihan_pekerjaan)){
                  $pilihan_pekerjaan = $id_pilihan_pekerjaan;
                }else{
                  $pilihan_pekerjaan = set_value('id_pilihan_pekerjaan');
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
              <select  name="id_pilihan_kawin" id="id_pilihan_kawin" class="form-control">
                <?php
                if(set_value('id_pilihan_kawin')=="" && isset($id_pilihan_kawin)){
                  $pilihan_kawin = $id_pilihan_kawin;
                }else{
                  $pilihan_kawin = set_value('id_pilihan_kawin');
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
              <select  name="id_pilihan_jkn" id="id_pilihan_jkn" class="form-control">
                <?php
                if(set_value('id_pilihan_jkn')=="" && isset($id_pilihan_jkn)){
                  $pilihan_jkn = $id_pilihan_jkn;
                }else{
                  $pilihan_jkn = set_value('id_pilihan_jkn');
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
            <div class="col-md-4" style="padding: 5px">Suku</div>
            <div class="col-md-8">
              <input type="text" name="suku" id="suku" placeholder="Suku" value="<?php 
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
              <input type="text" name="no_hp" id="no_hp" placeholder="Nomor HP" value="<?php 
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

    </div><!-- /.register-box -->
  </div>
</div>

</form>        