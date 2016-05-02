<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Penderita TB Paru</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Ya, < 2 minggu terakhir</td>
        <td><?php echo $kurang;?></td>
        <td><?php echo ($kurang>0) ? number_format($kurang/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Ya, ≥ 2 minggu</td>
        <td><?php echo $lebih;?></td>
        <td><?php echo ($lebih>0) ? number_format($lebih/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Tidak</td>
        <td><?php echo $tidak;?></td>
        <td><?php echo ($tidak>0) ? number_format($tidak/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <th>Total</th>
        <th><?php echo $jumlahorang; ?></th>
        <th><?php echo ($jumlahorang>0) ? $jumlahorang/$jumlahorang*100 : 0; echo " %";?></th>
      </tr>
      
    </table>
  </div>
  <div class="col-md-6">
    <div class="row" id="row1">
      <div class="chart">
        <canvas id="pieChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
      </div>
    </div>
  </div>
  </div>
  <div class="row"> 
    <div class="col-md-6"></div>
    <div class="col-md-6">
      <div class="col-md-5">
          <div class="bux"></div> &nbsp; <label>Ya, < 2 minggu terakhir</label>
      </div>
      <div class="col-md-4">
          <div class="bux1"></div> &nbsp; <label>Ya, ≥ 2 minggu</label>
      </div>
      <div class="col-md-3">
          <div class="bux2"></div> &nbsp; <label>Tidak</label>
      </div>
    </div>
  </div>    
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #e02a11;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #ffb400;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #800080;
        margin: 0;
        float: left;
      }
      
</style>
<?php // print_r($bar);?>
<script>
  $(function () { 
    
    //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
            
            echo "
              {
              value: ";echo number_format(($kurang>0) ? $kurang/$jumlahorang*100:0,2).",
              color: \"".'#e02a11'."\",
              highlight: \"".'#e02a11'."\",
              label: \"".'Ya, < 2 minggu terakhir'."\"
              },
              {
              value: ";echo number_format(($lebih>0) ? $lebih/$jumlahorang*100:0,2).",
              color: \"".'#ffb400'."\",
              highlight: \"".'#ffb400'."\",
              label: \"".'Ya, ≥ 2 minggu'."\"
              },
              {
              value: ";echo number_format(($tidak>0) ? $tidak/$jumlahorang*100:0,2).",
              color: \"".'#800080'."\",
              highlight: \"".'#800080'."\",
              label: \"".'Tidak'."\"
              }"; 
            ?>

        ];
        var pieOptions = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 40, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: false,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        pieChart.Doughnut(PieData, pieOptions);
  });
</script>