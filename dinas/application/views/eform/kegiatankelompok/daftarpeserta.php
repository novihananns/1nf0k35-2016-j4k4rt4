
<div class="div-grid">
    <div id="jqxgriddatapesertalengkap"></div>
</div>
<script type="text/javascript">
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
			$("#jqxgriddatapesertalengkap").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgriddatapesertalengkap").jqxGrid('updatebounddata', 'sort');
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
			$("#jqxgriddatapesertalengkap").jqxGrid('clearfilters');
		});

		$("#jqxgriddatapesertalengkap").jqxGrid(
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
				// { text: 'Pilih', align: 'center', filtertype: 'none', sortable: false, width: '7%', cellsrenderer: function (row) {
				//     var dataRecord = $("#jqxgriddatapesertalengkap").jqxGrid('getrowdata', row);
				//     if(dataRecord.edit==1){
				// 		return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='edit(\""+dataRecord.id_data_kegiatan+"\");'></a></div>";
				// 	}else{
				// 		return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
				// 	}
    //              }
    //             },
				{ text: 'Kegiatan', editable:false , columntype: 'textbox', filtertype: 'none', width: '20%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgriddatapesertalengkap").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:4px;padding-top:6px;text-align:left;font-weight:bold;' onclick='edit(\""+dataRecord.id_data_kegiatan+"\");'><a href='javascript:void(0);' style='cursor:pointer;'>"+dataRecord.kegiatan+"</a></div>";
                 } },
				{ text: 'Pelaksanaan',editable:false , align: 'center', cellsalign: 'center', datafield: 'tgl', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '15%' },
				{ text: 'Club Prolanis', editable:false ,align: 'center', datafield: 'kode_club', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
				{ text: 'Materi', editable:false ,align: 'center', datafield: 'materi', columntype: 'textbox', filtertype: 'textbox', width: '38%' }
            ]
		});

	function edit(id){
		document.location.href="<?php echo base_url().'eform/kegiatankelompok/edit';?>/" + id ;
	}

</script>