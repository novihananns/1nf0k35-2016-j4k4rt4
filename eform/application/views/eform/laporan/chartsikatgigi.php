<table class="table table-bordered table-hover">
  <tr>
    <th>Tingkat Pendidikan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <tr>
    <td><?php echo "Saat mandi pagi"; ?></td>
    <td><?php echo (isset($mandipagi)) ? $mandipagi:0;?></td>
    <td><?php echo ($mandipagi>0) ? number_format($mandipagi/$totalorang*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Saat mandi sore"; ?></td>
    <td><?php echo (isset($mandisore)) ? $mandisore : 0;?></td>
    <td><?php echo ($mandisore>0) ? number_format($mandisore/$totalorang*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Sesudah makan pagi"; ?></td>
    <td><?php echo (isset($makanpagi)) ? $makanpagi : 0;?></td>
    <td><?php echo ($makanpagi>0) ? number_format($makanpagi/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Sesudah bangun pagi"; ?></td>
    <td><?php echo (isset($banguntidur)) ? $banguntidur : 0;?></td>
    <td><?php echo ($banguntidur>0) ? number_format($banguntidur/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Sebelum tidur malam"; ?></td>
    <td><?php echo (isset($sebelumtidur)) ? $sebelumtidur : 0;?></td>
    <td><?php echo ($sebelumtidur>0) ? number_format($sebelumtidur/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Sesudah makan siang"; ?></td>
    <td><?php echo (isset($sesudahmakan)) ? $sesudahmakan : 0;?></td>
    <td><?php echo ($sesudahmakan>0) ? number_format($sesudahmakan/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <th><?php echo "Total"; ?></th>
    <th><?php echo (isset($totalorang)) ? $totalorang : 0;?></th>
    <th><?php echo ($totalorang>0) ? number_format($totalorang/$totalorang*100,2): 0;echo " %";?></th>
  </tr>
  
</table>
<div class="chart">
  <canvas id="barChart" height="400" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Saat mandi pagi</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Saat mandi sore</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>Sesudah makan pagi</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Sesudah bangun pagi</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Sebelum tidur malam</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Sesudah makan siang</label>
  </div>
</div>

<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #20ad3a;
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
        background-color: #e02a11;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #00BFFF;
        margin: 0;
        float: left;
      }
      .bux7{
        width: 10px;
        padding: 10px;
        background-color: #800080;
        margin: 0;
        float: left;
      }
      .bux9{
        width: 10px;
        padding: 10px;
        background-color: #708090;
        margin: 0;
        float: left;
      }
    </style>
<script>
$(function(){
  var areaChartData = {
        labels: [<?php 
            echo "\"".str_replace(array("KEC. ","KEL. "),"", $puskesmas)."\"";
            ?>],
        datasets: [
          {
            label: "Saat mandi pagi",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format(($mandipagi>0) ? ($mandipagi/$totalorang*100): 0,2);
                    echo "\"".$x."\"";
                  ?>]
          },{
            label: "Saat mandi sore",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php  $x = number_format(($mandisore>0) ?($mandisore/$totalorang*100):0,2);
                          echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Sesudah makan pagi",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php
                      $x = number_format(($makanpagi>0) ? ($makanpagi/$totalorang*100) :0 ,2);
                      echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Sesudah bangun pagi",
            fillColor: "#00BFFF",
            strokeColor: "#00BFFF",
            pointColor: "#00BFFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format( ($banguntidur>0) ? ($banguntidur/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Sebelum tidur malam",
            fillColor: "#800080",
            strokeColor: "#800080",
            pointColor: "#800080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format( ($sebelumtidur>0) ? ($sebelumtidur/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Sesudah makan siang",
            fillColor: "#708090",
            strokeColor: "#708090",
            pointColor: "#708090",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format(($sesudahmakan>0) ? ($sesudahmakan/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
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