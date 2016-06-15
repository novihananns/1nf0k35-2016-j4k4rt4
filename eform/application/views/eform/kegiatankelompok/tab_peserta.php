<script>
  $(function() {
        $('#jqxTabsdppp').jqxTabs({ width: '100%', height: '1000'});
        var loadPage = function (url, tabIndex) {
            $.get(url, function (data) {
                $('#tabaddpeserta' + tabIndex).html(data);
            });
        }

        loadPage('<?php echo base_url()?>eform/kegiatankelompok/form_tab_dpp/1/{id_pegawai}/{tahun}/{id_mst_peg_struktur_org}/0', 1);
        $('#jqxTabsdppp').on('selected', function (event) {
            var pageIndex = event.args.item + 1;
            loadPage('<?php echo base_url()?>eform/kegiatankelompok/form_tab_dpp/'+pageIndex+'/{id_pegawai}/{tahun}/{id_mst_peg_struktur_org}/0', pageIndex);
        });

  });
</script>


<div id='jqxWidgetJabatan'>
    <div id='jqxTabsdppp'>
        <ul>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Pengukuran</div>
              </div>
            </li>
            <li style="margin-left: 15px;">
              <div style="height: 20px; margin-top: 5px;">
                  <div style="float: left;">
                      <i class="icon fa fa-plus" style="font-size: 18px"></i>
                  </div>
                  <div style="margin-left: 10px; vertical-align: middle; text-align: center; float: left;">
                      Penilaian DP3</div>
              </div>
            </li>
        </ul>
        <div id="tabaddpeserta1" style="background: #FAFAFA">
        </div>
        <div id="tabaddpeserta2" style="background: #FAFAFA">
        </div>
    </div>
</div>

