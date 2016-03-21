<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" href="<?php echo base_url()?>public/themes/login/img/favicon.png">
    <style type="text/css">
      /* <![CDATA[ */    
        @import url(<?php echo base_url()?>public/themes/login/css/style.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.base.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.orange.css);
      /* ]]> */
    </style>
    <script src="<?php echo base_url()?>plugins/js/jquery-1.6.2.min.js"></script>
    <script src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcore.js"></script>
    <script src="<?php echo base_url()?>plugins/js/jqwidgets/jqxwindow.js"></script>
    <script src="<?php echo base_url()?>plugins/js/autocomplete.js"></script>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="skin-green layout-top-nav fixed">
<table class="login-bg" border="0" height="100%" width="100%">
<tbody><tr><td height="3%">&nbsp;</td></tr>
<tr><td align="center" height="20%">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
      <td width="10%">&nbsp;</td>
      <td style="padding-right:10px;" align="left">
        <table>
          <tbody><tr>
            <td><img src="<?php echo base_url()?>public/themes/login/img/31.png" width="80"></td>
            <td>
              <font style="font-family:Calibri;font-size:14pt;color:#FFFFFF;">Suku Dinas Kesehatan<br>
              {district}</font>
            </td>
          </tr>
        </tbody></table>
      </td>
      <td style="padding-right:10px;" align="center">
        <table>
          <tbody><tr>
            <td><img src="<?php echo base_url()?>public/themes/sik/dist/img/logo_white.png" width="150"></td>
          </tr>
        </tbody></table>
      </td>
    </tr>
  </tbody></table>
  </td>
</tr>
<tr><td valign="top">


<table id="Table_01" style="margin:0 auto;" align="center" border="0" cellpadding="0" cellspacing="0" height="250" width="400">
  <tbody><tr>
    <td class="head-login-table" align="center"><img src="<?php echo base_url()?>public/themes/login/img/login-pasien.png" height="80">
    </td>
  </tr>
  <tr>
    <td class="body-login-table">{content}</td>
  </tr>
  <tr>
    <td>
    </td>
  </tr>
</tbody></table>

</td></tr>
<tr><td style="font-family:Calibri;font-size:10pt;color:#FFFFFF;" align="center" height="10%">Powered by &nbsp;<a href="http://infokes.co.id/" style="text-decoration: none;color:white" target="_BLANK">PT Infokes Indonesia</a></td></tr>
<tr><td height="20%"> </td></tr>
</tbody></table>
<div class="ac_results" style="display: none; position: absolute;"></div>

  </body>
</html>
