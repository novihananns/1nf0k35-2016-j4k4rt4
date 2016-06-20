
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>eform/kegiatankelompok/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
	        <div class="box-header">
	          <h3 class="box-title">{title_form}</h3>
		    </div>

	      	<div class="box-footer">
		      <div class="col-md-8">
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>eform/kegiatankelompok/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Kegiatan</button>
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
	          <button type="button" id="btn-export" class="btn btn-warning" style=""><i class='fa fa-save'></i> &nbsp; Export</button>
		     </div>
		      <div class="col-md-4">
		     	<div class="row">
			     	<div class="col-md-4" style="padding-top:5px;"><label> Puskesmas </label> </div>
			     	<div class="col-md-8">
			     		<select name="code_cl_phc" id="puskesmas" class="form-control">
							<?php foreach ($datapuskesmas as $row ) { ;?>
								<option value="<?php echo $row->code; ?>" onchange="" ><?php echo $row->value; ?></option>
							<?php	} ;?>
				     	</select>
				     </div>	
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
	    $("#menu_eform_kegiatankelompok").addClass("active");
    	$("#menu_kegiatan_kelompok").addClass("active");
	});
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_data_kegiatan', type: 'string'},
			{ name: 'tgl', type: 'date'},
			{ name: 'kode_kelompok', type: 'string'},
			{ name: 'status_penyuluhan', type: 'string'},
			{ name: 'status_senam', type: 'string'},
			{ name: 'materi', type: 'string'},
			{ name: 'pembicara', type: 'string'},
			{ name: 'lokasi', type: 'string'},
			{ name: 'biaya', type: 'string'},
			{ name: 'namakelompok', type: 'string'},
			{ name: 'kegiatan', type: 'string'},
			{ name: 'kode_club', type: 'string'},
			{ name: 'keterangan', type: 'string'},
			{ name: 'eduId', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('eform/kegiatankelompok/json'); ?>",
		cache: false,
			updateRow: function (rowID, rowData, commit) {
             
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

		$("#jqxgrid").jqxGrid(
		{		
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100', '200'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.id_data_kegiatan+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id_data_kegiatan+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Kegiatan', editable:false ,datafield: 'kegiatan', columntype: 'textbox', filtertype: 'none', width: '16%' },
				{ text: 'Pelaksanaan',editable:false , align: 'center', cellsalign: 'center', datafield: 'tgl', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%' },
				{ text: 'Club Prolanis', editable:false ,align: 'center', datafield: 'kode_club', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Materi', editable:false ,align: 'center', datafield: 'materi', columntype: 'textbox', filtertype: 'textbox', width: '38%' },
				{ text: 'Status', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.eduId=="" || dataRecord.eduId==null){
						return "<div style='width:100%;padding-top:8px;text-align:center'>-</div>";
					}else{
						return "<div style='width:100%;padding-top:8px;text-align:center'><b>Sent</b></div>";
					}
                 }
                },
            ]
		});

	function edit(id){
		document.location.href="<?php echo base_url().'eform/kegiatankelompok/edit';?>/" + id ;
	}

	function del(id){
		var confirms = confirm("Hapus Data Kegiatan ?");
		if(confirms == true){
			$.post("<?php echo base_url().'eform/kegiatankelompok/dodel' ?>/"+id,  function(){
				alert('data berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}
	$("select[name='code_cl_phc']").change(function(){
		$.post("<?php echo base_url().'eform/kegiatankelompok/filter' ?>", 'code_cl_phc='+$(this).val(),  function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
    });
	$("#btn-export").click(function(){
		
		var post = "";
		var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
		for(i=0; i < filter.length; i++){
			var fltr 	= filter[i];
			var value	= fltr.filter.getfilters()[0].value;
			var condition	= fltr.filter.getfilters()[0].condition;
			var filteroperation	= fltr.filter.getfilters()[0].operation;
			var filterdatafield	= fltr.filtercolumn;
			if(filterdatafield=="tgl"){
				var d = new Date(value);
				var day = d.getDate();
				var month = d.getMonth();
				var year = d.getYear();
				value = year+'-'+month+'-'+day;
				
			}
			post = post+'&filtervalue'+i+'='+value;
			post = post+'&filtercondition'+i+'='+condition;
			post = post+'&filteroperation'+i+'='+filteroperation;
			post = post+'&filterdatafield'+i+'='+filterdatafield;
			post = post+'&'+filterdatafield+'operator=and';
		}
		post = post+'&filterscount='+i;
		
		var sortdatafield = $("#jqxgrid").jqxGrid('getsortcolumn');
		if(sortdatafield != "" && sortdatafield != null){
			post = post + '&sortdatafield='+sortdatafield;
		}
		if(sortdatafield != null){
			var sortorder = $("#jqxgrid").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgrid").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
			post = post+'&sortorder='+sortorder;
			
		}
		post = post+'&puskes='+$("#puskesmas option:selected").text();
		
		$.post("<?php echo base_url()?>eform/kegiatankelompok/pengadaan_export",post,function(response	){
			window.location.href=response;
			// alert(response);
		});
	});
</script>