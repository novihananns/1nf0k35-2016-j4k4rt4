<section class="content">
<form action="<?php echo base_url()?>mst/club_prolanis/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
	      	<div class="row">
	      		<div class="col-md-8">
				 	<button type="button" class="btn btn-warning" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
				 	<button type="button" class="btn btn-danger" id="btn-sync"><i class='fa fa-file-excel-o'></i> &nbsp; Re-Sync</button>
				 </div>
				 <div class="col-md-4 pull-right">
				 	<label> Puskesmas </label>
				 	<select name="puskesmas" id="puskesmas" class="form-control">
			     	</select>
				 </div>
			</div>
		 </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</form>
</section>

<script type="text/javascript">
	$(function () {	
		$("#menu_mst_club_prolanis").addClass("active");
		$("#menu_master_data").addClass("active");

		$.ajax({
			url : '<?php echo site_url('mst/club_prolanis/get_puskesmas') ?>',
		    type : 'POST',
		    success : function(data) {
		      $('#puskesmas').html(data);
		    }
		});
	});
		
	  var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'clubId', type: 'string'},
			{ name: 'kdProgram', type: 'string'},
			{ name: 'alamat', type: 'string'},
			{ name: 'ketua_noHP', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'ketua_nama', type: 'string'},
			{ name: 'provider', type: 'string'}
        ],
		url: "<?php echo site_url('mst/club_prolanis/json'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$('#btn-refresh').click(function () {
			$("#jqxgrid").jqxGrid('clearfilters');
		});

		$("#jqxgrid").jqxGrid({		
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
                { text: 'Club ID', datafield: 'clubId', columntype: 'textbox', align:'center', cellsalign:'center', filtertype: 'textbox',width: '8%' },
                { text: 'Nama Club Prolanis', datafield: 'nama', columntype: 'textbox', align:'left', filtertype: 'textbox',width: '26%' },
				{ text: 'Alamat', datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', width: '32%' },
				{ text: 'Nama Ketua', datafield: 'ketua_nama', columntype: 'textbox', filtertype: 'textbox', width: '18%' },
				{ text: 'No HP', datafield: 'ketua_noHP', columntype: 'textbox', filtertype: 'textbox', width: '16%' }

			]
		});

    $('#puskesmas').change(function(){
      var puskesmas = $(this).val();
      $.ajax({
        url : '<?php echo site_url('mst/club_prolanis/get_puskesmasfilter') ?>',
        type : 'POST',
        data : 'puskesmas=' + puskesmas,
        success : function(data) {
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();

    $("#btn-sync").click(function(){
		$.post("<?php echo base_url()?>mst/club_prolanis/bpjs_club",function(response){
			alert(response);
		});
	});
</script>