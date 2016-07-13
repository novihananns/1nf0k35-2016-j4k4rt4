<script>
  	$(function () { 

		  <?php
        if(set_value('jam_data')=="" && isset($jam_data)){
          $jam_data = strtotime($jam_data);
        }else{
          $jam_data = strtotime(set_value('jam_data'));
        }
        if($jam_data=="") $jam_data = time();
    	?>

		  var date = new Date();
	    date.setHours(<?php echo date("H", $jam_data)?>);
			date.setMinutes(<?php echo date("i", $jam_data)?>);
			date.setSeconds(<?php echo date("s", $jam_data)?>);
		  $("#jam_data").jqxDateTimeInput({ height: '30px', theme: theme, formatString: 'HH:mm:ss', showTimeButton: true, showCalendarButton: false});
		  $("#jam_data").jqxDateTimeInput('setDate', date);
		
    	$("#tgl_pengisian").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});

      $('#btn-kembali').click(function(){
	        window.location.href="<?php echo base_url()?>eform/data_kepala_keluarga";
	    });
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
      <div class="box-footer">
        <button type="submit" class="btn btn-warning"><i class='fa fa-save'></i> &nbsp; Simpan</button>
        <button type="button" id="btn-kembali" class="btn btn-success"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
      </div>
      <div class="box-body">

      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Tanggal Pengisian</div>
        <div class="col-md-8">
          <div id='tgl_pengisian' name="tgl_pengisian" value="<?php
            if(set_value('tgl_pengisian')=="" && isset($tgl_pengisian)){
              $tgl_pengisian = strtotime($tgl_pengisian);
            }else{
              $tgl_pengisian = strtotime(set_value('tgl_pengisian'));
            }
            if($tgl_pengisian=="") $tgl_pengisian = time();
            echo date("Y-m-d",$tgl_pengisian);
          ?>" >
          </div>
        </div>
      </div>
      
      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Jam Mulai Mendata</div>
        <div class="col-md-8">
          <div id='jam_data' name="jam_data"></div>
        </div>
      </div>
        
      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Provinsi</div>
        <div class="col-md-8">
          <select  name="provinsi" id="provinsi" class="form-control">
          	<?php
            foreach($data_provinsi as $row_provinsi){
            ?>
                <option value="<?php echo $row_provinsi->code; ?>" ><?php echo ucwords(strtolower($row_provinsi->value)); ?></option>
            <?php
            }    
          	?>
	      </select>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Kabupaten / Kota</div>
        <div class="col-md-8">
          <select  name="kota" id="kota" class="form-control">
          	<?php
            foreach($data_kotakab as $row_kotakab){
            ?>
                <option value="<?php echo $row_kotakab->code; ?>" ><?php echo ucwords(strtolower($row_kotakab->value)); ?></option>
            <?php
            }    
          	?>
	      </select>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Kecamatan</div>
        <div class="col-md-8">
          <select  name="id_kecamatan" id="id_kecamatan" class="form-control">
          	<?php
            foreach($data_kecamatan as $row_kecamatan){
            ?>
                <option value="<?php echo $row_kecamatan->code; ?>" ><?php echo ucwords(strtolower($row_kecamatan->nama)); ?></option>
            <?php
            }    
          	?>
	      </select>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Desa / Kelurahan</div>
        <div class="col-md-8">
          <select  name="kelurahan" id="kelurahan" class="form-control">
          	<?php
	        if(set_value('kelurahan')=="" && isset($kelurahan)){
	          $kelurahan = $kelurahan;
	        }else{
	          $kelurahan = set_value('kelurahan');
	        }

            foreach($data_desa as $row_desa){
	 	        $select = $row_desa->code == $kelurahan ? 'selected' : '' ;
            ?>
                <option value="<?php echo $row_desa->code; ?>" <?php echo $select; ?>><?php echo ucwords(strtolower($row_desa->value)); ?></option>
            <?php
            }    
          	?>
	      </select>
        </div>
      </div>

      <div class="row" style="margin: 5px">
        <div class="col-md-4" style="padding: 5px">Dusun / RW</div>
        <div class="col-md-8">
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
        <div class="col-md-4" style="padding: 5px">RT</div>
        <div class="col-md-8">
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
        <div class="col-md-4" style="padding: 5px">Nomor Rumah</div>
        <div class="col-md-8">
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
        <div class="col-md-5" style="padding: 5px">Kode Pos</div>
        <div class="col-md-7">
          <select  name="kodepos" id="kodepos" class="form-control">
          	<?php
	        if(set_value('kodepos')=="" && isset($kodepos)){
	          $kodepos = $kodepos;
	        }else{
	          $kodepos = set_value('kodepos');
	        }

            foreach($data_pos as $row_pos){
	 	        $select = $row_pos->pos == $kodepos ? 'selected' : '' ;
            ?>
                <option value="<?php echo $row_pos->pos; ?>" <?php echo $select; ?>><?php echo chunk_split($row_pos->pos, 1, ' '); ?></option>
            <?php
            }    
          	?>
          </select>
        </div>
      </div>

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
        <div class="col-md-5" style="padding: 5px">Jabatan Stuktural TP PKK</div>
        <div class="col-md-7">
          <select  name="jabatanstuktural" id="jabatanstuktural" class="form-control">
          	<?php
	        if(set_value('jabatanstuktural')=="" && isset($jabatanstuktural)){
	          $jabatanstuktural = $jabatanstuktural;
	        }else{
	          $jabatanstuktural = set_value('jabatanstuktural');
	        }

            foreach($data_pkk as $row_pkk){
	 	        $select = $row_pkk->id_pkk == $jabatanstuktural ? 'selected' : '' ;
            ?>
                <option value="<?php echo $row_pkk->id_pkk; ?>" <?php echo $select; ?>><?php echo $row_pkk->value; ?></option>
            <?php
            }    
          	?>
		  </select>
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
        <div class="col-md-4" style="padding: 5px">Peseta KB</div>
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

      </div>
    </form>        
  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script>
$(function () { 
	$("#menu_ketuk_pintu").addClass("active");
	$("#menu_eform_data_kepala_keluarga").addClass("active");
});
</script>
