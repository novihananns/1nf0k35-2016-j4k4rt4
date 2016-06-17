<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="row">
  <form action="<?php echo base_url()?>eform/kegiatankelompok/add" method="post">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="col-md-4">Tanggal Pelaksanaan</div>
          <div class="col-md-8" style="margin-bottom: 5px">
            <div id='tgl' name="tgl" value="<?php
               if(set_value('tgl')=="" && isset($tgl)){
                  $tgldata= $tgl;
                }else{
                  $tgldata= set_value('tgl');
                }
                echo ($tgldata!="") ? $tgldata : "";
              ?>"></div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-4">Jenis Kelompok</div>
          <div class="col-md-8" style="margin-bottom: 5px">
            <select  name="kode_kelompok" id="kode_kelompok" type="text" class="form-control">
              <?php
              if(set_value('kode_kelompok')=="" && isset($kode_kelompok)){
                $kode_kelompok = $kode_kelompok;
              }else{
                $kode_kelompok = set_value('kode_kelompok');
              }
              foreach($jeniskelompok as $key) : 
                $select = $key->id_mas_club_kelompok == $kode_kelompok ? 'selected' : '' 
              ?>
                <option value="<?php echo $key->id_mas_club_kelompok ?>" <?php echo $select ?>><?php echo $key->value ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="row" id="jenisplor">
          <div class="col-md-4">Club Prolanis</div> 
          <div class="col-md-8" style="margin-bottom: 5px">
            <select name="jenis_kelompok" id="jenis_kelompok" class="form-control" id="kelurahan">
              <option value="">Pilih Club Pronalis</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">Jenis Kegiatan</div>
          <div class="col-md-4" style="margin-bottom: 5px">
              <input type="checkbox" name="edukasi" value="1"> Penyuluhan/Edukasi
          </div>
          <div class="col-md-4" style="margin-bottom: 5px">
              <input type="checkbox" name="senam" value="1"> Senam
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">Materi</div>
          <div class="col-md-8" style="margin-bottom: 5px">
            <input type="text" class="form-control" name="materi" placeholder="Materi" value="<?php 
              if(set_value('materi')=="" && isset($materi)){
                echo $materi;
              }else{
                echo  set_value('materi');
              }
              ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">Pembicara</div>
          <div class="col-md-8" style="margin-bottom: 5px">
            <input type="text" class="form-control" name="pembicara" placeholder="Pembicara" value="<?php 
              if(set_value('pembicara')=="" && isset($pembicara)){
                echo $pembicara;
              }else{
                echo  set_value('pembicara');
              }
              ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">Lokasi</div>
          <div class="col-md-8" style="margin-bottom: 5px">
            <input type="text" class="form-control" name="lokasi" placeholder="Lokasi" value="<?php 
              if(set_value('lokasi')=="" && isset($lokasi)){
                echo $lokasi;
              }else{
                echo  set_value('lokasi');
              }
              ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">Biaya</div>
          <div class="col-md-8" style="margin-bottom: 5px">
            <input type="number" class="form-control" name="biaya" id="biaya" placeholder="Biaya" value="<?php 
              if(set_value('biaya')=="" && isset($biaya)){
                echo $biaya;
              }else{
                echo  set_value('biaya');
              }
              ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">Keterangan</div>
          <div class="col-md-8" style="margin-bottom: 5px">
           <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"><?php 
              if(set_value('keterangan')=="" && isset($keterangan)){
                echo $keterangan;
              }else{
                echo  set_value('keterangan');
              }
              ?></textarea>
          </div>
        </div>
        <div class="box-footer pull-right">
        <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> &nbsp; Simpan & Lanjutkan</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
      </div>  
      </div>
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->
</form>        

<script type="text/javascript">
$(function(){
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>eform/kegiatankelompok";
    });

    $("#menu_kegiatan_kelompok").addClass("active");
    $("#menu_eform_kegiatankelompok").addClass("active");

    $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height:'30px'});

    $("#jenisplor").hide();
    $("#kode_kelompok").change(function(){
        if ($(this).val()=='00') {
          $("#jenisplor").hide();
          $("#biaya").prop( "disabled", true );
          $("#biaya").val(0);
        }else{
          $("#jenisplor").show();
          $("#biaya").prop( "disabled", false );
        }
        var datakelom = $(this).val();
        $.ajax({
          url : '<?php echo site_url('eform/kegiatankelompok/getdatakelompok') ?>',
          type : 'POST',
          data : 'datakelom=' + datakelom,
          success : function(data) {
            $('#jenis_kelompok').html(data);
          }
        });

        return false;
    }).change();
  });
</script>
