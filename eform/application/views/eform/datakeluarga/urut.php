<div class="row" style="margin: 0">
  <div class="col-md-12">
  	<div class="box-footer">
		<div class="col-md-8">
	      <h4><i class="icon fa fa-exchange"></i> Pindah Nomor Urut : <b>{nourutkel}</b></h4>
	    </div>
		<div class="col-md-4" style="text-align:right">
			<button class="btn btn-warning" id="btn-close"><i class="icon fa fa-times-circle"></i> Tutup</button>
		</div>
    </div>
	<div class="box box-primary">
	  <div class="box-body">
		<div class="row">
			<div class="col-md-12">
	  			Nomor Urut yang dapat digunakan: <br> <br>
	  		</div>
	  	</div>
		<div class="row">
			<div class="col-md-12">
				<?php
				$nourutkel = intval($nourutkel);
				foreach ($vacant as $key) {
				?>
					<button onClick="nomor('<?php echo $key?>');" class="btn btn-lg btn-<?php echo ($key<$nourutkel ? 'success':'danger' )?>" style="width:80px;margin:2px">
			  			<i class="icon fa fa-long-arrow-<?php echo ($key<$nourutkel ? 'up':'down' )?>"></i>  <?php echo $key?>
			  		</button>
				<?php
				}
				?>
	  		</div>
	  	</div>
	  </div>
	</div>
  </div>
</div>
<script>
	function nomor(number){
		if(confirm('Gunakan Nomor: '+number+' ?')){
			$.get("<?php echo base_url().'eform/data_kepala_keluarga/nomor/'.$id_data_keluarga ?>/" + number,  function(res){
				if(res=="OK"){
					alert('Nomor urut berhasil diganti.');
					close_popup();
				}
			});
		}
	}

$(function () { 
	$("#btn-close").click(function(){
		close_popup();
	});

});
</script>		
