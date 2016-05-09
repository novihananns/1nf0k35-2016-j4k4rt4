

<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="body">
  <div id='jqxWidget'>
      <div id='jqxTabs'>
          <ul>
              <li style="margin-left: 15px;">
                <div style="height: 20px; margin-top: 5px;">
                    <div style="float: left;">
                        <i class="glyphicon glyphicon-tasks" style="font-size: 18px"></i>
                    </div>
                    <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                        Setting BPJS</div>
                </div>
              </li>
              <li style="margin-left: 15px;">
                <div style="height: 20px; margin-top: 5px;">
                    <div style="float: left;">
                        <i class="glyphicon glyphicon-tasks" style="font-size: 18px"></i>
                    </div>
                    <div style="margin-left: 8px; vertical-align: middle; text-align: center; float: left;">
                        Setting General</div>
                </div>
              </li>
          </ul>
          <div id="content1" style="background: #FAFAFA"></div>
          <div id="content2" style="background: #FAFAFA"></div>
      </div>
  </div>
</div>

<script>
$(function () { 
    $('#jqxTabs').jqxTabs({ width: '100%', height: '700'});

    var loadPage = function (url, tabIndex) {
        $.get(url, function (data) {
            $('#content' + tabIndex).html(data);
        });
    }

    loadPage('<?php echo base_url()?>admin_config/tab/1/', 1);
    $('#jqxTabs').on('selected', function (event) {
        var pageIndex = event.args.item + 1;
        loadPage('<?php echo base_url()?>admin_config/tab/'+pageIndex+'/', pageIndex);
    });

        

    $("#menu_admin_config").addClass("active");
    $("#menu_admin_panel").addClass("active");
});
</script>
