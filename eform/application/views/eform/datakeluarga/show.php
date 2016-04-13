<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>mst/data_keluarga/dodel_multi" method="POST" name="">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
	      	<div class="row">
	      		<div class="col-md-12">
				 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>eform/data_kepala_keluarga/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
				 	<button type="button" class="btn btn-warning" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
				 	<button type="button" class="btn btn-success" id="btn-export"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>
				 </div>
			</div>
			<div class="box-body">
			<div class="row">
				 <div class="col-md-3">
				 	<label> Kecamatan </label>
				 	<select name="kecamatan" id="kecamatan" class="form-control">
						<?php foreach ($datakecamatan as $kec ) { ;?>
						<?php $select = $kec->code == substr($this->session->userdata('puskesmas'), 0,7)  ? 'selected=selected' : '' ?>
							<option value="<?php echo $kec->code; ?>" <?php echo $select ?>><?php echo $kec->nama; ?></option>
						<?php	} ;?>
			     	</select>
				 </div>
				 <div class="col-md-3">
				 <label> Kelurahan </label>
				 	<select name="kelurahan" id="kelurahan" class="form-control">
			     	</select>
				 </div>
				 <div class="col-md-3">
				 <label> Rukun Warga </label>
				 	<select name="rukunwarga" id="rukunwarga" class="form-control">
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

<div id="popup_kpldh" style="display:none">
	<div id="popup_title">Nomor Urut Keluarga</div>
	<div id="popup_content">&nbsp;</div>
</div>

<script type="text/javascript">
	$(function () {	
		$("#menu_ketuk_pintu").addClass("active");
		$("#menu_eform_data_kepala_keluarga").addClass("active");
	});
		
	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_data_keluarga', type: 'string'},
			{ name: 'tanggal_pengisian', type: 'date'},
			{ name: 'jam_data', type: 'string'},
			{ name: 'alamat', type: 'string'},
			{ name: 'id_propinsi', type: 'string'},
			{ name: 'id_kota', type: 'string'},
			{ name: 'id_kecamatan', type: 'string'},
			{ name: 'value', type: 'string'},
			{ name: 'rt', type: 'string'},
			{ name: 'rw', type: 'string'},
			{ name: 'norumah', type: 'string'},
			{ name: 'nourutkel', type: 'string'},
			{ name: 'id_kodepos', type: 'string'},
			{ name: 'namadesawisma', type: 'string'},
			{ name: 'id_pkk', type: 'string'},
			{ name: 'namakepalakeluarga', type: 'string'},
			{ name: 'nama_komunitas', type: 'string'},
			{ name: 'notlp', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('eform/data_kepala_keluarga/json'); ?>",
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
				{ text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding:4px;text-align:center' onclick='edit(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' ></a></div>";
					}else{
						return "<div style='width:100%;padding:4px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding:4px;text-align:center' onclick='del(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif'></a></div>";
					}else{
						return "<div style='width:100%;padding:4px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'No. Urut', datafield: 'nourutkel', align: 'center', columntype: 'textbox', filtertype: 'textbox', width: '6%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
					return "<div style='width:100%;padding:4px;padding-top:6px;text-align:center;font-weight:bold;' onclick='urut(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);' style='cursor:pointer;color:#a31919'>"+dataRecord.nourutkel+"</a></div>";
                 }
                },
                { text: 'Tgl Pengisian', datafield: 'tanggal_pengisian', columntype: 'textbox', align:'center', cellsalign:'center', filtertype: 'date',cellsformat: 'dd-MM-yyyy', width: '10%' },
				{ text: 'Kepala Keluarga', datafield: 'namakepalakeluarga', columntype: 'textbox', filtertype: 'textbox', width: '16%' },
				{ text: 'Desa', datafield: 'value', columntype: 'textbox', filtertype: 'textbox', width: '19%' },
				{ text: 'RT', datafield: 'rt', columntype: 'textbox', filtertype: 'textbox', width: '6%' },
				{ text: 'RW', datafield: 'rw', columntype: 'textbox', filtertype: 'textbox', width: '6%' },
				{ text: 'No. Rumah', datafield: 'norumah', columntype: 'textbox', filtertype: 'textbox', width: '8%' },
				{ text: 'Alamat', datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', width: '21%' }
			]
		});

	function urut(id){
		$("#popup_kpldh #popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'eform/data_kepala_keluarga/urut/';?>"+id, function(data) {
			$("#popup_content").html(data);
		});
		$("#popup_kpldh").jqxWindow({
			theme: theme, resizable: false,
			width: 460,
			height: 460,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup_kpldh").jqxWindow('open');
	}

	function edit(id){
		document.location.href="<?php echo base_url().'eform/data_kepala_keluarga/edit';?>/" + id;
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'eform/data_kepala_keluarga/dodel' ?>/" + id,  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

	function close_popup(){
        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		$("#popup_kpldh").jqxWindow('close');
	}

	$('#kecamatan').change(function(){
      var kecamatan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('eform/data_kepala_keluarga/get_kecamatanfilter') ?>',
        type : 'POST',
        data : 'kecamatan=' + kecamatan,
        success : function(data) {
          $('#kelurahan').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
    $('#kelurahan').change(function(){
      var kelurahan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('eform/data_kepala_keluarga/get_kelurahanfilter') ?>',
        type : 'POST',
        data : 'kelurahan=' + kelurahan,
        success : function(data) {
          $('#rukunwarga').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
    $('#rukunwarga').change(function(){
      var rukunwarga = $(this).val();
      var kelurahan = $("#kelurahan").val();
      $.ajax({
        url : '<?php echo site_url('eform/data_kepala_keluarga/get_rukunwargafilter') ?>',
        type : 'POST',
        data : 'rukunwarga=' + rukunwarga +'&kelurahan='+kelurahan,
        success : function(data) {
          $('#rukunrumahtangga').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
    $("#btn-export").click(function(){
		
		var post = "";
		var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
		for(i=0; i < filter.length; i++){
			var fltr 	= filter[i];
			var value	= fltr.filter.getfilters()[0].value;
			var condition	= fltr.filter.getfilters()[0].condition;
			var filteroperation	= fltr.filter.getfilters()[0].operation;
			var filterdatafield	= fltr.filtercolumn;
			if(filterdatafield=="tanggal_pengisian"){
				var d = new Date(value);
				var day = d.getDate();
				var month = d.getMonth();
				var year = d.getFullYear();
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
		post = post+'&kecamatan='+$("#kecamatan option:selected").text()+'&kelurahan='+$("#kelurahan option:selected").text()+'&rukunwarga='+$("#rukunwarga option:selected").text()+'&rukunrumahtangga='+$("#rukunrumahtangga option:selected").text();
		
		$.post("<?php echo base_url()?>eform/data_kepala_keluarga/datakepalakeluaraexport",post,function(response	){
			window.location.href=response;
		});
	});
</script>