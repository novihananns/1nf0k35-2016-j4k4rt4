<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Jenis Lantai Rumah</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Ubin / Keramik / Marmer</td>
        <td><?php echo $ubin;?></td>
        <td><?php echo ($ubin>0) ? number_format($ubin/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Semen / Papan</td>
        <td><?php echo $semen;?></td>
        <td><?php echo ($semen>0) ? number_format($semen/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Tanah</td>
        <td><?php echo $tanah;?></td>
        <td><?php echo ($tanah>0) ? number_format($tanah/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Lainnya</td>
        <td><?php echo $lainnya;?></td>
        <td><?php echo ($lainnya>0) ? number_format($lainnya/$jumlahorang*100,2):0; echo " %";?></td>
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
    <div class="col-md-4"></div>
    <div class="col-md-8">
      <div class="col-md-4">
          <div class="bux"></div> &nbsp; <label>Ubin / Keramik / Marmer</label>
      </div>
      <div class="col-md-3">
          <div class="bux1"></div> &nbsp; <label>Semen / Papan </label>
      </div>
      <div class="col-md-2">
          <div class="bux2"></div> &nbsp; <label>Tanah</label>
      </div>
      <div class="col-md-3">
          <div class="bux3"></div> &nbsp; <label>Lainnya</label>
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
        background-color: #00BFFF;
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
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #20ad3a;
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
              value: ";echo number_format(($ubin>0) ? $ubin/$jumlahorang*100:0,2).",
              color: \"".'#e02a11'."\",
              highlight: \"".'#e02a11'."\",
              label: \"".'Ubin / Kramik / Marmer'."\"
              },
              {
              value: ";echo number_format(($semen>0) ? $semen/$jumlahorang*100:0,2).",
              color: \"".'#00BFFF'."\",
              highlight: \"".'#00BFFF'."\",
              label: \"".'Semen / Papan'."\"
              },
              {
              value: ";echo number_format(($tanah>0) ? $tanah/$jumlahorang*100:0,2).",
              color: \"".'#800080'."\",
              highlight: \"".'#800080'."\",
              label: \"".'Tanah'."\"
              },
              {
              value: ";echo number_format(($lainnya>0) ? $lainnya/$jumlahorang*100:0,2).",
              color: \"".'#20ad3a'."\",
              highlight: \"".'#20ad3a'."\",
              label:  \"".'Lainnya'."\"
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