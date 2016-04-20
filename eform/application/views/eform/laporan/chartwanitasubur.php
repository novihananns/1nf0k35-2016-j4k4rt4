<div class="row">
  <div class="col-md-12">
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
  <div class="col-md-12">
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
       // print_r($bar);  
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".$row['value']."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Belum Sekolah",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['blmsekolah']))  $x = number_format(($row['blmsekolah']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },{
            label: "Tidak Sekolah",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidaksekolah']))  $x = number_format(($row['tidaksekolah']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tidak Tamat SD",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tdktamatsd']))  $x = number_format(($row['tdktamatsd']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih SD",
            fillColor: "#00BFFF",
            strokeColor: "#00BFFF",
            pointColor: "#00BFFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihsd']))  $x = number_format(($row['masihsd']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat SD",
            fillColor: "#00FF7F",
            strokeColor: "#00FF7F",
            pointColor: "#00FF7F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatsd']))  $x = number_format(($row['tamatsd']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih SMP",
            fillColor: "#FFA072",
            strokeColor: "#FFA072",
            pointColor: "#FFA072",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihsmp']))  $x = number_format(($row['masihsmp']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat SMP",
            fillColor: "#CD853F",
            strokeColor: "#CD853F",
            pointColor: "#CD853F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatsmp']))  $x = number_format(($row['tamatsmp']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih SMA",
            fillColor: "#800080",
            strokeColor: "#800080",
            pointColor: "#800080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihsma']))  $x = number_format(($row['masihsma']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat SMA",
            fillColor: "#9ACD32",
            strokeColor: "#9ACD32",
            pointColor: "#9ACD32",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatsma']))  $x = number_format(($row['tamatsma']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Masih PT/Akademi",
            fillColor: "#708090",
            strokeColor: "#708090",
            pointColor: "#708090",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['masihpt']))  $x = number_format(($row['masihpt']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tamat PT/Akademi",
            fillColor: "#FF6347",
            strokeColor: "#FF6347",
            pointColor: "#FF6347",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tamatpt']))  $x = number_format(($row['tamatpt']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          }
        ]
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