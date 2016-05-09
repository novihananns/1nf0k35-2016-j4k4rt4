<div class="body">
        <div class="box-body">
          <div class="form-group">
            <div class="row">
                <div class="col-md-5">
                    <label>Puskesmas</label>  
                </div>
                <div class="col-md-7">
                    <label>: {namapuskes}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label>Server</label>  
                </div>
                <div class="col-md-7">
                    <label>: {server}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label>Username</label>  
                </div>
                <div class="col-md-7">
                    <label>: {username}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label>Password</label>  
                </div>
                <div class="col-md-7">
                    <label>: {password}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label>Cons ID</label>  
                </div>
                <div class="col-md-7">
                    <label>: {consid}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label>Secret Key</label>  
                </div>
                <div class="col-md-7">
                    <label>: {secretkey}</label>
                </div>
            </div>
          </div>
        <div class="box-footer">
          <div style="float: right;">
            <button type="button" id="btn-close" class="btn btn-warning">Close</button>
          </div>
        </div>
        </div>
</div>
<script type="text/javascript">
    $('#btn-close').click(function(){
      close_popup();
    }); 
</script>