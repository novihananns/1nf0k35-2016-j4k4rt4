
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
            <div class="col-md-3">
                Nama Koordinator 
            </div>
            <div class="col-md-9">
                : <?php echo $nama_koordinator=='null' ? 'Kosong' : ucfirst(str_replace("%20", " ",$nama_koordinator)) ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
                Nama Pendata
            </div>
            <div class="col-md-9">
                : <?php echo $nama_pendata=='null' ? 'Kosong' : ucfirst(str_replace("%20", " ", $nama_pendata))  ?>
            </div>
          </div>
      </div>
     
        <div class="box-body">
        <div class="div-grid">
            <div id="jqxgriddetail"></div>
      </div>
      </div>
    </div>
  </div>
  </div>
</form>
</section>


<script type="text/javascript">
   
     var sourcedetail = {
      datatype: "json",
      type  : "POST",
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
    url: "<?php echo base_url().'eform/laporanpendata/json_detailkk/' ?>{nama_koordinator}/{nama_pendata}",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgriddetail").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgriddetail").jqxGrid('updatebounddata', 'sort');
    },
    root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){   
      if (data != null){
        sourcedetail.totalrecords = data[0].TotalRows;          
      }
    }
    };    
    var dataadapterdetail = new $.jqx.dataAdapter(sourcedetail, {
      loadError: function(xhr, status, error){
        alert(error);
      }
    });
     
    $('#btn-refreshdatadetail').click(function () {
      $("#jqxgriddetail").jqxGrid('clearfilters');
    });

    $("#jqxgriddetail").jqxGrid({   
      width: '100%',
      selectionmode: 'singlerow',
      source: dataadapterdetail, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '50', '100', '200', '500'],
      showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
      rendergridrows: function(obj)
      {
        return obj.data;    
      },
      columns: [
        { text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgriddetail").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding:4px;text-align:center' onclick='editdetail(\""+dataRecord.id_data_keluarga+"\");'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' ></a></div>";
          }else{
            return "<div style='width:100%;padding:4px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
       
        { text: 'Kepala Keluarga', datafield: 'namakepalakeluarga', columntype: 'textbox', filtertype: 'textbox', width: '30%' },
        { text: 'Desa', datafield: 'value', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
        { text: 'RT', datafield: 'rt', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
        { text: 'RW', datafield: 'rw', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
        { text: 'Alamat', datafield: 'alamat', columntype: 'textbox', filtertype: 'textbox', width: '20%' }
      ]
    });


  function editdetail(id){
    document.location.href="<?php echo base_url().'eform/data_kepala_keluarga/edit';?>/" + id;
  }

</script>