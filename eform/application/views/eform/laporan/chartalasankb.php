<table class="table table-bordered table-hover">
  <tr>
    <th>Alasan Tidak KB</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
  	if(isset($rows['total'])){
  ?>
  <tr>
    <td><?php echo "Sedang Hamil"; ?></td>
    <td><?php echo (isset($rows['sedanghamil'])) ? $rows['sedanghamil']:0;?></td>
    <td><?php echo (($rows['sedanghamil'])!=0) ? number_format($rows['sedanghamil']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Setuju"; ?></td>
    <td><?php echo (isset($rows['tidaksetuju'])) ? $rows['tidaksetuju'] : 0;?></td>
    <td><?php echo (($rows['tidaksetuju'])!=0) ? number_format($rows['tidaksetuju']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Tahu"; ?></td>
    <td><?php echo (isset($rows['tidaktahu'])) ? $rows['tidaktahu'] : 0;?></td>
    <td><?php echo (($rows['tidaktahu'])!=0) ? number_format($rows['tidaktahu']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Takut Efek KB"; ?></td>
    <td><?php echo (isset($rows['takutefekkb'])) ? $rows['takutefekkb'] : 0;?></td>
    <td><?php echo (($rows['takutefekkb'])!=0) ? number_format($rows['takutefekkb']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Pelayanan KB Jauh"; ?></td>
    <td><?php echo (isset($rows['pelayanankb'])) ? $rows['pelayanankb'] : 0;?></td>
    <td><?php echo (($rows['pelayanankb'])!=0) ? number_format($rows['pelayanankb']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Tidak Mampu / Mahal"; ?></td>
    <td><?php echo (isset($rows['mahalkb'])) ? $rows['mahalkb'] : 0;?></td>
    <td><?php echo (($rows['mahalkb'])!=0) ? number_format($rows['mahalkb']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Fertilasi "; ?></td>
    <td><?php echo (isset($rows['fertilasi'])) ? $rows['fertilasi'] : 0;?></td>
    <td><?php echo (($rows['fertilasi'])!=0) ? number_format($rows['fertilasi']/$rows['total']*100,2) :0;?></td>
  </tr>
  <tr>
    <td><?php echo "Lainnya"; ?></td>
    <td><?php echo (isset($rows['lainnyakb'])) ? $rows['lainnyakb'] : 0;?></td>
    <td><?php echo (($rows['lainnyakb'])!=0) ? number_format($rows['lainnyakb']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <th><?php echo "Total"; ?></th>
    <th><?php echo (isset($rows['total'])) ? $rows['total'] : 0;?></th>
    <th><?php echo (($rows['total'])!=0) ? number_format($rows['total']/$rows['total']*100,2) : 0;echo " %";?></th>
  </tr>
  <?php }else{}?>

  <?php }
 // print_r($bar);
   ?>
  
</table>
<div class="chart">
	<canvas id="barChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
</div>
<div class="row">
  <div class="col-md-3">
      <div class="bux"></div> &nbsp; <label>Sedang Hamil</label>
  </div>
  <div class="col-md-3">
      <div class="bux1"></div> &nbsp; <label>Tidak Setuju</label>
  </div>
  <div class="col-md-3">
      <div class="bux2"></div> &nbsp; <label>Tidak Tahu</label>
  </div>
  <div class="col-md-3">
      <div class="bux3"></div> &nbsp; <label>Takut Efek KB</label>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
      <div class="bux4"></div> &nbsp; <label>Pelayanan KB Jauh</label>
  </div>
  <div class="col-md-3">
      <div class="bux5"></div> &nbsp; <label>Tidak Mampu / Mahal</label>
  </div>
  <div class="col-md-3">
      <div class="bux6"></div> &nbsp; <label>Fertilasi</label>
  </div>
  <div class="col-md-3">
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
        $i=0;
       // print_r($bar);  
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".str_replace(array("KEC. ","KEL. "),"", $row['puskesmas'])."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Infant (0-12 bulan)",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['sedanghamil']))  $x = (($rows['total'])!=0) ? number_format(($row['sedanghamil']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },{
            label: "Toddler  (1-3 tahun)",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidaksetuju']))  $x = (($rows['total'])!=0) ? number_format(($row['tidaksetuju']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Preschool (4 tahun-5 tahun)",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['tidaktahu']))  $x = (($rows['total'])!=0) ? number_format(($row['tidaktahu']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Usia sekolah (6 tahun- 12 tahun)",
            fillColor: "#00BFFF",
            strokeColor: "#00BFFF",
            pointColor: "#00BFFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['takutefekkb']))  $x = (($rows['total'])!=0) ? number_format(($row['takutefekkb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Remaja ( 13 tahun-20 tahun)",
            fillColor: "#00FF7F",
            strokeColor: "#00FF7F",
            pointColor: "#00FF7F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['pelayanankb']))  $x = (($rows['total'])!=0) ? number_format(($row['pelayanankb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Dewasa (21 tahun-44 tahun)",
            fillColor: "#FFA072",
            strokeColor: "#FFA072",
            pointColor: "#FFA072",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['mahalkb']))  $x = (($rows['total'])!=0) ? number_format(($row['mahalkb']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Prelansia (45 tahun-59 tahun)",
            fillColor: "#CD853F",
            strokeColor: "#CD853F",
            pointColor: "#CD853F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['fertilasi']))  $x = (($rows['total'])!=0) ? number_format(($row['fertilasi']/$row['total']*100),2):0;
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Lansia (>60 tahun)",
            fillColor: "#800080",
            strokeColor: "#800080",
            pointColor: "#800080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['lainnyakb']))  $x = (($rows['total'])!=0) ? number_format(($row['lainnyakb']/$row['total']*100),2):0;
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