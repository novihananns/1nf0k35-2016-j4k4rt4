
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
        data.append('nmProvider', $("[name='nmProvider']").val());
        data.append('jnsKelas', $("[name='jnsKelas']").val());
        data.append('noHP', $("[name='noHP']").val());

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
                  // $("#tambahtjqxgrid_peserta").hide();
                  // $("#btn_add_peserta").show();
                  // $("#btn-refresh-datapeserta").show();
                  // $("#jqxgrid_peserta").show();
                  // $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
                  alert("Data Bershasil disimpan");
                  bersih();
              }
            }
        });

        return false;
      }
      $('#btn-save-add-peserta').click(function(){
            simpandatapeserta();
        });
        
      });

      // $("input[name='nik']").keyup(function(){
        $("#buttonniksearch").click(function(){
        var nik = $("input[name='nik']").val();
        if(nik.length==16){
          $.get("<?php echo base_url()?>eform/kegiatankelompok/bpjs_search/nik/"+nik,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Nomor terdaftar sebagai peserta \nBPJS "+res.response.kdProviderPst.nmProvider+", \nGunakan data?")){
                  $("#bpjs").val(res.response.noKartu);
                  $("#nama").val(res.response.nama);
                  $("#jenis_peserta").val(res.response.jnsPeserta.nama);
                  $("#tgl_lahir").val(res.response.tglLahir);
                  $("#id_pilihan_kelamin").val(res.response.sex);
                  $("#nmProvider").val(res.response.kdProviderPst.nmProvider);
                  $("#jnsKelas").val(res.response.jnsKelas.nama);
                  $("#noHP").val(res.response.noHP);
                }
              }else{
                alert("Peserta tidak terdaftar sebagai angota BPJS");
                bersih();
              }
          },"json");
        }else{
          alert("Pastikan NIK Berjumalh 16 digit");
        }
      });

      // $("#bpjs").keyup(function(){
        $("#buttonbpjssearch").click(function(){
        var bpjs = $("#bpjs").val();
        if(bpjs.length==13){
          $.get("<?php echo base_url()?>eform/kegiatankelompok/bpjs_search/bpjs/"+bpjs,function(res){
              if(res.metaData.code=="200"){
                if(confirm("Nomor terdaftar sebagai peserta \nBPJS "+res.response.kdProviderPst.nmProvider+", \nGunakan data?")){
                  if(res.response.noKTP!=null) $("input[name='nik']").val(res.response.noKTP);
                  $("#nama").val(res.response.nama);
                  $("#jenis_peserta").val(res.response.jnsPeserta.nama);
                  $("#tgl_lahir").val(res.response.tglLahir);
                  $("#id_pilihan_kelamin").val(res.response.sex);
                  $("#nmProvider").val(res.response.kdProviderPst.nmProvider);
                  $("#jnsKelas").val(res.response.jnsKelas.nama);
                  $("#noHP").val(res.response.noHP);
                }
              }else{
                alert("Peserta tidak terdaftar sebagai angota BPJS");
                bersih();
              }
          },"json");
        }else{
          alert("Pastikan Nomor BPJS Berjumalh 13 digit");
        }
      });
      function bersih(){
            $("#nama").val('');
            $("#bpjs").val('');
            $("#nik").val('');
            $("#tgl_lahir").val('');
            $("#jenis_peserta").val('');
            $("#id_pilihan_kelamin").val('');
            $("#nmProvider").val('');
            $("#jnsKelas").val('');
            $("#noHP").val('');
      }
</script>

<form action="<?php echo base_url()?>eform/kegiatankelompok/{action}_peserta/{kode}" method="post">
<div class="row" style="margin: 0">
  <div class="col-md-12">
  <br>
    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">NIK</div>
            <div class="col-md-8">
                <div class="row">
                  <div class="col-md-10">
                    <input style="background:#dde4ff" type="text" name="nik" id="nik" placeholder="Nomor Induk Keluarga" value="<?php 
                        if(set_value('nik')=="" && isset($nik)){
                          echo $nik;
                        }else{
                          echo  set_value('nik');
                        }
                        ?>" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-default" id="buttonniksearch"><span class="glyphicon glyphicon-search"></span></button>
                  </div>
                </div>
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor BPJS</div>
            <div class="col-md-8">
                <div class="row">
                  <div class="col-md-10">
                    <input style="background:#dde4ff" type="text" name="bpjs" id="bpjs" placeholder="Nomor BPJS" value="<?php 
                      if(set_value('bpjs')=="" && isset($bpjs)){
                        echo $bpjs;
                      }else{
                        echo  set_value('bpjs');
                      }
                      ?>" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-default" id="buttonbpjssearch"><span class="glyphicon glyphicon-search"></span></button>
                  </div>
                </div>
            </div>
            <div class="col-md-8">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama</div>
            <div class="col-md-8">
              <input type="text" name="nama" id="nama" placeholder="Nama" readonly class="form-control">
            </div>
          </div>

          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Tanggal Lahir</div>
            <div class="col-md-8">
              <input type="text" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir" readonly class="form-control">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jenis Peserta</div>
            <div class="col-md-8">
              <input type="text" name="jenis_peserta" id="jenis_peserta" placeholder="Jenis Peserta" readonly class="form-control">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Kelamin</div>
            <div class="col-md-8">
              <input type="text" name="id_pilihan_kelamin" id="id_pilihan_kelamin" placeholder="Jenis Kelamin" readonly class="form-control">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nama Provider</div>
            <div class="col-md-8">
              <input type="text" name="nmProvider" id="nmProvider" placeholder="Nama Provider" readonly class="form-control">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Jenis Kelas</div>
            <div class="col-md-8">
              <input type="text" name="jnsKelas" id="jnsKelas" placeholder="Jenis Kelas" readonly class="form-control">
            </div>
          </div>
          <div class="row" style="margin: 5px">
            <div class="col-md-4" style="padding: 5px">Nomor HP</div>
            <div class="col-md-8">
              <input type="text" name="noHP" id="noHP" placeholder="Nomor HP" readonly class="form-control">
            </div>
          </div>
            <div class="col-md-12" style="text-align: right">
                <button type="button" id="btn-save-add-peserta" class="btn btn-success"><i class='fa fa-save'></i> &nbsp; Pilih</button>
            </div>
          </div>
        </div><!-- /.form-box -->
      </div><!-- /.form-box -->

    </div><!-- /.register-box -->
  </div>
</div>

</form>        

<style type="text/css">
  
#custom-search-input {
        margin:0;
        margin-top: 10px;
        padding: 0;
    }
 
    #custom-search-input .search-query {
        padding-right: 3px;
        padding-right: 4px \9;
        padding-left: 3px;
        padding-left: 4px \9;
        /* IE7-8 doesn't have border-radius, so don't indent the padding */
 
        margin-bottom: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
 
    #custom-search-input button {
        border: 0;
        background: none;
        /** belows styles are working good */
        padding: 2px 5px;
        margin-top: 2px;
        position: relative;
        left: -28px;
        /* IE7-8 doesn't have border-radius, so don't indent the padding */
        margin-bottom: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        color:#D9230F;
    }
 
    .search-query:focus + button {
        z-index: 3;   
    }

</style>