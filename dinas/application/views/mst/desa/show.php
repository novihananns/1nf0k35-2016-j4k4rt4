<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>mst/desa/dodel_multi" method="POST" name="">
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
            <div class="col-md-12">
            <?php if(isset($unlock) && $unlock!=1){
            ?>
          <button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>mst/desa/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
        <?php
            }
        ?>
          <button type="button" class="btn btn-warning" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
          <button type="button" class="btn btn-success" id="btn-export"><i class='fa fa-file-excel-o'></i> &nbsp; Export</button>
          <button type="submit" class="btn btn-danger" onClick="if(!confirm('Hapus semua data yang dipilih?'))return false;">Hapus</button>
         </div>
      </div>
      <div class="box-body">
      <?php
      //  echo $this->session->userdata("filter_code_kecamatan")." || kel";
      //  echo $this->session->userdata("filter_code_kelurahan");
      ?>
      <div class="row">
        <div class="col-md-3">
         <!--<<label> Rukun Rumah Tangga </label>
          select name="rukunrumahtangga" id="rukunrumahtangga" class="form-control">
            </select>-->
         </div>
         <div class="col-md-3">
          <label> Provinsi </label> <?php echo $this->session->userdata('filter_code_provinsi');?>
          <select name="provinsi" id="provinsi" class="form-control">
            <option value="">Seluruh Provinsi</option>
            <?php foreach ($dataprovinsi as $prov ) { ;?>
            <?php $select = $prov->code == $this->session->userdata('filter_code_provinsi')  ? 'selected=selected' : '' ?>
              <option value="<?php echo $prov->code; ?>" <?php echo $select ?>><?php echo $prov->value; ?></option>
            <?php } ;?>
            </select>
         </div>
         <div class="col-md-3">
         <label> Kota </label>
          <select name="kota" id="kota" class="form-control">
            </select>
         </div>
         <div class="col-md-3">
         <label> Kecamatan </label>
          <select name="kecamatan" id="kecamatan" class="form-control">
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
    $("#menu_mst_desa").addClass("active");
    $("#menu_master_data").addClass("active");
  });
  
    var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'code', type: 'string'},
      { name: 'value', type: 'string'},
      { name: 'edit', type: 'number'},
      { name: 'Cek', type: 'number'},
      { name: 'hapus', type: 'number'}
        ],
    url: "<?php echo site_url('mst/desa/json'); ?>",
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
        { text: 'Cek', align: 'center', filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            if(dataRecord.Cek==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><input type='checkbox' name='code[]' value='"+dataRecord.code+"'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
                  { text: 'Hapus', align: 'center', filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            if(dataRecord.hapus==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='hapus(\""+dataRecord.code+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
                
                { text: 'Edit', align: 'center', filtertype: 'none', sortable: false, width: '10%', cellsrenderer: function (row) {
            var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
            if(dataRecord.edit==1){
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_edit.gif' onclick='edit(\""+dataRecord.code+"\");'></a></div>";
          }else{
            return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
          }
                 }
                },
                { text: 'Code', datafield: 'code', columntype: 'textbox', filtertype: 'textbox', width: '20%' },
                { text: 'Nama / Asal', datafield: 'value', columntype: 'textbox', align:'left', filtertype: 'textbox',width: '50%' },
      ]
    });

  function edit(code){
    document.location.href="<?php echo base_url().'mst/desa/edit';?>/" + code;
  }
 
 function hapus(code){
    var confirms = confirm("Hapus Data ?");
    if(confirms == true){
      $.post("<?php echo base_url().'mst/desa/dodel' ?>/" + code,  function(){
        $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
      });
    }
  }


  $('#provinsi').change(function(){
      var provinsi = $(this).val();
      $.ajax({
        url : '<?php echo site_url('mst/desa/get_provinsifilter') ?>',
        type : 'POST',
        data : 'provinsi=' + provinsi,
        success : function(data) {
          $('#kota').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
  }).change();
  
    $('#kota').change(function(){
      var kota = $(this).val();
      $.ajax({
        url : '<?php echo site_url('mst/desa/get_kotafilter') ?>',
        type : 'POST',
        data : 'kota=' + kota,
        success : function(data) {
          $('#kecamatan').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
    $('#kecamatan').change(function(){
      var kecamatan = $(this).val();
      $.ajax({
        url : '<?php echo site_url('mst/desa/get_kecamatanfilter') ?>',
        type : 'POST',
        data : 'kecamatan=' + kecamatan,
        success : function(data) {
          $('#kelurahan').html(data);
          $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
        }
      });

      return false;
    }).change();
    $("#btn-export").click(function(){
    
    var post = "";
    var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
    for(i=0; i < filter.length; i++){
      var fltr  = filter[i];
      var value = fltr.filter.getfilters()[0].value;
      var condition = fltr.filter.getfilters()[0].condition;
      var filteroperation = fltr.filter.getfilters()[0].operation;
      var filterdatafield = fltr.filtercolumn;
      if(filterdatafield=="tanggal_pengisian"){
        var d = new Date(value);
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        value = year+'-'+month+'-'+day;
        
      }
      //alert(value);
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
    post = post+'&provinsi='+$("#provinsi option:selected").text()+'&kota='+$("#kota option:selected").text()+'&kecamatan='+$("#kecamatan option:selected").text()+'&kelurahan='+$("#kelurahan option:selected").text();
    
    $.post("<?php echo base_url()?>mst/desa/datakepalakeluaraexport",post,function(response  ){
      //alert(response);
      window.location.href=response;
    });
  });
</script>