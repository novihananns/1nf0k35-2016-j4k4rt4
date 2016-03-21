<table class="table table-bordered table-hover">
  <tr>
    <th>Usia</th>
    <th>Jumlah</th>
    <th>Persentase</th>
  </tr>
  <?php 
  foreach ($bar as $rows ) {  
  	if(isset($rows['total'])){
  ?>
  <tr>
    <td><?php echo "Infant  (0-12 bulan)"; ?></td>
    <td><?php echo (isset($rows['jmlinfant'])) ? $rows['jmlinfant']:0;?></td>
    <td><?php echo (($rows['jmlinfant'])!=0) ? number_format($rows['jmlinfant']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Toddler  (1-3 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmltoddler'])) ? $rows['jmltoddler'] : 0;?></td>
    <td><?php echo (($rows['jmltoddler'])!=0) ? number_format($rows['jmltoddler']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Preschool (4 tahun-5 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmlpreschool'])) ? $rows['jmlpreschool'] : 0;?></td>
    <td><?php echo (($rows['jmlpreschool'])!=0) ? number_format($rows['jmlpreschool']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Usia sekolah (6 tahun- 12 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmlsekolah'])) ? $rows['jmlsekolah'] : 0;?></td>
    <td><?php echo (($rows['jmlsekolah'])!=0) ? number_format($rows['jmlsekolah']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Remaja ( 13 tahun-20 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmlremaja'])) ? $rows['jmlremaja'] : 0;?></td>
    <td><?php echo (($rows['jmlremaja'])!=0) ? number_format($rows['jmlremaja']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Dewasa (21 tahun-44 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmldewasa'])) ? $rows['jmldewasa'] : 0;?></td>
    <td><?php echo (($rows['jmldewasa'])!=0) ? number_format($rows['jmldewasa']/$rows['total']*100,2) : 0;?></td>
  </tr>
  <tr>
    <td><?php echo "Prelansia (45 tahun-59 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmlprelansia'])) ? $rows['jmlprelansia'] : 0;?></td>
    <td><?php echo (($rows['jmlprelansia'])!=0) ? number_format($rows['jmlprelansia']/$rows['total']*100,2) :0;?></td>
  </tr>
  <tr>
    <td><?php echo "Lansia (>60 tahun)"; ?></td>
    <td><?php echo (isset($rows['jmllansia'])) ? $rows['jmllansia'] : 0;?></td>
    <td><?php echo (($rows['jmllansia'])!=0) ? number_format($rows['jmllansia']/$rows['total']*100,2) : 0;?></td>
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
      <div class="bux"></div> &nbsp; <label>Infant (0-12 bulan)</label>
  </div>
  <div class="col-md-3">
      <div class="bux1"></div> &nbsp; <label>Toddler (1-3 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux2"></div> &nbsp; <label>Preschool (4 tahun-5 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux3"></div> &nbsp; <label>Usia sekolah (6 tahun- 12 tahun)</label>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
      <div class="bux4"></div> &nbsp; <label>Remaja ( 13 tahun-20 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux5"></div> &nbsp; <label>Dewasa (21 tahun-44 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux6"></div> &nbsp; <label>Prelansia (45 tahun-59 tahun)</label>
  </div>
  <div class="col-md-3">
      <div class="bux7"></div> &nbsp; <label>Lansia (>60 tahun)</label>
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
              if(isset($row['jmlinfant']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlinfant']/$row['total']*100),2):0;
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
              if(isset($row['jmltoddler']))  $x = (($rows['total'])!=0) ? number_format(($row['jmltoddler']/$row['total']*100),2):0;
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
              if(isset($row['jmlpreschool']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlpreschool']/$row['total']*100),2):0;
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
              if(isset($row['jmlsekolah']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlsekolah']/$row['total']*100),2):0;
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
              if(isset($row['jmlremaja']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlremaja']/$row['total']*100),2):0;
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
              if(isset($row['jmldewasa']))  $x = (($rows['total'])!=0) ? number_format(($row['jmldewasa']/$row['total']*100),2):0;
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
              if(isset($row['jmlprelansia']))  $x = (($rows['total'])!=0) ? number_format(($row['jmlprelansia']/$row['total']*100),2):0;
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
              if(isset($row['jmllansia']))  $x = (($rows['total'])!=0) ? number_format(($row['jmllansia']/$row['total']*100),2):0;
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