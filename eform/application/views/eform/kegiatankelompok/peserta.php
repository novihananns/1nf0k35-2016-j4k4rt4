
<script>

	$(function(){
		$("#btn-back-pesertadata").hide();
		$('#btn-back-pesertadata').click(function(){
            $("#tambahtjqxgrid_peserta").hide();
            $("#btn-back-pesertadata").hide();
            $("#jqxgrid_peserta").show();
            $("#btn_add_peserta").show();
            $("#btn-refresh-datapeserta").show();
            $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
      });
		$("#menu_kegiatan_kelompok").addClass("active");
      $("#menu_kegiatankelompok").addClass("active");	
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'no', type: 'string' },
			{ name: 'no_kartu', type: 'string' },
			{ name: 'id_data_kegiatan', type: 'string' },
			{ name: 'nama', type: 'string' },
			{ name: 'tgl_lahir', type: 'date' },
			{ name: 'usia', type: 'string' },
			{ name: 'jenis_kelamin', type: 'string' },
			{ name: 'jenis_peserta', type: 'string' },
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('eform/kegiatankelompok/detailpeserta/'.$kode); ?>",
		cache: false,
		updateRow: function (rowID, rowData, commit) {
         },
		filter: function(){
			$("#jqxgrid_peserta").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid_peserta").jqxGrid('updatebounddata', 'sort');
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
     
		$("#jqxgrid_peserta").jqxGrid(
		{	
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: true,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},

			columns: [
				{ text: 'Del', align: 'center', editable: false,filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid_peserta").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del_peserta(\""+dataRecord.id_data_kegiatan+"\",\""+dataRecord.no_kartu+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
                { text: 'No', align: 'center',cellsalign: 'center',editable: false, datafield: 'no', columntype: 'textbox', filtertype: 'none', width: '5%' },
				{ text: 'No Kartu', align: 'center',cellsalign: 'center',editable: false, datafield: 'no_kartu', columntype: 'textbox', filtertype: 'textbox', width: '16%' },
				{ text: 'Nama Peserta ', editable: false,datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Jenis Kelamin ', editable: false,datafield: 'jenis_kelamin', align: 'center', cellsalign: 'center', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
				{ text: 'Jenis Peserta ', align: 'center',cellsalign: 'center',editable: false,datafield: 'jenis_peserta', columntype: 'textbox', filtertype: 'textbox', width: '20%'},
				{ text: 'Tanggal Lahir',align: 'center',cellsalign: 'center', editable: false,datafield: 'tgl_lahir', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '12%'},
				{ text: 'Usia', align: 'center', cellsalign: 'center', editable: false, datafield: 'usia', columntype: 'textbox', filtertype: 'textbox', width: '12%'}
           ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid_peserta").jqxGrid('clearfilters');
		});
        
 		$('#btn-refresh-datapeserta').click(function () {
			$("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
		});

 		$('#btn_add_peserta').click(function () {
			add_peserta();
		});


		$("#btn-export-peserta").click(function(){
			var post = "";
			var filter = $("#jqxgrid_peserta").jqxGrid('getfilterinformation');
			for(i=0; i < filter.length; i++){
				var fltr 	= filter[i];
				var value	= fltr.filter.getfilters()[0].value;
				var condition	= fltr.filter.getfilters()[0].condition;
				var filteroperation	= fltr.filter.getfilters()[0].operation;
				var filterdatafield	= fltr.filtercolumn;
				
				post = post+'&filtervalue'+i+'='+value;
				post = post+'&filtercondition'+i+'='+condition;
				post = post+'&filteroperation'+i+'='+filteroperation;
				post = post+'&filterdatafield'+i+'='+filterdatafield;
				post = post+'&'+filterdatafield+'operator=and';
			}
			post = post+'&filterscount='+i;
			
			var sortdatafield = $("#jqxgrid_peserta").jqxGrid('getsortcolumn');
			if(sortdatafield != "" && sortdatafield != null){
				post = post + '&sortdatafield='+sortdatafield;
			}
			if(sortdatafield != null){
				var sortorder = $("#jqxgrid_peserta").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgrid").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
				post = post+'&sortorder='+sortorder;
			}
			
			post = post+'&kode={kode}';
			
			$.post("<?php echo base_url()?>eform/kegiatankelompok/pengadaan_detail_export",post  ,function(response){
				window.location.href=response;
			});
		});
		$("#tambahtjqxgrid_peserta").hide();
	});
	
	
	function add_peserta(){
		
		$.get("<?php echo base_url().'eform/kegiatankelompok/tab/1/'.$kode.'/'; ?>" , function(data) {
			$("#tambahtjqxgrid_peserta").show();
			$("#tambahtjqxgrid_peserta").html(data);
			$("#btn_add_peserta").hide();
			$("#btn-refresh-datapeserta").hide();
			$("#jqxgrid_peserta").hide();
			$("#btn-back-pesertadata").show();
		});
	}

	function del_peserta(id_data_kegiatan,no_kartu){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'eform/kegiatankelompok/dodelpermohonan/'; ?>" + id_data_kegiatan+'/'+no_kartu,  function(){
				alert('Data berhasil dihapus');

				$("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
				
			});
			
		}
	}

</script>

<div>
	<div style="width:100%;">
		
		<div style="padding:5px">
			<div class="row">
				<div class="col-md-4">
					<div class="box-header">
		          		<h3 class="box-title">Daftar Hadir Peserta</h3>
		     	 	</div>
		     	 </div>
	     	 	<div class="col-md-8">
	     	 		<div class="pull-right">
					<button type="button" class="btn btn-success" id="btn-refresh-datapeserta"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
					<button class="btn btn-danger" id='btn_add_peserta' type='button'><i class='fa fa-plus-square'></i> Tambah Peserta</button>
					<button type="button" id="btn-back-pesertadata" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
					</div>
				</div>
			</div>
		</div>
        <div id="jqxgrid_peserta"></div>
        <div id="tambahtjqxgrid_peserta"></div>
	</div>
</div>