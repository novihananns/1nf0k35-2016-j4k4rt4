<table class="table table-bordered table-hover">
  <tr>
    <th>Pekerjaan</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
    if(isset($rows['totalpetani'])||isset($rows['totalnelayan'])||isset($rows['totalpnstniporli'])||isset($rows['totalswasta'])||isset($rows['totalwiraswasta'])||isset($rows['totalpensiunan'])||isset($rows['totalpekerjalepas'])||isset($rows['totallainnya'])||isset($rows['totaltidakbelumkerja'])||isset($rows['totalbekerja'])||isset($rows['totalbelumkerja'])||isset($rows['totaltidakkerja'])||isset($rows['totalirt'])){
  ?>
  <tr>
    <td><?php echo "Petani"; ?></td>
    <td><?php echo (isset($rows['petani'])) ? $rows['petani']:0;?></td>
    <td><?php echo (isset($rows['petani'])) ? number_format($rows['petani']/$rows['totalorang']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Nelayan"; ?></td>
    <td><?php echo (isset($rows['nelayan'])) ? $rows['nelayan'] : 0;?></td>
    <td><?php echo (isset($rows['nelayan'])) ? number_format($rows['nelayan']/$rows['totalorang']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "PNS / Porli / TNI"; ?></td>
    <td><?php echo (isset($rows['pnstniporli'])) ? $rows['pnstniporli'] : 0;?></td>
    <td><?php echo (isset($rows['pnstniporli'])) ? number_format($rows['pnstniporli']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Swasta"; ?></td>
    <td><?php echo (isset($rows['swasta'])) ? $rows['swasta'] : 0;?></td>
    <td><?php echo (isset($rows['swasta'])) ? number_format($rows['swasta']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Wiraswasta"; ?></td>
    <td><?php echo (isset($rows['wiraswasta'])) ? $rows['wiraswasta'] : 0;?></td>
    <td><?php echo (isset($rows['wiraswasta'])) ? number_format($rows['wiraswasta']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Pensiunan"; ?></td>
    <td><?php echo (isset($rows['pensiunan'])) ? $rows['pensiunan'] : 0;?></td>
    <td><?php echo (isset($rows['pensiunan'])) ? number_format($rows['pensiunan']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Pekerja Lepas"; ?></td>
    <td><?php echo (isset($rows['pekerjalepas'])) ? $rows['pekerjalepas'] : 0;?></td>
    <td><?php echo (isset($rows['pekerjalepas'])) ? number_format($rows['pekerjalepas']/$rows['totalorang']*100,2):0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak / Belum Bekerja"; ?></td>
    <td><?php echo (isset($rows['tidakbelumkerja'])) ? $rows['tidakbelumkerja'] : 0;?></td>
    <td><?php echo (isset($rows['tidakbelumkerja'])) ? number_format($rows['tidakbelumkerja']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Bekerja"; ?></td>
    <td><?php echo (isset($rows['bekerja'])) ? $rows['bekerja'] : 0;?></td>
    <td><?php echo (isset($rows['bekerja'])) ? number_format($rows['bekerja']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Belum Kerja"; ?></td>
    <td><?php echo (isset($rows['belumkerja'])) ? $rows['belumkerja'] : 0;?></td>
    <td><?php echo (isset($rows['belumkerja'])) ? number_format($rows['belumkerja']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Kerja"; ?></td>
    <td><?php echo (isset($rows['tidakkerja'])) ? $rows['tidakkerja'] : 0;?></td>
    <td><?php echo (isset($rows['tidakkerja'])) ? number_format($rows['tidakkerja']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "IRT"; ?></td>
    <td><?php echo (isset($rows['irt'])) ? $rows['irt'] : 0;?></td>
    <td><?php echo (isset($rows['irt'])) ? number_format($rows['irt']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Pedagang"; ?></td>
    <td><?php echo (isset($rows['pedagang'])) ? $rows['pedagang'] : 0;?></td>
    <td><?php echo (isset($rows['pedagang'])) ? number_format($rows['pedagang']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Lainnya"; ?></td>
    <td><?php echo (isset($rows['lainnya'])) ? $rows['lainnya'] : 0;?></td>
    <td><?php echo (isset($rows['lainnya'])) ? number_format($rows['lainnya']/$rows['totalorang']*100,2): 0;?></td>
  </tr>
  <tr>
    <th><?php echo "Total"; ?></th>
    <th><?php echo (isset($rows['totalorang'])) ? $rows['totalorang'] : 0;?></th>
    <th><?php echo (isset($rows['totalorang'])) ? number_format($rows['totalorang']/$rows['totalorang']*100,2): 0;echo " %";?></th>
  </tr>
  <?php }else{ }?>
  <?php }
 // print_r($bar);
   ?>
  
</table>
<div class="chart">
  <canvas id="barChart" height="500" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Petani</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Nelayan</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>PNS / Porli / TNI</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Swasta</label>
  </div>
  <div class="col-md-2">
      <div class="bux4"></div> &nbsp; <label>Wirawasta</label>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux5"></div> &nbsp; <label>Pensiunan</label>
  </div>
  <div class="col-md-2">
      <div class="bux6"></div> &nbsp; <label>Pekerja Lepas</label>
  </div>
  <div class="col-md-2">
      <div class="bux8"></div> &nbsp; <label>Tidak/Belum Bekerja</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Bekerja</label>
  </div>
  <div class="col-md-2">
      <div class="bux10"></div> &nbsp; <label>Belum Bekerja</label>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux11"></div> &nbsp; <label>Tidak Bekerja</label>
  </div>
  <div class="col-md-2">
      <div class="bux12"></div> &nbsp; <label>IRT</label>
  </div>
  <div class="col-md-2">
      <div class="bux13"></div> &nbsp; <label>Pedagang</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Lainnya</label>
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
      .bux11{
        width: 10px;
        padding: 10px;
        background-color: #808000;
        margin: 0;
        float: left;
      }
      .bux12{
        width: 10px;
        padding: 10px;
        background-color: #808080;
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
      .bux13{
        width: 10px;
        padding: 10px;
        background-color: #FF1493;
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
            label: "Petani",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['petani']))  $x = number_format(($row['petani']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },{
            label: "Nelayan",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['nelayan']))  $x = number_format(($row['nelayan']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "PNS / TNI / Porli",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pnstniporli']))  $x = number_format(($row['pnstniporli']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Swasta",
            fillColor: "#00BFFF",
            strokeColor: "#00BFFF",
            pointColor: "#00BFFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['swasta']))  $x = number_format(($row['swasta']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Wirawasta",
            fillColor: "#00FF7F",
            strokeColor: "#00FF7F",
            pointColor: "#00FF7F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['wiraswasta']))  $x = number_format(($row['wiraswasta']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Pensiunan",
            fillColor: "#FFA072",
            strokeColor: "#FFA072",
            pointColor: "#FFA072",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pensiunan']))  $x = number_format(($row['pensiunan']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Pekerja Lepas",
            fillColor: "#CD853F",
            strokeColor: "#CD853F",
            pointColor: "#CD853F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pekerjalepas']))  $x = number_format(($row['pekerjalepas']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Belum / Tidak Kerja",
            fillColor: "#9ACD32",
            strokeColor: "#9ACD32",
            pointColor: "#9ACD32",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidakbelumkerja']))  $x = number_format(($row['tidakbelumkerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Bekerja",
            fillColor: "#708090",
            strokeColor: "#708090",
            pointColor: "#708090",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['bekerja']))  $x = number_format(($row['bekerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Belum Kerja",
            fillColor: "#FF6347",
            strokeColor: "#FF6347",
            pointColor: "#FF6347",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['belumkerja']))  $x = number_format(($row['belumkerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Tidak Kerja",
            fillColor: "#808000",
            strokeColor: "#808000",
            pointColor: "#808000",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidakkerja']))  $x = number_format(($row['tidakkerja']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "IRT",
            fillColor: "#808080",
            strokeColor: "#808080",
            pointColor: "#808080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['irt']))  $x = number_format(($row['irt']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Pedagang",
            fillColor: "#FF1493",
            strokeColor: "#FF1493",
            pointColor: "#FF1493",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pedagang']))  $x = number_format(($row['pedagang']/$row['totalorang']*100),2);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Lainnya",
            fillColor: "#800080",
            strokeColor: "#800080",
            pointColor: "#800080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['lainnya']))  $x = number_format(($row['lainnya']/$row['totalorang']*100),2);
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