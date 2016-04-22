<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Desa</th>
        <th>Jumlah</th>
      </tr>
      <?php 
      if (isset($bar)) {
        foreach ($bar as $key) { ?>
        <tr>
          <td>Pasar Rebo</td>
          <td><?php echo $key['3172100'];?></td>
        </tr>
        <tr>
          <td>Ciracas</td>
          <td><?php echo $key['3172020'];?></td>
        </tr>
        <tr>
          <td>Cipayung</td>
          <td><?php echo $key['3172030'];?></td>
        </tr>
        <tr>
          <td>Makasar</td>
          <td><?php echo $key['3172040'];?></td>
        </tr>
        <tr>
          <td>Kramat Jati</td>
          <td><?php echo $key['3172050'];?></td>
        </tr>
        <tr>
          <td>Jatinegara</td>
          <td><?php echo $key['3172060'];?></td>
        </tr>
        <tr>
          <td>Duren Sawit</td>
          <td><?php echo $key['3172070'];?></td>
        </tr>
        <tr>
          <td>Cakung</td>
          <td><?php echo $key['3172080'];?></td>
        </tr>
        <tr>
          <td>Pulo Gadung</td>
          <td><?php echo $key['3172090'];?></td>
        </tr>
        <tr>
          <td>Matraman</td>
          <td><?php echo $key['3172100'];?></td>
        </tr>
      <?php    
        }
      }
      print_r($bar);
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
  <div class="row">
  <div class="col-md-2">
      <div class="bux"></div> &nbsp; <label>Pasar Rebo</label>
  </div>
  <div class="col-md-2">
      <div class="bux1"></div> &nbsp; <label>Ciracas</label>
  </div>
  <div class="col-md-2">
      <div class="bux2"></div> &nbsp; <label>Cipayung</label>
  </div>
  <div class="col-md-2">
      <div class="bux3"></div> &nbsp; <label>Makasar</label>
  </div>
  <div class="col-md-2">
      <div class="bux4"></div> &nbsp; <label>Kramat Jati</label>
  </div>
</div>
<div class="row">
  <div class="col-md-2">
      <div class="bux5"></div> &nbsp; <label>Jatinegara</label>
  </div>
  <div class="col-md-2">
      <div class="bux6"></div> &nbsp; <label>Duren Sawit</label>
  </div>
  <div class="col-md-2">
      <div class="bux7"></div> &nbsp; <label>Cakung</label>
  </div>
  <div class="col-md-2">
      <div class="bux8"></div> &nbsp; <label>Pulo Gadung</label>
  </div>
  <div class="col-md-2">
      <div class="bux9"></div> &nbsp; <label>Matraman</label>
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
    </style>
</div>
<script>
  $(function () { 
    var safeColors = ['00','33','66','99','cc','ff'];
    var rand = function() {
        return Math.floor(Math.random()*6);
    };
    var randomColor = function() {
        var r = safeColors[rand()];
        var g = safeColors[rand()];
        var b = safeColors[rand()];
        return "#"+r+g+b;
    };
      var areaChartData = {
        labels: [<?php 
        $i=0;
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".$row['nama']."\"";
          $i++;
        } ?>],
        datasets: [
          {
            fillColor:"#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172010']))  $x =$row['3172010'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172020']))  $x =$row['3172020'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172030']))  $x =$row['3172030'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#00BFFF",
            strokeColor: "#00BFFF",
            pointColor: "#00BFFF",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172040']))  $x =$row['3172040'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#00FF7F",
            strokeColor: "#00FF7F",
            pointColor: "#00FF7F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172050']))  $x =$row['3172050'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#FFA072",
            strokeColor: "#FFA072",
            pointColor: "#FFA072",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172060']))  $x =$row['3172060'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#CD853F",
            strokeColor: "#CD853F",
            pointColor: "#CD853F",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172070']))  $x =$row['3172070'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#800080",
            strokeColor: "#800080",
            pointColor: "#800080",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172080']))  $x =$row['3172080'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#9ACD32",
            strokeColor: "#9ACD32",
            pointColor: "#9ACD32",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172090']))  $x =$row['3172090'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            fillColor:"#708090",
            strokeColor: "#708090",
            pointColor: "#708090",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['3172100']))  $x =$row['3172100'];
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
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