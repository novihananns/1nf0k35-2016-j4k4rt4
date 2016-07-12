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
<form action="<?php echo base_url()?>eform/data_kepala_keluarga/importdata" method="post" enctype="multipart/form-data">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-footer">
        <button type="button" id="btn-kembali" class="btn btn-success"><i class='fa fa-arrow-circle-left'></i> &nbsp;Kembali</button>
        <button type="button" class="btn btn-primary"><i class='fa fa-file-excel-o'></i> &nbsp; Download Template</button>
        <button type="submit" id="btn-import" class="btn btn-warning"><i class='fa fa-arrow-circle-right'></i> &nbsp; Periksa File & Import</button>
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
        <div class="col-md-4" style="padding: 5px; font-weight:bold">Tentukan File Excel</div>
        <div class="col-md-8">
          <input type="file" name="file" placeholder="File *.xls" class="form-control">
        </div>
      </div>
      <div id="respon1"></div>

      <br><br>
    </div>
  </div><!-- /.form-box -->
</div><!-- /.form-box -->

  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-body">
       <div class="row" style="margin: 5px">
       <label>Keterangan:</label>
       <ol>
         <li>Gunakan template excel yang telah disediakan</li>
         <li>Pastikan pengisian excel benar sesuai contoh</li>
         <li>1 (satu) file untuk import data dalam 1 (satu) kelurahan</li>
         <li>Data akan di import kedalam database jika file excel 100% benar</li>
         <li>Disarankan hanya meng import maksimal 1000 data per file</li>
       </ol>
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

// $("#btn-import").click(function(){
//   var data = new FormData();
//     data.append('filename', $("#filename").val());
    
//     $.ajax({
//         cache : false,
//         contentType : false,
//         processData : false,
//         type : 'POST',
//         url : '<?php echo base_url()."eform/data_kepala_keluarga/importdata" ?>',
//         data : data,
//         success : function(response){
//           if(response=="OK"){
//             alert("OK");
//           }else{
//             alert("Failed");
//           }
//         }
//     });
// });

  // $("#btn-import").click(function() {

  //     var file=$("#filename").val();
  //     var form = $('form').get(0); 

  //     $.ajax({
  //         type:'post',
  //         dataType:'json',
  //         url : '<?php echo base_url()."eform/data_kepala_keluarga/importdata" ?>',
  //         data: new FormData(form),
  //         mimeType:"multipart/form-data",
  //         contentType: false,
  //         cache: false,
  //         processData:false,
  //         beforeSend:function(){
  //             $("#respon1").html('<img src="<?=base_url();?>media/images/ajax-loader.gif"/><span>Harap Tunggu...</span>');
  //         },
  //         success:function(x){
  //             $("#respon1").html(x);
  //             $("#resetbtn").trigger('click');
  //             return false;
  //         },
  //     });
  // });

</script>
