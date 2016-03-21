<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Disabilitas</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Sedang</td>
        <td><?php echo $sedang;?></td>
        <td><?php echo ($sedang>0) ? number_format($sedang/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Pernah</td>
        <td><?php echo $pernah;?></td>
        <td><?php echo ($pernah>0) ? number_format($pernah/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Tidak Pernah</td>
        <td><?php echo $tidakpernah;?></td>
        <td><?php echo ($tidakpernah>0) ? number_format($tidakpernah/$jumlahorang*100,2):0; echo " %";?></td>
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
              value: ";echo number_format(($sedang>0) ? $sedang/$jumlahorang*100:0,2).",
              color: \"".'#e02a11'."\",
              highlight: \"".'#e02a11'."\",
              label: \"".'Sedang'."\"
              },
              {
              value: ";echo number_format(($pernah>0) ? $pernah/$jumlahorang*100:0,2).",
              color: \"".'#800080'."\",
              highlight: \"".'#800080'."\",
              label: \"".'Pernah'."\"
              },
              {
              value: ";echo number_format(($tidakpernah>0) ? $tidakpernah/$jumlahorang*100:0,2).",
              color: \"".'#00BFFF'."\",
              highlight: \"".'#00BFFF'."\",
              label:  \"".'Tidak Pernah'."\"
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