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
          <td><?php echo $key['nama'];?></td>
          <td><?php echo $key[$key['code']];?></td>
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
  <div class="row">
<?php 
$i=0;
if (isset($bar)) {
  foreach ($bar as $key) { ?>
      <div class="col-md-2">
          <div class="bux<?php echo $i?>"></div> &nbsp; <label><?php echo $key['nama'];?></label>
      </div>
<?php
  $i++;   
        if ($i==5) {
           echo "<br/>";
         } 
    }
  }
?>
</div>
<style type="text/css">
<?php 
$i=0;
if (isset($bar)) {
  foreach ($bar as $key) { ?>
      .bux<?php echo $i?>{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: <?php echo $color[$i]?>;
        margin: 0;
        float: left;
      }
<?php
  $i++;   
    }
  }
?>
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
        labels: ['Wanita Subur (16-49)'],
        datasets: [
        <?php 
        $i=0;
        if (isset($bar)) {
          foreach ($bar as $key) { ?>
              {
                fillColor:"<?php echo $color[$i];?>",
                strokeColor: "<?php echo $color[$i];?>",
                pointColor: "<?php echo $color[$i];?>",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?php 
                  if(isset($key[$key['code']]))  $x =$key[$key['code']];
                  else                              $x = 0;
                  echo "\"".$x."\""; ?>]
              },
        <?php
          $i++;   
            }
          }
        ?>
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