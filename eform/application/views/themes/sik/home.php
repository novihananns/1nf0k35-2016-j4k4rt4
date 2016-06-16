<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <style type="text/css">
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/bootstrap.min.css);
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/font-awesome.min.css);
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/ionicons.min.css);
        @import url(<?php echo base_url()?>public/themes/sik/plugins/datatables/dataTables.bootstrap.css);
        @import url(<?php echo base_url()?>public/themes/sik/dist/css/sik.css);
        @import url(<?php echo base_url()?>public/themes/sik/dist/css/skins/skin-green.min.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.base.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.bootstrap.css);
    </style>
	
    <script src="<?php echo base_url()?>plugins/js/jQuery-2.1.3.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxtooltip.js"></script>
	
	  <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdatatable.js"></script>
	  <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxtreegrid.js"></script>
	
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.selection.js"></script>    
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.edit.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.filter.js"></script>   
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.sort.js"></script>     
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.pager.js"></script>        
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.columnsresize.js"></script>        
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/globalization/globalize.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxmaskedinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxnavigationbar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcombobox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcheckbox.js"></script>
	 
    <script type="text/javascript">
        var theme = "bootstrap";
    </script>
  </head>
  <body class="skin-green sidebar-mini wysihtml5-supported">

    <div class="wrapper">
    <div id="top">
      <img id="logo_pus" src="<?php echo base_url()?>public/themes/sik/dist/img/logo.gif">
      <div id="pus_name">PUSKESMAS {puskesmas}<br>Dinas Kesehatan {district}</div>
      <img id="logo_epus" class="hidden-xs" src="<?php echo base_url()?>public/themes/sik/dist/img/epuskesmas2.png">
    </div>
      
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url()?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>M</b>O</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Manajemen</b> Organisasi</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>


          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i>
                  <span class="hidden-xs"><?php echo ucwords($this->session-> userdata('username'));?></span>
                </a>
                <ul class="dropdown-menu">
                  {login}
                </ul>
              </li>
              <li class="dropdown notifications-menu">
                <a href="<?php echo base_url()."morganisasi/logout" ?>">
                  <i class="fa fa-sign-out"></i>
                  <span class="hidden-xs">Keluar</span>
                </a>
              </li>
            </ul>
          </div>

          <div class="hidden-md hidden-lg hidden-sm">
            <a href="<?php echo base_url()."morganisasi/logout" ?>" class="sidebar-link">
              <i class="fa fa-sign-out"></i>
              <span>Keluar</span>
            </a>
            <a href="<?php echo base_url()."morganisasi/profile" ?>" class="sidebar-link">
              <i class="fa fa-user"></i>
              <span><?php echo ucwords($this->session-> userdata('username'));?></span>
            </a>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <?php /* 
		  <ul class="sidebar-menu">
            <li id="menu_dashboard" class="treeview">
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_dashboard_home"><a href="<?php echo base_url()?>"><i class="fa fa-circle-o"></i> Home</a></li>
                <li id="menu_dashboard_profile"><a href="<?php echo base_url()?>morganisasi/profile"><i class="fa fa-circle-o"></i> Profile</a></li>
              </ul>
            </li>
            <?php if($this->session->userdata('level')=="administrator" || $this->session->userdata('level')=="super administrator"){ ?>
            <li id="menu_kepegawaian" class="treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Kepegawaian</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_chart_penangkar"><a href="<?php echo base_url()?>"><i class="fa fa-circle-o"></i> Daftar Riwayat Hidup</a></li>
              </ul>
            </li>
            <li id="menu_keuangan" class="treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>Keuangan</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_chart_penangkar"><a href="<?php echo base_url()?>"><i class="fa fa-circle-o"></i> BKU</a></li>
              </ul>
            </li>
            <li id="menu_inventory" class="treeview">
              <a href="#">
                <i class="fa fa-inbox"></i>
                <span>Inventory</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_chart_penangkar"><a href="<?php echo base_url()?>"><i class="fa fa-circle-o"></i> Aset</a></li>
              </ul>
            </li>
            <li id="menu_sms" class="treeview">
              <a href="#">
                <i class="fa fa fa-envelope"></i>
                <span>SMS Gateway</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_sms_dashboard"><a href="<?php echo base_url()?>sms/home"><i class="fa fa-circle-o"></i> Dashboard</a></li>
                <li id="menu_sms_inbox"><a href="<?php echo base_url()?>sms/inbox"><i class="fa fa-circle-o"></i> Inbox</a></li>
                <li id="menu_sms_phonebook"><a href="<?php echo base_url()?>sms/pbk"><i class="fa fa-circle-o"></i> Phonebook</a></li>
                <li id="menu_sms_group"><a href="<?php echo base_url()?>sms/group"><i class="fa fa-circle-o"></i> Group</a></li>
                <li id="menu_sms_autoreply"><a href="<?php echo base_url()?>sms/autoreply"><i class="fa fa-circle-o"></i> Autoreply</a></li>
                <li id="menu_sms_schedule"><a href="<?php echo base_url()?>sms/shcedule"><i class="fa fa-circle-o"></i> Schedule</a></li>
              </ul>
            </li>
            <li id="menu_laporan" class="treeview">
              <a href="#">
                <i class="fa fa-building-o"></i>
                <span>Laporan</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_lap_pegawai"><a href="<?php echo base_url()?>lap_penangkar"><i class="fa fa-circle-o"></i>Daftar Pegawai</a></li>
              </ul>
            </li>
            <li  id="menu_admin" class="treeview">
              <a href="#">
                <i class="fa fa-cogs"></i>
                <span>Admin Panel</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_admin_config"><a href="<?php echo base_url()?>admin_config"><i class="fa fa-circle-o"></i> Configuration</a></li>
                <li id="menu_admin_user"><a href="<?php echo base_url()?>admin_user"><i class="fa fa-circle-o"></i> User Management</a></li>
                <li id="menu_admin_file"><a href="<?php echo base_url()?>admin_file"><i class="fa fa-circle-o"></i> File Management</a></li>
                <li id="menu_admin_role"><a href="<?php echo base_url()?>admin_role"><i class="fa fa-circle-o"></i> Group Role</a></li>
                <li id="menu_admin_menu"><a href="<?php echo base_url()?>admin_menu"><i class="fa fa-circle-o"></i> Menu Management</a></li>
                <li id="menu_admin_config"><a href="<?php echo base_url()?>sms_setting"><i class="fa fa-circle-o"></i> SMS Setting</a></li>
              </ul>
            </li>
            <li id="menu_parameter" class="treeview">
              <a href="#">
                <i class="fa fa-archive"></i>
                <span>Master Data</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="menu_mst_puskesmas"><a href="<?php echo base_url()?>mst/puskesmas"><i class="fa fa-circle-o"></i> Puskesmas</a></li>
                <li id="menu_mst_agama"><a href="<?php echo base_url()?>mst/agama"><i class="fa fa-circle-o"></i> Agama</a></li>
                <li id="menu_mst_desa"><a href="<?php echo base_url()?>mst/desa"><i class="fa fa-circle-o"></i> Desa / Kelurahan</a></li>
                <li id="menu_mst_kabupatenkota"><a href="<?php echo base_url()?>mst/kabupatenkota"><i class="fa fa-circle-o"></i> Kota / Kabupaten</a></li>
                <li id="menu_mst_kecamatan"><a href="<?php echo base_url()?>mst/kecamatan"><i class="fa fa-circle-o"></i> Kecamatan</a></li>
                <li id="menu_mst_provinsi"><a href="<?php echo base_url()?>mst/provinsi"><i class="fa fa-circle-o"></i> Porvinsi</a></li>
<<<<<<< HEAD
                <li id="menu_mst_inv_ruangan"><a href="<?php echo base_url()?>mst/inv_ruangan"><i class="fa fa-circle-o"></i> Inventory Ruangan</a></li>
=======
                <li id="menu_mst_invbarang"><a href="<?php echo base_url()?>mst/invbarang"><i class="fa fa-circle-o"></i> Inv Barang</a></li>
                <li id="menu_mst_invkondisibarang"><a href="<?php echo base_url()?>mst/invkondisibarang"><i class="fa fa-circle-o"></i> Inv Kondisi Barang</a></li>
>>>>>>> b2a0863d40e5cbe86d9781618c8e4571de9457d6
              </ul>
            </li>
            <?php }?>
          </ul> */ ?>
		  
		  {menu}
        </section>
        <section class="sidebar" style="text-align: center;">
            <img src="<?php echo base_url()?>public/themes/sik/dist/img/hati.png"/>
        </section>
        
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {title_group}
            <small>{title_form}</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url()?>"><i class="fa fa-dashboard"></i> {title_group}</a></li>
            <li class="active">{title_form}</li>
          </ol>
        </section>

        <section class="content">
          {content}
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="row">
          <div class="hidden-xs col-md-6">
            Copyright PT.Infokes Indonesia © 2006-2016 - All Right Reserved<br>
            InfoKes Manajemen Organisasi - ePuskesmas
          </div>
          <div class="hidden-xs col-md-6 pull-right" style="text-align:right">
            <img height="30" src="<?php echo base_url()?>public/themes/sik/dist/img/logo.png">
          </div>
        </div>
      </footer>
    </div><!-- ./wrapper -->

    <script src="<?php echo base_url()?>public/themes/sik/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src='<?php echo base_url()?>public/themes/sik/plugins/fastclick/fastclick.min.js'></script>
    <script src="<?php echo base_url()?>public/themes/sik/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/chartjs/Chart.min.js" type="text/javascript"></script>

  </body>
</html>