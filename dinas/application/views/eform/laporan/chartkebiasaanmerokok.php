<table class="table table-bordered table-hover">
  <tr>
    <th>Tingkat Pendidikan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <tr>
    <td><?php echo "Ya, setiap hari"; ?></td>
    <td><?php echo (isset($tiaphari)) ? $tiaphari:0;?></td>
    <td><?php echo ($tiaphari>0) ? number_format($tiaphari/$totalorang*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Ya, kadang-kadang"; ?></td>
    <td><?php echo (isset($kadang)) ? $kadang : 0;?></td>
    <td><?php echo ($kadang>0) ? number_format($kadang/$totalorang*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Sesudah makan pagi"; ?></td>
    <td><?php echo (isset($dulu)) ? $dulu : 0;?></td>
    <td><?php echo ($dulu>0) ? number_format($dulu/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak, tapi dulu kadang-kadang"; ?></td>
    <td><?php echo (isset($dulukadang)) ? $dulukadang : 0;?></td>
    <td><?php echo ($dulukadang>0) ? number_format($dulukadang/$totalorang*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak pernah sama sekali"; ?></td>
    <td><?php echo (isset($tidakpernah)) ? $tidakpernah : 0;?></td>
    <td><?php echo ($tidakpernah>0) ? number_format($tidakpernah/$totalorang*100,2): 0;?></td>
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
      <div class="bux"></div> &nbsp; <label>Ya, setiap hari</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Ya, kadang-kadang</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>Tidak, tapi dulu merokok setiap hari</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Tidak, tapi dulu kadang-kadang</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Tidak pernah sama sekali</label>
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
    </style>
<script>
$(function(){
  var areaChartData = {
        labels: [<?php 
            echo "\"".$puskesmas."\"";
            ?>],
        datasets: [
          {
            label: "Ya, setiap hari",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format(($tiaphari>0) ? ($tiaphari/$totalorang*100): 0,2);
                    echo "\"".$x."\"";
                  ?>]
          },{
            label: "Ya, kadang-kadang",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php  $x = number_format(($kadang>0) ?($kadang/$totalorang*100):0,2);
                          echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Tidak, tapi dulu merokok setiap hari",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php
                      $x = number_format(($dulu>0) ? ($dulu/$totalorang*100) :0 ,2);
                      echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Tidak, tapi dulu kadang-kadang",
            fillColor: "#00BFFF",
            strokeColor: "#00BFFF",
            pointColor: "#00BFFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format( ($dulukadang>0) ? ($dulukadang/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          },
          {
            label: "Tidak pernah sama sekali",
            fillColor: "#800080",
            strokeColor: "#800080",
            pointColor: "#800080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
                    $x = number_format( ($tidakpernah>0) ? ($tidakpernah/$totalorang*100):0,2);
                    echo "\"".$x."\"";
                  ?>]
          },
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