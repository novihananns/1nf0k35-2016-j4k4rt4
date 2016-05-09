<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>mst/puskesmas/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
      </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-6 col-xs-4">
            <button type="button" class="btn btn-warning" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
           </div>
           <div class="col-md-4 col-xs-8 pull-right">
            <select name="kecamatan" id="kecamatan" class="form-control"></select>
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
    var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'code', type: 'string'},
      { name: 'value', type: 'string'},
      { name: 'secretkey', type: 'number'},
      { name: 'consid', type: 'number'},     
      { name: 'password', type: 'number'},
      { name: 'username', type: 'number'},
      { name: 'edit', type: 'number'},
      { name: 'getsetting', type: 'number'}
        ],
    url: "<?php echo site_url('admin_config/json'); ?>",
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
        { text: 'Get', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
          var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
          return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/return.png' onclick='getsetting(\""+dataRecord.code+"\");'></a></div>";
         }
        },
        { text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '8%', cellsrenderer: function (row) {
          var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
          if(dataRecord.edit==1){
          return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.code+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
         }
        },
        { text: 'Code', datafield: 'code', columntype: 'textbox', filtertype: 'textbox', width: '18%' },
        { text: 'Nama', datafield: 'value', columntype: 'textbox', align:'left', filtertype: 'textbox',width: '26%' },
        { text: 'Cons ID', align: 'center', columntype: 'textbox', filtertype: 'none', width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            return "<div style='width:100%;padding-top:2px;text-align:center'><input type='checkbox' "+ (dataRecord.consid==1 ? "checked":"") +" ></div>";
         }
        },
        { text: 'Secret Key', align: 'center', columntype: 'textbox', filtertype: 'none', width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            return "<div style='width:100%;padding-top:2px;text-align:center'><input type='checkbox' "+ (dataRecord.secretkey==1 ? "checked":"") +"></div>";
         }
        },
        { text: 'Username', align: 'center', columntype: 'textbox', filtertype: 'none', width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            return "<div style='width:100%;padding-top:2px;text-align:center'><input type='checkbox' "+ (dataRecord.username==1 ? "checked":"") +"></div>";
         }
        },
        { text: 'Password', align: 'center', columntype: 'textbox', filtertype: 'none', width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            return "<div style='width:100%;padding-top:2px;text-align:center'><input type='checkbox' "+ (dataRecord.password==1 ? "checked":"") +"></div>";
         }
        },

      ]
    });

  function edit(code){


  }

  function getsetting(code){
    if(confirm("Cek ulang setting BPJS ?")){
      $.post("<?php echo base_url().'admin_config/checkBPJS' ?>/" + code,  function(res){
        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
      },"json");
    }
  }

  $.ajax({
    url : '<?php echo site_url('mst/puskesmas/get_kotafilter') ?>',
    type : 'POST',
    data : 'kota={kode_kota}' ,
    success : function(data) {
      $('#kecamatan').html(data);
      $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
    }
  });

  $('#kecamatan').change(function(){
    var kecamatan = $(this).val();
    $.ajax({
      url : '<?php echo site_url('mst/puskesmas/get_kecamatanfilter') ?>',
      type : 'POST',
      data : 'kecamatan=' + kecamatan,
      success : function(data) {
        $('#kelurahan').html(data);
        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
      }
    });

    return false;
  });

    $("#menu_admin_config").addClass("active");
    $("#menu_admin_panel").addClass("active");
</script>