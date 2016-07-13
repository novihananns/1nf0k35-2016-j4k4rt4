<script>
  	$(function () { 
     	$('#btn-kembali').click(function(){
	        window.location.href="<?php echo base_url()?>eform/data_kepala_keluarga";
	    });

      <?php
      $jam_data = strtotime($jam_data);

      if(set_value('jam_selesai')=="" && isset($jam_selesai)){
        $jam_selesai = strtotime($jam_selesai);
      }else{
        $jam_selesai = strtotime(set_value('jam_selesai'));
      }
      if($jam_selesai=="") $jam_selesai = time();
      ?>

      $("#tgl_pengisian").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});
      
      var date = new Date();
      date.setHours(<?php echo date("H", $jam_data)?>);
      date.setMinutes(<?php echo date("i", $jam_data)?>);
      date.setSeconds(<?php echo date("s", $jam_data)?>);
      $("#jam_data").jqxDateTimeInput({ height: '30px', theme: theme, formatString: 'HH:mm:ss', showTimeButton: true, showCalendarButton: false});
      $("#jam_data").jqxDateTimeInput('setDate', date);

      var date = new Date();
      date.setHours(<?php echo date("H", $jam_selesai)?>);
      date.setMinutes(<?php echo date("i", $jam_selesai)?>);
      date.setSeconds(<?php echo date("s", $jam_selesai)?>);
      $("#jam_selesai").jqxDateTimeInput({ height: '30px', theme: theme, formatString: 'HH:mm:ss', showTimeButton: true, showCalendarButton: false});
      $("#jam_selesai").jqxDateTimeInput('setDate', date);

	});
</script>

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
<form action="<?php echo base_url()?>eform/data_kepala_keluarga/{action}/{id_data_keluarga}" method="post">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-body">

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px"><b>Nomor Urut</b></div>
        <div class="col-md-8 col-xs-8"><b> : {nourutkel}</b></div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Tanggal Pengisian</div>
        <div class="col-md-8 col-xs-8">
          <div id='tgl_pengisian' name="tgl_pengisian" value="<?php
            $tgl_pengisian = strtotime($tanggal_pengisian);
            echo date("Y-m-d",$tgl_pengisian);
          ?>" >
          </div>         
        </div>
      </div>
      
      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Jam Mulai Mendata</div>
        <div class="col-md-8 col-xs-8">
          <div id='jam_data' name="jam_data"></div>
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Provinsi</div>
        <div class="col-md-8 col-xs-8"> :
            <?php
            foreach($data_provinsi as $row_provinsi){
                echo ucwords(strtolower($row_provinsi->value));
            }    
            ?>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Kabupaten / Kota</div>
        <div class="col-md-8 col-xs-8"> :
            <?php
            foreach($data_kotakab as $row_kotakab){
                echo ucwords(strtolower($row_kotakab->value));
            }    
            ?>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Kecamatan</div>
        <div class="col-md-8 col-xs-8"> :
            <?php
            foreach($data_kecamatan as $row_kecamatan){
              echo ucwords(strtolower($row_kecamatan->nama));
            }    
            ?>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Desa / Kelurahan</div>
        <div class="col-md-8 col-xs-8">
          <select  name="kelurahan" id="kelurahan" class="form-control">
            <?php
            foreach($data_desa as $row_desa){
                $select = $row_desa->code == $id_desa ? 'selected' : '' ;
            ?>
                <option value="<?php echo $row_desa->code; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_desa->value)); ?></option>
            <?php
            }    
            ?>
        </select>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Dusun / RW</div>
        <div class="col-md-8 col-xs-8">
          <input type="number" name="dusun"  id="dusun" placeholder="RW" value="<?php 
            if(set_value('dusun')=="" && isset($rw)){
              echo $rw;
            }else{
              echo  set_value('dusun');
            }
            ?>" class="form-control">
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">RT</div>
        <div class="col-md-8 col-xs-8">
          <input type="number" name="rt" id="rt" placeholder="RT" value="<?php 
            if(set_value('rt')=="" && isset($rt)){
              echo $rt;
            }else{
              echo  set_value('rt');
            }
            ?>" class="form-control">
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Nomor Rumah</div>
        <div class="col-md-8 col-xs-8">
          <input type="text" name="norumah" id="norumah" placeholder="Nomor Rumah" value="<?php 
            if(set_value('norumah')=="" && isset($norumah)){
              echo $norumah;
            }else{
              echo  set_value('norumah');
            }
            ?>" class="form-control">
        </div>
      </div>
 
      <div class="row" style="margin: 5px">
        <div class="col-md-4 col-xs-4" style="padding: 5px">Kode Pos</div>
        <div class="col-md-8 col-xs-8">
          <select  name="kodepos" id="kodepos" class="form-control">
            <?php
            foreach($data_pos as $row_pos){
            $select = $row_pos->pos == $id_kodepos ? 'selected' : '' ;
            ?>
                <option value="<?php echo $row_pos->pos; ?>" <?php echo $select; ?>><?php echo chunk_split($row_pos->pos, 1, ' '); ?></option>
            <?php
            }    
            ?>
          </select>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Alamat</div>
        <div class="col-md-8">
          <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat"><?php 
      if(set_value('alamat')=="" && isset($alamat)){
        echo $alamat;
      }else{
        echo  set_value('alamat');
      }
      ?></textarea>
        </div>
      </div>

    </div>
  </div><!-- /.form-box -->
</div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">

      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">Nama Komunitas</div>
        <div class="col-md-7">
          <input type="text" name="namakomunitas" id="namakomunitas" value="<?php 
      			if(set_value('namakomunitas')=="" && isset($nama_komunitas)){
      				echo $nama_komunitas;
      			}else{
      				echo  set_value('namakomunitas');
      			}
      			?>" class="form-control">
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">Nama Kepala Rumah Tangga</div>
        <div class="col-md-7">
          <input type="text" name="namakepalakeluarga" id="namakepalakeluarga" value="<?php 
      			if(set_value('namakepalakeluarga')=="" && isset($namakepalakeluarga)){
      				echo $namakepalakeluarga;
      			}else{
      				echo  set_value('namakepalakeluarga');
      			}
      			?>" class="form-control">
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">No. HP / Telepon</div>
        <div class="col-md-7">
          <input type="text" name="notlp" id="notlp" value="<?php 
      			if(set_value('notlp')=="" && isset($notlp)){
      				echo $notlp;
      			}else{
      				echo  set_value('notlp');
      			}
      			?>" class="form-control">
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">Nama Dasa Wisma</div>
        <div class="col-md-7">
          <input type="text" name="namadesawisma" id="namadesawisma" value="<?php 
      			if(set_value('namadesawisma')=="" && isset($namadesawisma)){
      				echo $namadesawisma;
      			}else{
      				echo  set_value('namadesawisma');
      			}
      			?>" class="form-control">
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-12" style="padding: 5px">Jumlah Jiwa</div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-1" style="padding: 5px"></div>
        <div class="col-md-4" style="padding: 5px">Laki-laki</div>
        <div class="col-md-7">
          <input type="number" name="jml_anaklaki" placeholder="Jumlah Laki-laki" id="jml_anaklaki" value="<?php 
            if(set_value('jml_anaklaki')=="" && isset($jml_anaklaki)){
              echo $jml_anaklaki;
            }else{
              echo  set_value('jml_anaklaki');
            }
            ?>" class="form-control">
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-1" style="padding: 5px"></div>
        <div class="col-md-4" style="padding: 5px">Perempuan</div>
        <div class="col-md-7">
          <input type="number" name="jml_anakperempuan" placeholder="Jumlah Perempuan" id="jml_anakperempuan" value="<?php 
            if(set_value('jml_anakperempuan')=="" && isset($jml_anakperempuan)){
              echo $jml_anakperempuan;
            }else{
              echo  set_value('jml_anakperempuan');
            }
            ?>" class="form-control">
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-12" style="padding: 5px">Jumlah PUS</div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-1" style="padding: 5px"></div>
        <div class="col-md-4" style="padding: 5px">Peserta KB</div>
        <div class="col-md-7">
          <input type="number" name="pus_ikutkb" placeholder="Pus Peserta KB" id="pus_ikutkb" value="<?php 
            if(set_value('pus_ikutkb')=="" && isset($pus_ikutkb)){
              echo $pus_ikutkb;
            }else{
              echo  set_value('pus_ikutkb');
            }
            ?>" class="form-control">
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-1" style="padding: 5px"></div>
        <div class="col-md-4" style="padding: 5px">Bukan Peserta KB</div>
        <div class="col-md-7">
          <input type="number" name="pus_tidakikutkb" placeholder="Pus Bukan peserta KB" id="pus_tidakikutkb" value="<?php 
            if(set_value('pus_tidakikutkb')=="" && isset($pus_tidakikutkb)){
              echo $pus_tidakikutkb;
            }else{
              echo  set_value('pus_tidakikutkb');
            }
            ?>" class="form-control">
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">Jam Selesai Mendata</div>
        <div class="col-md-7">
          <div id='jam_selesai' name="jam_selesai"></div>
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">Nama Koordinator</div>
        <div class="col-md-7">
          <input type="text" name="nama_koordinator" autocomplete="off" id="nama_koordinator" value="<?php 
            if(set_value('nama_koordinator')=="" && isset($nama_koordinator)){
              echo $nama_koordinator;
            }else{
              echo  set_value('nama_koordinator');
            }
            ?>" class="form-control">
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-5" style="padding: 5px">Nama Pendata</div>
        <div class="col-md-7">
          <input type="text" name="nama_pendata" autocomplete="off" id="nama_pendata" value="<?php 
            if(set_value('nama_pendata')=="" && isset($nama_pendata)){
              echo $nama_pendata;
            }else{
              echo  set_value('nama_pendata');
            }
            ?>" class="form-control">
        </div>
      </div>


      <div class="box-footer" style="float: right">
        <button type="submit" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan Data Keluarga</button>
        <!--<button type="submit" class="btn btn-primary"><i class='fa fa-print'></i> &nbsp; Cetak</button>-->
        <button type="button" id="btn-kembali" class="btn btn-success"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      </div>
    </form>        
  </div><!-- /.form-box -->
</div><!-- /.register-box -->
</div><!-- /.register-box -->
				
<div id='jqxWidget'>
    <div id='jqxTabs'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-home" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Profile Keluarga</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-group" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Anggota Keluarga</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-female" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Keluarga Berencana</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-headphones" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                      Pembangunan Keluarga</div>
              </div>
            </li>
        </ul>
        <div id="content1" style="background: #FAFAFA"></div>
        <div id="content2" style="background: #FAFAFA"></div>
        <div id="content3" style="background: #FAFAFA"></div>
        <div id="content4" style="background: #FAFAFA"></div>
    </div>
</div>

<script>
$(function () { 
    $('#jqxTabs').jqxTabs({ width: '100%', height: '1700'});

    var loadPage = function (url, tabIndex) {
        $.get(url, function (data) {
            $('#content' + tabIndex).html(data);
        });
    }

    loadPage('<?php echo base_url()?>eform/data_kepala_keluarga/tab/1/{id_data_keluarga}', 1);
    $('#jqxTabs').on('selected', function (event) {
        var pageIndex = event.args.item + 1;
        loadPage('<?php echo base_url()?>eform/data_kepala_keluarga/tab/'+pageIndex+'/{id_data_keluarga}', pageIndex);
    });


    $('#btn-datakeluarga').click(function(){
    	var id_data_keluarga = "<?php echo $id_data_keluarga; ?>";
        window.location.href="<?php echo base_url()?>eform/anggota_keluarga_kependudukan/edit/"+id_data_keluarga;
    });
    
    $("#btn-print").click(function(){
        $('a.print-preview').printPreview();
        $('#btnPreview').click(); 
    });
        

    $("#menu_ketuk_pintu").addClass("active");
    $("#menu_eform_data_kepala_keluarga").addClass("active");
});

$("#nama_koordinator").jqxInput(
    {
    width: '100%',
    height: '30px',
    minLength: 2,
    source: function (query, response) {
      var dataAdapter = new $.jqx.dataAdapter
      (
        {
          datatype: "json",
            datafields: [
            { name: 'nama_koordinator', type: 'string'}
          ],
          url: '<?php echo base_url().'eform/data_kepala_keluarga/autocomplite_namakoordinator'; ?>'
        },
        {
          autoBind: true,
          formatData: function (data) {
            data.query = query;
            return data;
          },
          loadComplete: function (data) {
            if (data.length > 0) {
              response($.map(data, function (item) {
                return item.nama_koordinator;
              }));
            }
          }
        });
    }
});
$("#nama_pendata").jqxInput(
    {
    width: '100%',
    height: '30px',
    minLength: 2,
    source: function (query, response) {
      var dataAdapter = new $.jqx.dataAdapter
      (
        {
          datatype: "json",
            datafields: [
            { name: 'nama_pendata', type: 'string'}
          ],
          url: '<?php echo base_url().'eform/data_kepala_keluarga/autocomplite_namapendata'; ?>'
        },
        {
          autoBind: true,
          formatData: function (data) {
            data.query = query;
            return data;
          },
          loadComplete: function (data) {
            if (data.length > 0) {
              response($.map(data, function (item) {
                return item.nama_pendata;
              }));
            }
          }
        });
    }
});

</script>
<a href='javascript:void(0);' class='print-preview tombol' id='btnPreview' style="display: none;">Preview</a>
<div id="datacetak" style="display: none;">
    {data_print}
</div>
