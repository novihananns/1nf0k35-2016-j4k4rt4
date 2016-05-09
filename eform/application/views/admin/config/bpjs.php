
<?php if($alert_form!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $alert_form;?>
</div>
<?php } ?>
<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>
<section class="content">
<form method="POST" name="frmUsers">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

        <!-- form start -->
          <div class="box-body" style="float:right">
            <button id="getbpjs" type="button" class="btn btn-success">Get Config BPJS</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Puskesmas</label>
              <select  name="codepus" id="codepus" class="form-control">
                  <?php foreach($kodepuskesmas as $pus) : ?>
                    <?php $select = $pus->code == $code ? 'selected' : '' ?>
                    <option value="<?php echo $pus->code ?>" <?php echo $select ?>><?php echo $pus->value ?></option>
                  <?php endforeach ?>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Server</label>
              <input type="text" class="form-control" name="serverbpjs" id="serverbpjs" placeholder="Server" value="<?php 
            if(set_value('serverbpjs')=="" && isset($server)){
              echo $server;
            }else{
              echo  set_value('serverbpjs');
            }
            ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Username</label>
              <input type="text" class="form-control" name="usernamebpjs" id="usernamebpjs" placeholder="Username" value="<?php 
            if(set_value('usernamebpjs')=="" && isset($username)){
              echo $username;
            }else{
              echo  set_value('usernamebpjs');
            }
            ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Password</label>
              <input type="password" class="form-control" name="passwordbpjs"  id="passwordbpjs" placeholder="Password" value="<?php 
            if(set_value('passwordbpjs')=="" && isset($password)){
              echo $password;
            }else{
              echo  set_value('passwordbpjs');
            }
            ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Cons ID</label>
              <input type="text" class="form-control" name="considbpjs" id="considbpjs" placeholder="Cons ID" value="<?php 
            if(set_value('considbpjs')=="" && isset($consid)){
              echo $consid;
            }else{
              echo  set_value('considbpjs');
            }
            ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Secret Key</label>
              <input type="text" class="form-control" name="keybpjs" id="keybpjs" placeholder="Secret Key" value="<?php 
            if(set_value('keybpjs')=="" && isset($secretkey)){
              echo $secretkey;
            }else{
              echo  set_value('keybpjs');
            }
            ?>">
            </div>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <div style="float: right;">
            <button type="button" id="btn-adds" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Reset</button>
            </div>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
		$("#menu_admin_config").addClass("active");
		$("#menu_admin_panel").addClass("active");

   
    function saved (){
      var data = new FormData();
        $('#biodata_notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
        $('#biodata_notice').show();

        data.append('codepus', $("#codepus").val());
        data.append('serverbpjs', $("[name='serverbpjs']").val());
        data.append('usernamebpjs', $("[name='usernamebpjs']").val());
        data.append('passwordbpjs', $("[name='passwordbpjs']").val());
        data.append('considbpjs', $("[name='considbpjs']").val());
        data.append('keybpjs', $("[name='keybpjs']").val());

        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()?>admin_config/insertdata',
            data : data,
            success : function(response){
                $('#content1').html(response);
            }
        });

        return false;
    }
  $('#btn-adds').click(function(){
     saved();
  });
  $('#getbpjs').click(function(){
    var code = "<?php echo 'P'.$this->session->userdata('puskesmas');?>";
      if(confirm("Mengambil setting BPJS ?")){
        $.post("<?php echo base_url().'admin_config/checkBPJS' ?>/" + code,  function(res){
          if (res.code!='kosong') {
              $("#serverbpjs").val(res.server);
              $("#usernamebpjs").val(res.username);
              $("#passwordbpjs").val(res.password);
              $("#considbpjs").val(res.consid);
              $("#keybpjs").val(res.secretkey);
          }else{
            alert('Maaf! data tidak tersedia');
          }
        },"json");
      }
  });
});
</script>