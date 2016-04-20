<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Desa</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <?php 
      if (isset($showkelamin)) {
        foreach ($showkelamin as $key) { ?>
        <tr>
          <td><?php echo $key->value; ?></td>
          <td><?php echo $key->jumlah;?></td>
          <td><?php echo ($jumlahorang>0) ? number_format($key->jumlah/$jumlahorang*100,2):0; echo " %";?></td>
        </tr>
      <?php    
        }
      }
      ?>
      
    </table>
  </div>
  <div class="col-md-6">
    <div class="row" id="row1">
      <div class="chart">
        <canvas id="barChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
      </div>
    </div>
  </div>
</div>
<?php  print_r($bar);?>
<script>
  $(function () { 
    
    var areaChartData = {
        labels: [<?php 
        $i=0;
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".$row['id_desa']."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Laki-laki",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['jumlah']))  $x = ($row['jumlah']);
              else                           $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Perempuan",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['jumlah']))  $x = ($row['jumlah']);
              else                            $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
        ]
       /* datasets: [
        <?php /*
          $i=0;
          foreach ($bar as $row ) { 
            echo "
            {
              label: 'Laki-laki',
              fillColor: '#20ad3a',
              strokeColor: '#20ad3a',
              pointColor: '#20ad3a',
              pointStrokeColor: '#c1c7d1',
              pointHighlightFill: '#fff',
              pointHighlightStroke: 'rgba(220,220,220,1)',
              data: [3]
            },";
          }*/
          ?>
        ]*/
      };
//-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        var barChartOptions = {
          scaleBeginAtZero: true,
          scaleShowGridLines: true,
          scaleGridLineColor: "rgba(0,0,0,.05)",
          scaleGridLineWidth: 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines: true,
          barShowStroke: true,
          barStrokeWidth: 2,
          barValueSpacing: 5,
          barDatasetSpacing: 1,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          responsive: true,
          maintainAspectRatio: false
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
  });
</script>