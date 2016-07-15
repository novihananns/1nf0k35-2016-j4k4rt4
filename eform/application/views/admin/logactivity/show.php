<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>logactivity/dodel_multi" method="POST" name="frmUsers">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
          <div class="box-footer">
          	<div class="col-md-3" style="float:right;">
			 	<div id="tglfilterlog" value="<?php
              echo ($this->session->userdata('filter_datatglfilterlog')!="") ? date("Y-m-d",strtotime($this->session->userdata('filter_datatglfilterlog'))) : "";
            ?>"></div>
			</div>
          	<div class="col-md-3" style="float:right;">
			 	<select id="filterlog" class="form-control">
			 		<option value="">Pilih Log</option>
			 		<?php 
			 			foreach ($datalogfilter as $key => $value) {
			 			$select = (($key == $this->session->userdata('filter_datafilterlog')) ? 'selected' : '');
			 		?>
			 		<option value="<?php echo $key; ?>" <?php echo $select; ?>> <?php echo $value; ?></option>
			 		<?php
			 			}
			 		?>
			 	</select>
			</div>
         </div>
	    </div>

        <div class="box-body">
                  <table id="dataTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                     	<th>No</font></th>
						<th>Username</font></th>
						<th>Waktu</font></th>
						<th>Keterangan</font></th>
                      </tr>
                    </thead>
                    <tbody>
					<?php 
					$start = '000';
					foreach($query as $row):
						$tmp = ((int)$start)+1;
                		$start = sprintf("%03s", $tmp);
					?>
						<tr>
							<td><?php echo $start; ?>&nbsp;</td>
							<td><?php echo $row->username?>&nbsp;</td>
							<td><?php echo date('g:i:s A D, F jS Y',$row->dtime) ?>&nbsp;</td>
							<td><?php echo $row->activity?>&nbsp;</td>
						</tr>
					<?php endforeach;?>                   
				</tbody>
                    <tfoot>
                      <tr>
                      	<th>No</font></th>
						<th>Username</font></th>
						<th>Waktu</font></th>
						<th>Keterangan</font></th>
                      </tr>
                    </tfoot>
                  </table>
	    </div>

	  </div>
	</div>
  </div>
</form>
</section>
<script>
	$(function () {	
		$("#tglfilterlog").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme, height: '30px'});
		$("#dataTable").dataTable();
		$("#menu_logactivity").addClass("active");
		$("#menu_admin_panel").addClass("active");
	});
	$("#filterlog").change(function(){
			 var filterlog = $(this).val();
		      $.ajax({
		        url : '<?php echo base_url().'logactivity/get_filterlogactivity' ?>',
		        type : 'POST',
		        data : 'filterlog=' + filterlog,
		        success : function(data) {
		          	location.reload();
		        }
		      });

		      return false;
		});
		$("#tglfilterlog").change(function(){
			 var tglfilterlog = $(this).val();
		      $.ajax({
		        url : '<?php echo base_url().'logactivity/get_tglfilterlog' ?>',
		        type : 'POST',
		        data : 'tglfilterlog=' + tglfilterlog,
		        success : function(data) {
		          	location.reload();
		        }
		      });

		      return false;
		});
</script>
