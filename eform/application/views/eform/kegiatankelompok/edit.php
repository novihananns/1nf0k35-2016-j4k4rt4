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
  <form action="<?php echo base_url()?>eform/kegiatankelompok/{action}/{kode}/" method="post">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">
          <div class="form-group">
          <label>Tanggal Pelaksanaan</label>
          <div id='tgl' name="tgl" value="<?php
             if(set_value('tgl')=="" && isset($tgl)){
                $tgldata= $tgl;
              }else{
                $tgldata= set_value('tgl');
              }
              echo ($tgldata!="") ? $tgldata : "";
            ?>"></div>
        </div>
        <div class="form-group">
          <label>Jenis Kelompok</label> 
          <select  name="kode_kelompok" id="kode_kelompok" type="text" class="form-control">
              <?php foreach($jeniskelompok as $key) : ?>
                <?php $select = $key->id_mas_club_kelompok == $kode_kelompok ? 'selected' : '' ?>
                <option value="<?php echo $key->id_mas_club_kelompok ?>" <?php echo $select ?>><?php echo $key->value ?></option>
              <?php endforeach ?>
          </select>
        </div>
        <div class="form-group" id="jenisplor">
          <label>Club Prolanis</label> 
          <select name="jenis_kelompok" id="jenis_kelompok" class="form-control" id="kelurahan">
            <option value="">Pilih Club Pronalis</option>
          </select>
        </div>
        <div class="form-group">
          <label>Jenis Kegiatan</label> <br/>
          <div class="row">
            <div class="col-md-6">
              <input type="checkbox" name="edukasi" value="1" <?php if(set_value('edukasi')=="" && isset($status_penyuluhan) && $status_penyuluhan=='1'){
                  echo 'checked';
                }else{
                  echo  '';
                }?>> Penyuluhan/Edukasi
            </div>
            <div class="col-md-6">
              <input type="checkbox" name="senam" value="1" <?php if(set_value('senam')=="" && isset($status_senam) && $status_senam=='1'){
                  echo 'checked';
                }else{
                  echo  '';
                }?>> Senam
            </div>
          </div>  
        </div>
        <div class="form-group">
          <label>Materi</label>
          <input type="text" class="form-control" name="materi" placeholder="Materi" value="<?php 
            if(set_value('materi')=="" && isset($materi)){
              echo $materi;
            }else{
              echo  set_value('materi');
            }
            ?>">
        </div>
      </div>
    </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">

      <div class="form-group">
          <label>Pembicara</label>
          <input type="text" class="form-control" name="pembicara" placeholder="Pembicara" value="<?php 
            if(set_value('pembicara')=="" && isset($pembicara)){
              echo $pembicara;
            }else{
              echo  set_value('pembicara');
            }
            ?>">
            <input type="hidden" class="form-control" name="id_data_kegiatan" placeholder="id_data_kegiatan" value="<?php 
            if(set_value('id_data_kegiatan')=="" && isset($id_data_kegiatan)){
              echo $id_data_kegiatan;
            }else{
              echo  set_value('id_data_kegiatan');
            }
            ?>">
        </div>
        <div class="form-group">
          <label>Lokasi</label>
          <input type="text" class="form-control" name="lokasi" placeholder="Lokasi" value="<?php 
            if(set_value('lokasi')=="" && isset($lokasi)){
              echo $lokasi;
            }else{
              echo  set_value('lokasi');
            }
            ?>">
        </div>
        <div class="form-group">
          <label>Biaya</label>
          <input type="number" class="form-control" name="biaya" placeholder="Biaya" value="<?php 
            if(set_value('biaya')=="" && isset($biaya)){
              echo $biaya;
            }else{
              echo  set_value('biaya');
            }
            ?>">
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"><?php 
              if(set_value('keterangan')=="" && isset($keterangan)){
                echo $keterangan;
              }else{
                echo  set_value('keterangan');
              }
              ?></textarea>
        </div>
      <div id="success"> 
        
      <div style="text-align: right">
          <button type="submit" class="btn btn-primary"><i class='fa fa-floppy-o'></i> &nbsp; Simpan</button>
        <button type="button" id="btn-kembali" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
      </div>
      </div>
           
    </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->  
</form>    
</div>
<div class="box box-success">
  <div class="box-body">
    <div class="div-grid">
        <div id="jqxTabs">
          <?php echo $pesertadata;?>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
  
    $('#btn-kembali').click(function(){
        window.location.href="<?php echo base_url()?>eform/kegiatankelompok";
    });


    $("#menu_inventory_pengadaanbarang").addClass("active");
    $("#menu_aset_tetap").addClass("active");

      $("#tgl").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});
      $("#jenisplor").hide();
      $("#kode_kelompok").change(function(){
        if ($(this).val()=='00') {
          $("#jenisplor").hide();
        }else{
          $("#jenisplor").show();
        }
        var datakelom = $(this).val();
        $.ajax({
          url : '<?php echo site_url('eform/kegiatankelompok/getdatakelompokedit') ?>',
          type : 'POST',
          data : 'datakelom=' + datakelom+'&kode_club=' + "<?php echo $kode_club; ?>",
          success : function(data) {
            $('#jenis_kelompok').html(data);
          }
        });

        return false;
      }).change();
  });
    
</script>

      