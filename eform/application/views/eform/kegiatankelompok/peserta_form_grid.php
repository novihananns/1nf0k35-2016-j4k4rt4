
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>inventory/pengadaanbarang/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
      <div class="box-footer">
      <button type="button" id="btn-back-pesertadata" class="btn btn-warning"><i class='fa fa-reply'></i> &nbsp; Kembali</button>
        <button type="button" class="btn btn-primary" id="btn-refresh-pesertabpjs"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
        <button type="button" onclick="doList()" class="btn btn-success" id="btn-success"><i class='fa fa-sign-in'></i> &nbsp; Pilih </button>
      </div>
        <div class="box-body">
        <div class="div-grid">
            <div id="jqxgridPesertaBPJS"></div>
      </div>
      </div>
    </div>
  </div>
  </div>
</form>
</section>

<script type="text/javascript">
  $(function () { 
    
      $("#menu_kegiatan_kelompok").addClass("active");
      $("#menu_kegiatankelompok").addClass("active");
    
  });

     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'no_kartu', type: 'string' },
      { name: 'id_data_kegiatan', type: 'string' },
      { name: 'nama', type: 'string' },
      { name: 'bpjs', type: 'string' },
      { name: 'id_pilihan_kelamin', type: 'string' },
      { name: 'tgl_lahir', type: 'date' },
      { name: 'tgl_lahirdata', type: 'string' },
      { name: 'usia', type: 'string' },
      { name: 'jeniskelamin', type: 'string' },
      { name: 'jenis_peserta', type: 'string' },
        ],
    url: "<?php echo site_url('eform/kegiatankelompok/json_pesertabpjs'); ?>",    
    cache: false,
    updateRow: function (rowID, rowData, commit) {
      
    },
    filter: function(){
      $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'sort');
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
     
    $('#btn-refresh-pesertabpjs').click(function () {
      $("#jqxgridPesertaBPJS").jqxGrid('clearfilters');
    });

    $("#jqxgridPesertaBPJS").jqxGrid(
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
        { text: 'Pilih', align: 'center', filtertype: 'none', sortable: false, width: '4%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgridPesertaBPJS").jqxGrid('getrowdata', row);
          return "<div style='width:100%;padding-top:2px;text-align:center'><input type='checkbox' name='aset[]' value="+dataRecord.bpjs+" ></div>";
                 }
                },
        { text: 'No Kartu', align: 'center',cellsalign: 'center',editable: false, datafield: 'bpjs', columntype: 'textbox', filtertype: 'textbox', width: '22%' },
        { text: 'Nama Peserta ', editable: false,datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '23%'},
        { text: 'Jenis Kelamin ', editable: false,datafield: 'jeniskelamin', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
        { text: 'Jenis Peserta ', align: 'center',cellsalign: 'center',editable: false,datafield: 'jenis_peserta', columntype: 'textbox', filtertype: 'textbox', width: '15%'},
        { text: 'Tanggal Lahir',align: 'center',cellsalign: 'center', editable: false,datafield: 'tgl_lahir', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '12%'},
        { text: 'Usia', align: 'center',cellsalign: 'right',editable: false, datafield: 'usia', columntype: 'textbox', filtertype: 'textbox', width: '13%'}
            ]
    });
    
  

  function add_pesertabpjs(data_pesertabpjs){
    
    $.get("<?php echo base_url().'eform/kegiatankelompok/add_pesertabpjs/'.$kode; ?>"+data_pesertabpjs ,  function(data) {
        res = data.split('|');
        if (res[0]=='OK') {
            $("#tambahtjqxgrid_peserta").hide();
            $("#btn_add_peserta").show();
            $("#jqxgrid_peserta").show();
            $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
        }else{
          alert(res[1]);
        }
          
    });
  }
  
  function doList(){        
    var values = new Array(); 
    var data_pesertabpjs = "/";
    $.each($("input[name='aset[]']:checked"), function() {
        values.push($(this).val());   
    });
    //alert(values);
    
    if(values.length > 0){
      for(i=0; i<values.length; i++){
        data_pesertabpjs = data_pesertabpjs+values[i]+"_tr_";
      }
      add_pesertabpjs(data_pesertabpjs);
    }else{
      alert('Silahkan Pilih Barang Terlebih Dahulu');
    }
    //alert(data_pesertabpjs); 
  }
        $('#btn-back-pesertadata').click(function(){
            $("#tambahtjqxgrid_peserta").hide();
            $("#jqxgrid_peserta").show();
            $("#btn_add_peserta").show();
            $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
      });
</script>