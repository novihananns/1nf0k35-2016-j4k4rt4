
<script>
    $(function () { 
      $('#btn-back-peserta').click(function(){
            $("#tambahtjqxgrid_peserta").hide();
            $("#jqxgrid_peserta").show();
            $("#btn_add_peserta").show();
            $("#btn-refresh-datapeserta").show();
            $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
      });
      function simpandatapeserta() {
        var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();
        data.append('nik', $("[name='nik']").val());
        data.append('bpjs', $("[name='bpjs']").val());
        data.append('nama', $("[name='nama']").val());
        data.append('jenis_peserta', $("[name='jenis_peserta']").val());
        data.append('tgl_lahir', $("[name='tgl_lahir']").val());
        data.append('id_pilihan_kelamin', $("[name='id_pilihan_kelamin']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."eform/kegiatankelompok/{action}_peserta/{kode}"?>',
            data : data,
            success : function(response){
              res = response.split("|")
              if (res[0]=='Error') {
                alert(res[1]);
              }else{
                  $("#tambahtjqxgrid_peserta").hide();
                  $("#btn_add_peserta").show();
                  $("#btn-refresh-datapeserta").show();
                  $("#jqxgrid_peserta").show();
                  $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
              }
            }
        });

        return false;
      }
      $('#btn-save-add-peserta').click(function(){
            simpandatapeserta();
        });
        
      });

      $("input[name='nik']").keyup(function(){
        var nik = $("input[name='nik']").val();
        if(nik.length==16){
          $.get("<?php echo base_url()?>eform/kegiatankelompok/bpjs_search/nik/"+nik,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Anggota keluarga terdaftar sebagai peserta BPJS. \nGunakan data?")){
                  $("#bpjs").val(res.response.noKartu);
                  $("#nama").val(res.response.nama);
                  $("#jenis_peserta").val(res.response.jnsPeserta.nama);
                  var tgl = res.response.tglLahir.split("-");
                  var date = new Date(tgl[2], (tgl[1]-1), tgl[0]);
                  $("#tgl_lahir").jqxDateTimeInput('setDate', date);

                  if(res.response.noHP!=" " && res.response.noHP!="") $("#no_hp").val(res.response.noHP);
                  if(res.response.sex=="P"){
                    $("#id_pilihan_kelamin").val(6);
                  }else{
                    $("#id_pilihan_kelamin").val(5);
                  }
                  
                }
              }else{
                alert("Peserta tidak terdaftar sebagai angota BPJS");
                bersih();
              }
          },"json");
        }
      });

      $("#bpjs").keyup(function(){
        var bpjs = $("#bpjs").val();
        if(bpjs.length==13){
          $.get("<?php echo base_url()?>eform/kegiatankelompok/bpjs_search/bpjs/"+bpjs,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Nomor BPJS terdaftar, gunakan data?")){
                  if(res.response.noKTP!=null) $("input[name='nik']").val(res.response.noKTP);
                  $("#nama").val(res.response.nama);
                  $("#jenis_peserta").val(res.response.jnsPeserta.nama);
                  var tgl = res.response.tglLahir.split("-");
                  var date = new Date(tgl[2], (tgl[1]-1), tgl[0]);
                  $("#tgl_lahir").jqxDateTimeInput('setDate', date);

                  if(res.response.noHP!=" " && res.response.noHP!="") $("#no_hp").val(res.response.noHP);
                  if(res.response.sex=="P"){
                    $("#id_pilihan_kelamin").val(6);
                  }else{
                    $("#id_pilihan_kelamin").val(5);
                  }
                  
                }
              }else{
                alert("Peserta tidak terdaftar sebagai angota BPJS");
                bersih();
              }
          },"json");
        }
      });
      function bersih(){
            $("#nama").val('');
            $("#bpjs").val('');
            $("#nik").val('');
      }
      $("#tgl_lahir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});
  
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
<form action="<?php echo base_url()?>eform/kegiatankelompok/{action}_peserta/{kode}" method="post">
<div class="row" style="margin: 0">
  <div class="col-md-12">
    <div class="box-footer">
      <div class="col-md-6">
        <h4><i class="icon fa fa-group" ></i> Tambah Anggota Keluarga</h4>
      </div>
    </div>
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
            <div class="col-md-4" style="padding: 5px">Nama</div>
            <div class="col-md-8">
              <input type="text" name="jenis_peserta" id="jenis_peserta" placeholder="Jenis Peserta" value="<?php 
                if(set_value('jenis_peserta')=="" && isset($jenis_peserta)){
                  echo $jenis_peserta;
                }else{
                  echo  set_value('jenis_peserta');
                }
                ?>" class="form-control">
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
            <div class="col-md-12" style="text-align: right">
                <!-- <button type="button" id="btn-back-peserta" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button> -->
                <button type="button" id="btn-save-add-peserta" class="btn btn-success"><i class='fa fa-save'></i> &nbsp; Pilih</button>
            </div>
          </div>
        </div><!-- /.form-box -->
      </div><!-- /.form-box -->

    </div><!-- /.register-box -->
  </div>
</div>

</form>        