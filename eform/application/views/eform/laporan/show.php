
<div class="row">

	<div class="col-md-12">
		<div class="box box-warning">
			<div class="box-body">
				<div class="row">
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Laporan</label>
				     		<select name="laporan" class="form-control" id="laporan">
				     			<option value="">Pilih Laporan</option>
				     			<option value="1">Distribusi Penduduk Berdasarkan Jenis Kelamin</option>
				     			<option value="2">Distribusi Penduduk Menurut Usia</option>
				     			<option value="3">Distribusi Penduduk Menurut Tingkat Pendidikan</option>
				     			<option value="4">Distribusi Penduduk Berdasarkan Pekerjaan</option>
				     			<option value="5">Distribusi Penduduk Mengikuti Kegiatan Posyandu</option>
				     			<option value="6">Distribusi Penduduk Penyandang Disabilitas</option>
				     			<option value="7">Distribusi Penduduk Jaminan Kesehatan</option>
				     			<option value="8">Distribusi Penduduk Keikutsertaan KB</option>
				     			<option value="9">Distribusi Penduduk Alasan Tidak KB</option>
				     			<option value="10">Distribusi Penduduk Berdasarkan Kepemilikan Rumah</option>
				     			<option value="11">Distribusi Penduduk Berdasarkan Jenis Atap Rumah</option>
				     			<option value="12">Distribusi Penduduk Berdasarkan Jenis Dinding Rumah</option>
				     			<option value="13">Distribusi Penduduk Berdasarkan Jenis Lantai Rumah</option>
				     			<option value="14">Distribusi Penduduk Berdasarkan Sumber Penerangan</option>
				     			<option value="15">Distribusi Penduduk Berdasarkan Sumber Air Minum</option>
				     			<option value="16">Distribusi Penduduk Berdasarkan Bahan Bakar untuk Memasak</option>
				     			<option value="17">Distribusi Penduduk Berdasarkan Fasilitas BAB</option>
				     			<option value="18">Distribusi Penduduk Kebiasaan Mencuci Tangan dengan Sabun</option>
				     			<option value="19">Distribusi Penduduk Berdasarkan Lokasi BAB</option>
				     			<option value="20">Distribusi Penduduk Kebiasaan Sikat Gigi</option>
				     			<option value="21">Distribusi Penduduk Berdasarkan Kebiasaan Merokok</option>
				     			<option value="22">Distribusi Penduduk Berdasarkan Usia Mulai Merokok</option>
				     			<option value="23">Distribusi Penduduk Berdasarkan Penderita Sakit Ginjal</option>
				     			<option value="24">Distribusi Penduduk Berdasarkan Sakit TB Paru </option>
				     			<option value="25">Distribusi Penduduk Berdasarkan Penderita Diabetes Melitus (DM)</option>
				     			<option value="26">Distribusi Penduduk Berdasarkan Penderita Hipertensi</option>
				     			<option value="27">Distribusi Penduduk Berdasarkan Penderita Jantung Koroner</option>
				     			<option value="28">Distribusi Penduduk Berdasarkan Penderita Stroke</option>
				     			<option value="29">Distribusi Penduduk Berdasarkan Penderita Kanker</option>
				     			<option value="30">Distribusi Penduduk Berdasarkan Penderita Asma</option>
				     			<option value="31">Distribusi Penduduk Sulit Tidur</option>
				     			<option value="32">Distribusi Penduduk Mudah Takut</option>
				     			<option value="33">Distribusi Penduduk Sulit Berfikir Jernih</option>
				     			<option value="34">Distribusi Penduduk Merasa Tidak Bahagia</option>
				     			<option value="35">Distribusi Penduduk Menagis Lebih Sering</option>
				     			<option value="36">Distribusi Penduduk Mempunyai Pikiran untuk Mengakhiri Hidup</option>
				     			<option value="37">Distribusi Penduduk Hilang Minat</option>
				     			<option value="38">Distribusi Penduduk Berdasarkan Test IVA</option>
				     			<option value="39">Distribusi Penduduk Berdasarkan Test PAP SMEAR</option>
				     			<option value="40">Distribusi Penduduk Berdasarkan Status Imunisasi</option>
				     			<option value="41">Distribusi Balita Berdasarkan Status Imunisasi</option>
				     			
				     		</select>
				  		</div>
				  	</div>				  	
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Kecamatan</label>
				     		<select name="kecamatan" class="form-control" id="kecamatan">
				     			<!--<option value="">Pilih Kecamatan</option>-->
				     			<?php foreach ($datakecamatan as $kec ) { ;?>
								<?php $select = $kec->code == substr($this->session->userdata('puskesmas'), 0,7)  ? 'selected=selected' : '' ?>
									<option value="<?php echo $kec->code; ?>" <?php echo $select ?>><?php echo $kec->nama; ?></option>
								<?php	} ;?>
				     		</select>
						</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label>Kelurahan</label>
				     		<select name="kelurahan" class="form-control" id="kelurahan">
				     			<option value="">Pilih Kelurahan</option>
				     		</select>
				  		</div>
				  	</div>
				  	<div class="col-md-6">
						<div class="form-group">
							<label>RW</label>
				     		<select name="rw" class="form-control" id="rw">
				     			<option value="">Pilih RW</option>
				     		</select>
				  		</div>
				  	</div>
				  	<div class="col-md-12">
						<div class="form-group pull-right">
						<!--	<button id="btn-export" type="button"  class="btn btn-success"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>-->
            				<button id="btn-preview" type="button"  class="btn btn-warning"><i class='fa fa-bar-chart-o'></i> &nbsp; Tampilkan Laporan & Chart</button>
						</div>
				  	</div>
				</div>
			</div>
		</div><!-- /.box -->
	</div><!-- /.box -->
</div><!-- /.box -->
<div class="row">
  <div class="col-md-12">
  	<div class="box" id="loading">
	    <div class="box-header with-border">
	    	<h3 class="box-title" id="judulloading" align="center">Mohon tunggu, sedang proses...</h3>
	    </div>
    </div>
    <div class="box" id="hilang">
      <div class="box-header with-border">
        <h3 class="box-title" id="judul">Tunggu, sedang proses...</h3>
        <br><br>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body" id="isi" style="min-height: 200px">
       <!-- <div class="row" id="rowchart">-->
          <div id="tampilchart">
            <!--<canvas id="tampilchart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>-->
          </div>
        <!--</div>-->
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">
	$(function () {	
		
		$("#menu_ketuk_pintu").addClass("active");
		$("#menu_eform_laporan_kpldh").addClass("active");

		$("#loading").hide();
		$("#hilang").hide(); 
      	$('#btn-preview').click(function(){
			$("#hilang").hide();
			$("#loading").show();
      		var judul = $('[name=laporan] :selected').text();
      		var kecamatanbar = $("#kecamatan").val();
      		var kelurahanbar = $("#kelurahan").val();
      		var id_judul = $("#laporan").val();
      		var rw = $("#rw").val();
	      		if ($("#laporan").val()=="") {
	      			$("#hilang").hide();
	      			alert("Silahkan Pilih Laporan Terlebih Dahulu");
	      		}else{
	      		}
      		$.ajax({
		        url : '<?php echo site_url('eform/laporan_kpldh/pilihchart/') ?>',
		        type : 'POST',
		        data : 'judul=' + judul+'&kecamatan=' + kecamatanbar+'&kelurahan=' + kelurahanbar+'&rw=' + rw+'&id_judul=' + id_judul,
		        success : function(data) {
		        	$("#loading").hide();
		        	$("#hilang").show(); 
		        	$('#judul').html($('[name=laporan] :selected').text());
		          	$('#tampilchart').html(data);
		        }
	     	});

	      return false;

      	});
      	
      	$('#kecamatan').change(function(){
	      var kecamatan = $(this).val();
	     // var id_mst_inv_ruangan = '<?php echo set_value('ruangan')?>';
	      $.ajax({
	        url : '<?php echo site_url('eform/laporan_kpldh/get_kecamatanfilter') ?>',
	        type : 'POST',
	        data : 'kecamatan=' + kecamatan,
	        success : function(data) {
	          $('#kelurahan').html(data);
	        }
	      });

	      return false;
	    }).change();
	    $('#kelurahan').change(function(){
	      var kelurahan = $(this).val();
	     // var id_mst_inv_ruangan = '<?php echo set_value('ruangan')?>';
	      $.ajax({
	        url : '<?php echo site_url('eform/laporan_kpldh/get_kelurahanfilter') ?>',
	        type : 'POST',
	        data : 'kelurahan=' + kelurahan,
	        success : function(data) {
	          $('#rw').html(data);
	        }
	      });

	      return false;
	    }).change();



	 

       
	});
	$("#btn-export").click(function(){
  		var judul = $('[name=laporan] :selected').text();
  		var id_judul = $("#laporan").val();
  		var kecamatanbar = $("#kecamatan").val();
  		var kelurahanbar = $("#kelurahan").val();
  		var rw = $("#rw").val();

		var post = "";
		post = post+'judul='+judul+'&kecamatan='+ kecamatanbar+'&kelurahan=' + kelurahanbar+'&rw=' + rw+'&id_judul=' + id_judul;
		
		$.post("<?php echo base_url()?>eform/export_data/pilih_export",post,function(response){
			//window.location.href=response;
			alert(response);
		});
	});
</script>
