<section class="content">
<form action="<?php echo base_url()?>inventory/pengadaanbarang/dodel_multi" method="POST" name="">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
      <div class="box-footer">
        <button type="button" class="btn btn-primary" id="btn-refresh-pesertabpjs"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
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
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id_data_kegiatan', type: 'string' },
      { name: 'nama', type: 'string' },
      { name: 'bpjs', type: 'string' },
      { name: 'id_pilihan_kelamin', type: 'string' },
      { name: 'tgl_lahir', type: 'date' },
      { name: 'tgl_lahirdata', type: 'string' },
      { name: 'usia', type: 'string' },
      { name: 'jeniskelamin', type: 'string' },
      { name: 'nik', type: 'string' },
      { name: 'no_kartu', type: 'string' },
      { name: 'ceklis', type: 'number' },
        ],
    url: "<?php echo base_url().'eform/kegiatankelompok/json_pesertabpjs/'.$kode; ?>",    
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
        { text: 'Pilih',filtertype: 'none', align:'center', datafield: 'no_kartu', columntype: 'checkbox', width: '8%' },
        { text: 'No Kartu', align: 'center',cellsalign: 'center',editable: false, datafield: 'bpjs', columntype: 'textbox', filtertype: 'textbox', width: '18%' },
        { text: 'NIK', align: 'center',cellsalign: 'center',editable: false,datafield: 'nik', columntype: 'textbox', filtertype: 'textbox', width: '19%'},
        { text: 'Nama Peserta ', editable: false,datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '23%'},
        { text: 'Jenis Kelamin ', align: 'center',cellsalign: 'center',editable: false,datafield: 'jeniskelamin', columntype: 'textbox', filtertype: 'textbox', width: '11%'},
        { text: 'Tanggal Lahir',align: 'center',cellsalign: 'center', editable: false,datafield: 'tgl_lahir', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '12%'},
        { text: 'Usia', align: 'center',cellsalign: 'center',editable: false, datafield: 'usia', columntype: 'textbox', filtertype: 'textbox', width: '9%'}
            ]
    });
    
    $("#jqxgridPesertaBPJS").bind('cellendedit', function (event) {
       
      var datarow = $("#jqxgridPesertaBPJS").jqxGrid('getrowdata', event.args.rowindex);
      var bpjsdata = datarow.bpjs;
      if(bpjsdata.length==13){
        $.get("<?php echo base_url()?>eform/kegiatankelompok/bpjs_search/bpjs/"+bpjsdata,function(res){
            if(res.metaData.code=="200"){
              if(confirm("Nomor terdaftar sebagai peserta \nBPJS "+res.response.kdProviderPst.nmProvider+", \nGunakan data?")){
                $.get("<?php echo base_url().'eform/kegiatankelompok/addpesetabpjsterdaftar/'.$kode; ?>/"+datarow.bpjs ,function(databp){
                    var resd = databp.split("|");
                    if (resd[0]=="OK") {

                    }else{

                    }
                    $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'cells');
                });
              }else{
                $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'cells');
              }
            }else{
              alert("Peserta tidak terdaftar sebagai angota BPJS");
              $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'cells');
            }
        },"json");
      }else{
        alert("Pastikan Nomor BPJS Berjumalh 13 digit");
        $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'cells');
      }
      
   });

  function add_pesertabpjs(data_pesertabpjs){
    
    $.get("<?php echo base_url().'eform/kegiatankelompok/add_pesertabpjs/'.$kode; ?>"+data_pesertabpjs ,  function(data) {
        res = data.split('|');
        if (res[0]=='OK') {
            // $("#tambahtjqxgrid_peserta").hide();
            // $("#btn_add_peserta").show();
            // $("#btn-refresh-datapeserta").show();
            // $("#jqxgrid_peserta").show();
            // $("#jqxgrid_peserta").jqxGrid('updatebounddata', 'cells');
            $("#jqxgridPesertaBPJS").jqxGrid('updatebounddata', 'cells');
        }else{
          alert(res[1]);
        }
          
    });
  }
  
  function doList(){        
    // var values = new Array(); 
    // var data_pesertabpjs = "/";
    // $.each($("input[name='aset[]']:checked"), function() {
    //     values.push($(this).val());   
    // });
    // //alert(values);
    
    // if(values.length > 0){
    //   for(i=0; i<values.length; i++){
    //     data_pesertabpjs = data_pesertabpjs+values[i]+"_tr_";
    //   }
    //   add_pesertabpjs(data_pesertabpjs);
    // }else{
    //   alert('Silahkan Pilih Barang Terlebih Dahulu');
    // }
    //alert(data_pesertabpjs); 
    var data = $("input[name='pesertaceklis']:checked").val();
    alert(data);
  }
        
</script>