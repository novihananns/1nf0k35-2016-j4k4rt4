<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Status Balita Imunisasi</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Lengkap</td>
        <td><?php echo $lengkap;?></td>
        <td><?php echo ($lengkap>0) ? number_format($lengkap/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Tidak Tahu</td>
        <td><?php echo $tidaktahu;?></td>
        <td><?php echo ($tidaktahu>0) ? number_format($tidaktahu/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Lengkap Sesuai Umur</td>
        <td><?php echo $lengkapsesuaiumur;?></td>
        <td><?php echo ($lengkapsesuaiumur>0) ? number_format($lengkapsesuaiumur/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Tidak Lengkap</td>
        <td><?php echo $tidaklengkap;?></td>
        <td><?php echo ($tidaklengkap>0) ? number_format($tidaklengkap/$jumlahorang*100,2):0; echo " %";?></td>
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
              value: ";echo number_format(($lengkap>0) ? $lengkap/$jumlahorang*100:0,2).",
              color: \"".'#e02a11'."\",
              highlight: \"".'#e02a11'."\",
              label: \"".'Lengkap'."\"
              },
              {
              value: ";echo number_format(($tidaktahu>0) ? $tidaktahu/$jumlahorang*100:0,2).",
              color: \"".'#CD853F'."\",
              highlight: \"".'#CD853F'."\",
              label: \"".'Tidak Tahu'."\"
              },
              {
              value: ";echo number_format(($lengkapsesuaiumur>0) ? $lengkapsesuaiumur/$jumlahorang*100:0,2).",
              color: \"".'#00FF7F'."\",
              highlight: \"".'#00FF7F'."\",
              label: \"".'Lengkap Sesuai Umur'."\"
              },
              
              {
              value: ";echo number_format(($tidaklengkap>0) ? $tidaklengkap/$jumlahorang*100:0,2).",
              color: \"".'#800080'."\",
              highlight: \"".'#800080'."\",
              label: \"".'Tidak Lengkap'."\"
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