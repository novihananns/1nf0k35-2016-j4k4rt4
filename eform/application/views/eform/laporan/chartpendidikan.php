<table class="table table-bordered table-hover">
  <tr>
    <th>Tingkat Pendidikan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
    if(isset($rows['totalblmsekolah'])||isset($rows['totaltidaksekolah'])||isset($rows['totaltdktamatsd'])||isset($rows['totalmasihsd'])||isset($rows['totaltamatsd'])||isset($rows['totalmasihsmp'])||isset($rows['totaltamatsmp'])||isset($rows['totalmasihsma'])||isset($rows['totaltamatsma'])||isset($rows['totalmasihpt'])||isset($rows['totaltamatpt'])){
  ?>
  <tr>
    <td><?php echo "Belum Sekolah"; ?></td>
    <td><?php echo (isset($rows['blmsekolah'])) ? $rows['blmsekolah']:0;?></td>
    <td><?php echo (isset($rows['blmsekolah'])) ? number_format($rows['blmsekolah']/$rows['totalorang']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Sekolah"; ?></td>
    <td><?php echo (isset($rows['tidaksekolah'])) ? $rows['tidaksekolah'] : 0;?></td>
    <td><?php echo (isset($rows['tidaksekolah'])) ? number_format($rows['tidaksekolah']/$rows['totalorang']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Tamat SD"; ?></td>
    <td><?php echo (isset($rows['tdktamatsd'])) ? $rows['tdktamatsd'] : 0;?></td>
    <td><?php echo (isset($rows['tdktamatsd'])) ? number_format($rows['tdktamatsd']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Masih SD"; ?></td>
    <td><?php echo (isset($rows['masihsd'])) ? $rows['masihsd'] : 0;?></td>
    <td><?php echo (isset($rows['masihsd'])) ? number_format($rows['masihsd']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat SD"; ?></td>
    <td><?php echo (isset($rows['tamatsd'])) ? $rows['tamatsd'] : 0;?></td>
    <td><?php echo (isset($rows['tamatsd'])) ? number_format($rows['tamatsd']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Masih SMP"; ?></td>
    <td><?php echo (isset($rows['masihsmp'])) ? $rows['masihsmp'] : 0;?></td>
    <td><?php echo (isset($rows['masihsmp'])) ? number_format($rows['masihsmp']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat SMP"; ?></td>
    <td><?php echo (isset($rows['tamatsmp'])) ? $rows['tamatsmp'] : 0;?></td>
    <td><?php echo (isset($rows['tamatsmp'])) ? number_format($rows['tamatsmp']/$rows['totalorang']*100,2):0;?></td>
  </tr>
  <tr>
    <td><?php echo "Masih SMA"; ?></td>
    <td><?php echo (isset($rows['masihsma'])) ? $rows['masihsma'] : 0;?></td>
    <td><?php echo (isset($rows['masihsma'])) ? number_format($rows['masihsma']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat SMA"; ?></td>
    <td><?php echo (isset($rows['tamatsma'])) ? $rows['tamatsma'] : 0;?></td>
    <td><?php echo (isset($rows['tamatsma'])) ? number_format($rows['tamatsma']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Masih PT/Akademi"; ?></td>
    <td><?php echo (isset($rows['masihpt'])) ? $rows['masihpt'] : 0;?></td>
    <td><?php echo (isset($rows['masihpt'])) ? number_format($rows['masihpt']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tamat PT/Akademi"; ?></td>
    <td><?php echo (isset($rows['tamatpt'])) ? $rows['tamatpt'] : 0;?></td>
    <td><?php echo (isset($rows['tamatpt'])) ? number_format($rows['tamatpt']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <th><?php echo "Total"; ?></th>
    <th><?php echo (isset($rows['totalorang'])) ? $rows['totalorang'] : 0;?></th>
    <th><?php echo (isset($rows['totalorang'])) ? number_format($rows['totalorang']/$rows['totalorang']*100,2): 0;echo " %";?></th>
  </tr>
  <?php }else{}?>
  <?php }
   ?>
  
</table>
<div class="chart">
  <canvas id="barChart" height="500" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Belum Sekolah</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Tidak Sekolah</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>Tidak Tamat SD</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Masih SD</label>
  </div>
  <div class="col-md-2">
      <div class="bux4"></div> &nbsp; <label>Tamat SD</label>
  </div>
</div>
<div class="row">
  <div class="col-md-1">
      <div class="bux5"></div> &nbsp; <label>Masih SMP</label>
  </div>
  <div class="col-md-1">
      <div class="bux6"></div> &nbsp; <label>Tamat SMP</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Masih SMA</label>
  </div>
  <div class="col-md-2">
      <div class="bux8"></div> &nbsp; <label>Tamat SMA</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Masih PT/Akademi</label>
  </div>
  <div class="col-md-2">
      <div class="bux10"></div> &nbsp; <label>Tamat PT/Akademi</label>
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
      .bux4{
        width: 10px;
        padding: 10px;
        background-color: #00FF7F;
        margin: 0;
        float: left;
      }
      .bux5{
        width: 10px;
        padding: 10px;
        background-color: #FFA072;
        margin: 0;
        float: left;
      }
      .bux6{
        width: 10px;
        padding: 10px;
        background-color: #CD853F;
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
      .bux8{
        width: 10px;
        padding: 10px;
        background-color: #9ACD32;
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
      .bux10{
        width: 10px;
        padding: 10px;
        background-color: #FF6347;
        margin: 0;
        float: left;
      }
    </style>
<script>
$(function(){
  var areaChartData = {
        labels: [<?php 
        $i=0;
       // print_r($bar);  
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".str_replace(array("KEC. ","KEL. "),"", $row['puskesmas'])."\"";
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