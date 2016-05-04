<!-- JS & CSS for Galley Photo -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>plugins/js/image_crud/image_crud/css/fineuploader.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>plugins/js/image_crud/image_crud/css/photogallery.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>plugins/js/image_crud/image_crud/css/colorbox.css" />
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/jquery-ui-1.9.0.custom.min.js"></script>
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/fineuploader-3.2.min.js"></script>
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/jquery.colorbox-min.js"></script>
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_back,#btn_back2').click(function(){
            window.location.href="<?php echo base_url()?>puskesmas";
        });

        $('#btn_updateProfile').click(function(){
          $('#notification').hide();
          $.ajax({ 
            type: "POST",
            url: "<?php echo base_url()?>mst/kabupatenkota/update_kota/{code}",
            data: $('#updateProfile').serialize(),
            success: function(response){
              $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
              $('#notification').show('slow');
              $('#notification span').html(response);
                  $('html, body').animate({
                      scrollTop: $("#top").offset().top
                  }, 300);
            }
           });    
        });
    });
</script>

 <div id="notification">
</div>
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div class="row" style="background:#FAFAFA">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab">Kabupaten/Kota</a></li>      
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">    
        <!-- <form action="<?php echo base_url()?>disbun/profile_doupdate" method="post"> -->
        <form name="updateProfile" id="updateProfile">
        <div class="row">
        <div class="col-md-6 col-md-offset-1">
             <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-qrcode" style="width:20px"></i>
              </span>
              <input type="text" class="form-control" placeholder="**Kode" name="code" readonly value="<?php 
                      if(set_value('code')=="" && isset($code)){
                        echo $code;
                      }else{
                        echo  set_value('code');
                      }
                      ?>"/>
            </div>
            <br>
                         <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-road" style="width:20px"></i>
              </span>
              <input type="text" class="form-control" placeholder="Nama" name="value" value="<?php 
                      if(set_value('value')=="" && isset($value)){
                        echo $value;
                      }else{
                        echo  set_value('value');
                      }
                      ?>"/>
            </div>
           
            <br>
            <div class="row">
            </div>
        </div>
          <div class="col-xs-2 col-md-offset-1">
            <button type="button" id="btn_updateProfile" class="btn btn-warning btn-block btn-flat">Simpan</button>
            <button type="button" id="btn_back" class="btn btn-primary btn-block btn-flat">Kembali</button>
          </div><!-- /.col -->
        </div>
        </form>        
      </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script type="text/javascript">
$(function () { 
    $("#menu_mst_kabupatenkota").addClass("active");
    $("#menu_master_data").addClass("active");
  });
</script>
